<?php

namespace App\Services;

use App\Models\UserModel;
use App\Models\HistoriqueTransactionModel;
use App\Models\FraisOperationModel;
use App\Models\ConfigurationModel;
use App\Models\OperateurModel;

class TransactionService
{
    private const TYPE_DEPOT = 1;
    private const TYPE_RETRAIT = 2;
    private const TYPE_TRANSFERT = 3;

    protected UserModel $userModel;
    protected HistoriqueTransactionModel $historiqueModel;
    protected FraisOperationModel $fraisModel;
    protected ConfigurationModel $configurationModel;
    protected OperateurModel $operateurModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->historiqueModel = new HistoriqueTransactionModel();
        $this->fraisModel = new FraisOperationModel();
        $this->configurationModel = new ConfigurationModel();
        $this->operateurModel = new OperateurModel();
    }

    private function calculerFrais(float $montant, int $typeOperationId): float
    {
        $tranche = $this->fraisModel
            ->where('type_operation_id', $typeOperationId)
            ->where('montant_min <=', $montant)
            ->where('montant_max >=', $montant)
            ->first();

        return $tranche ? (float) $tranche['frais'] : 0;
    }

    /**
     * Le solde n'est pas stocké sur la table user : il est recalculé
     * à partir de l'historique des transactions (comme dans UserService::soldeClient).
     */
    private function soldeUser(int $userId): float
    {
        $transactions = $this->historiqueModel
            ->where('user_id', $userId)
            ->findAll();

        $solde = 0.0;

        foreach ($transactions as $transaction) {
            if ($transaction['type_mouvement'] === 'credit') {
                $solde += (float) $transaction['montant'];
            } elseif ($transaction['type_mouvement'] === 'debit') {
                $solde -= (float) $transaction['montant'];
            }
        }

        return $solde;
    }

    public function depot(int $userId, float $montant): array
    {
        if ($montant <= 0) {
            throw new \RuntimeException("Le montant doit être positif.");
        }

        $user = $this->userModel->find($userId);
        if (!$user) {
            throw new \RuntimeException("Utilisateur introuvable.");
        }

        $db = db_connect();
        $db->transStart();

        $this->historiqueModel->insert([
            'montant' => $montant,
            'frais' => 0,
            'frais_operateur2' => 0,
            'type_mouvement' => 'credit',
            'user_id' => $userId,
            'destinataire_numero' => null,
            'type_operation_id' => self::TYPE_DEPOT,
            'frais_retrait_inclus' => 0,
        ]);

        $db->transComplete();

        $user['solde'] = $this->soldeUser($userId);

        return $user;
    }

    public function retrait(int $userId, float $montant): array
    {
        if ($montant <= 0) {
            throw new \RuntimeException("Le montant doit être positif.");
        }

        $user = $this->userModel->find($userId);
        if (!$user) {
            throw new \RuntimeException("Utilisateur introuvable.");
        }

        $solde = $this->soldeUser($userId);
        $frais = $this->calculerFrais($montant, self::TYPE_RETRAIT);
        $totalDebit = $montant + $frais;

        if ($solde < $totalDebit) {
            throw new \RuntimeException("Solde insuffisant.");
        }

        $db = db_connect();
        $db->transStart();

        $this->historiqueModel->insert([
            'montant' => $montant,
            'frais' => $frais,
            'frais_operateur2' => 0,
            'type_mouvement' => 'debit',
            'user_id' => $userId,
            'destinataire_numero' => null,
            'type_operation_id' => self::TYPE_RETRAIT,
            'frais_retrait_inclus' => 0,
        ]);

        $db->transComplete();

        $user['solde'] = $this->soldeUser($userId);

        return $user;
    }

    /**
     * Envoi multiple : le montant est divisé entre chaque destinataire.
     * Règle métier : tous les destinataires doivent appartenir au même opérateur.
     */
    public function transfertMultiple(
        int $userId,
        array $destinataires,
        float $montantTotal
    ): array {

        if (empty($destinataires)) {
            throw new \RuntimeException("Aucun destinataire.");
        }

        if ($montantTotal <= 0) {
            throw new \RuntimeException("Le montant doit être positif.");
        }

        $nombre = count($destinataires);
        $montantParDestinataire = $montantTotal / $nombre;

        /*
            Vérification même opérateur
        */
        $operateurId = null;
        foreach ($destinataires as $dest) {
            $telephone = $dest['telephone'];
            $prefix = substr($telephone, 0, 3);
            $config = $this->configurationModel
                ->where('prefix', $prefix)
                ->first();

            if (!$config) {
                throw new \RuntimeException(
                    "Préfixe inconnu : " . $prefix
                );
            }

            if ($operateurId === null) {
                $operateurId = $config['operateur_id'];
            } elseif ($operateurId != $config['operateur_id']) {
                throw new \RuntimeException(
                    "Tous les destinataires doivent appartenir au même opérateur."
                );
            }
        }

        /*
            Vérification du solde AVANT toute écriture, sur le total
            (montant + tous les frais cumulés), pour éviter un transfert
            partiel si le solde est insuffisant à mi-parcours.
        */
        $fraisTotalEstime = 0.0;
        foreach ($destinataires as $dest) {
            $inclureFrais = !empty($dest['inclure_frais']);
            $fraisTotalEstime += $this->calculerFraisEnvoi(
                $montantParDestinataire,
                $inclureFrais,
                $operateurId
            );
        }

        $solde = $this->soldeUser($userId);
        if ($solde < ($montantTotal + $fraisTotalEstime)) {
            throw new \RuntimeException("Solde insuffisant.");
        }

        /*
            Effectuer chaque transfert
        */
        $result = null;
        foreach ($destinataires as $dest) {
            $inclureFrais = !empty($dest['inclure_frais']);
            $result = $this->transfertAvecOption(
                $userId,
                $dest['telephone'],
                $montantParDestinataire,
                $inclureFrais,
                $operateurId
            );
        }

        return $result;
    }

    /**
     * Calcule les frais applicables à un envoi vers un destinataire.
     * - Le frais de transfert s'applique toujours.
     * - Le frais de retrait ne s'ajoute QUE si l'option est cochée
     *   ET que l'opérateur du destinataire est l'opérateur principal :
     *   il n'y a pas de frais de retrait pour les autres opérateurs.
     */
    private function calculerFraisEnvoi(float $montant, bool $inclureFraisRetrait, int $operateurId): float
    {
        $frais = $this->calculerFrais($montant, self::TYPE_TRANSFERT);

        if ($inclureFraisRetrait && !$this->estAutreOperateur($operateurId)) {
            $frais += $this->calculerFrais($montant, self::TYPE_RETRAIT);
        }

        return $frais;
    }

    private function transfertAvecOption(
        int $userId,
        string $telephoneDestinataire,
        float $montant,
        bool $inclureFraisRetrait,
        int $operateurId
    ): array {

        $emetteur = $this->userModel->find($userId);

        if (!$emetteur) {
            throw new \RuntimeException("Utilisateur introuvable.");
        }

        $destinataire = $this->userModel
            ->where('telephone', $telephoneDestinataire)
            ->first();
        if (!$destinataire) {
            throw new \RuntimeException("Destinataire introuvable : " . $telephoneDestinataire);
        }

        if ($destinataire['id'] == $emetteur['id']) {
            throw new \RuntimeException("Impossible de transférer à vous-même.");
        }

        $fraisRetraitInclus = $inclureFraisRetrait && !$this->estAutreOperateur($operateurId);
        $frais = $this->calculerFraisEnvoi($montant, $inclureFraisRetrait, $operateurId);
        $totalDebit = $montant + $frais;

        $soldeEmetteur = $this->soldeUser($emetteur['id']);
        if ($soldeEmetteur < $totalDebit) {
            throw new \RuntimeException("Solde insuffisant.");
        }

        $db = db_connect();
        $db->transStart();

        $this->historiqueModel->insert([
            'montant' => $montant,
            'frais' => $frais,
            'frais_operateur2' => 0,
            'type_mouvement' => 'debit',
            'user_id' => $emetteur['id'],
            'destinataire_numero' => $destinataire['telephone'],
            'type_operation_id' => self::TYPE_TRANSFERT,
            'frais_retrait_inclus' => $fraisRetraitInclus ? 1 : 0,
        ]);

        $this->historiqueModel->insert([
            'montant' => $montant,
            'frais' => 0,
            'frais_operateur2' => 0,
            'type_mouvement' => 'credit',
            'user_id' => $destinataire['id'],
            'destinataire_numero' => $emetteur['telephone'],
            'type_operation_id' => self::TYPE_TRANSFERT,
            'frais_retrait_inclus' => 0,
        ]);

        $db->transComplete();

        $emetteur['solde'] = $this->soldeUser($emetteur['id']);

        return $emetteur;
    }

    /**
     * Un opérateur est "autre" (non principal) quand principale = 0.
     */
    private function estAutreOperateur(int $operateurId): bool
    {
        $operateur = $this->operateurModel->find($operateurId);

        if (!$operateur) {
            throw new \RuntimeException("Opérateur introuvable.");
        }

        return (int) $operateur['principale'] === 0;
    }

    public function historique(int $userId): array
    {
        return $this->historiqueModel
            ->where('user_id', $userId)
            ->orderBy('date', 'DESC')
            ->findAll();
    }

    public function historiqueDetaille(int $userId): array
    {
        $lignes = $this->historiqueModel
            ->select('historique_transaction.*, type_operation.libelle as type_operation_libelle')
            ->join('type_operation', 'type_operation.id = historique_transaction.type_operation_id')
            ->where('historique_transaction.user_id', $userId)
            ->orderBy('historique_transaction.date', 'DESC')
            ->findAll();

        foreach ($lignes as &$ligne) {
            $ligne['contrepartie_telephone'] = $ligne['destinataire_numero'] ?? null;
        }

        return $lignes;
    }
}
