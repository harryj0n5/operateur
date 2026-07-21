<?php

namespace App\Services;

use App\Models\PromotionModel;
use App\Models\UserModel;
use App\Models\HistoriqueTransactionModel;
use App\Models\FraisOperationModel;
use App\Models\ConfigurationModel;
use App\Models\CoffreEpargneModel;

class TransactionService
{
    private const TYPE_DEPOT = 1;
    private const TYPE_RETRAIT = 2;
    private const TYPE_TRANSFERT = 3;
    private const TYPE_EPARGNE = 4;

    protected UserModel $userModel;
    protected HistoriqueTransactionModel $historiqueModel;
    protected FraisOperationModel $fraisModel;
    protected ConfigurationModel $configurationModel;
    protected PromotionModel $promotionModel;
    protected CoffreEpargneModel $coffreEpargneModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->historiqueModel = new HistoriqueTransactionModel();
        $this->fraisModel = new FraisOperationModel();
        $this->configurationModel = new ConfigurationModel();
        $this->promotionModel = new PromotionModel();
        $this->coffreEpargneModel = new CoffreEpargneModel();
    }

    public function soldeClient(int $clientId): float|int
    {
        $user = $this->userModel->find($clientId);

        if (!$user) {
            throw new \RuntimeException("Client introuvable.");
        }

        $transactions = $this->historiqueModel
            ->where('numero', $user['telephone'])
            ->findAll();

        $solde = 0;

        foreach ($transactions as $transaction) {
            if ($transaction['type_mouvement'] === 'credit') {
                $solde += (float) $transaction['montant'];
            } elseif ($transaction['type_mouvement'] === 'debit') {
                $solde -= (float) $transaction['montant']
                    + (float) $transaction['frais']
                    + (float) $transaction['frais_operateur2'];
            }
        }

        return $solde;
    }

    private function getOperateurParTelephone(string $telephone): ?array
    {
        $prefixes = $this->configurationModel
            ->findAll();

        foreach ($prefixes as $prefix) {
            if (str_starts_with($telephone, $prefix['prefix'])) {

                return db_connect()
                    ->table('operateur')
                    ->where('id', $prefix['operateur_id'])
                    ->get()
                    ->getRowArray();
            }
        }

        return null;
    }

    private function calculerFraisOperateur2(
        float $frais,
        ?array $operateur
    ): float {

        if (!$operateur) {
            return 0;
        }

        if ($operateur['principale']) {
            return 0;
        }

        return $frais * ((float) $operateur['pourcentage_frais'] / 100);
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

    private function prefixeValide(string $telephone): bool
    {
        $prefixes = $this->configurationModel->select('prefix')->findAll();

        foreach ($prefixes as $row) {
            if (str_starts_with($telephone, $row['prefix'])) {
                return true;
            }
        }

        return false;
    }

    private function verifierMemeOperateur(array $telephones): array
    {
        $premierOperateur = null;

        foreach ($telephones as $telephone) {
            if (!$this->prefixeValide($telephone)) {
                throw new \RuntimeException(
                    "Le numéro {$telephone} n'est pas pris en charge par un opérateur configuré."
                );
            }

            $operateur = $this->getOperateurParTelephone($telephone);

            if (!$operateur) {
                throw new \RuntimeException(
                    "Impossible de déterminer l'opérateur du numéro {$telephone}."
                );
            }

            if ($premierOperateur === null) {
                $premierOperateur = $operateur;
            } elseif ((int) $operateur['id'] !== (int) $premierOperateur['id']) {
                throw new \RuntimeException(
                    "Tous les numéros d'un envoi multiple doivent appartenir au même opérateur."
                );
            }
        }

        return $premierOperateur;
    }

    private function memeOperateur(string $telephone_emeteur, ?array $telephones): array
    {
        $telephones[] = $telephone_emeteur;
        return $this->verifierMemeOperateur($telephones);
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
            'numero' => $user['telephone'],
            'destinataire_numero' => null,
            'type_operation_id' => self::TYPE_DEPOT,
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            throw new \RuntimeException("Échec de l'enregistrement du dépôt.");
        }

        return $this->userModel->find($userId);
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

        $frais = $this->calculerFrais($montant, self::TYPE_RETRAIT);
        $totalDebit = $montant + $frais;

        if ($this->soldeClient($userId) < $totalDebit) {
            throw new \RuntimeException("Solde insuffisant.");
        }

        $db = db_connect();
        $db->transStart();

        $this->historiqueModel->insert([
            'montant' => $montant,
            'frais' => $frais,
            'frais_operateur2' => 0,
            'type_mouvement' => 'debit',
            'numero' => $user['telephone'],
            'destinataire_numero' => null,
            'type_operation_id' => self::TYPE_RETRAIT,
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            throw new \RuntimeException("Échec de l'enregistrement du retrait.");
        }

        return $this->userModel->find($userId);
    }

    public function transfert(int $userId, string $telephoneDestinataire, float $montant): array
    {

        if ($montant <= 0) {
            throw new \RuntimeException("Le montant doit être positif.");
        }

        $emetteur = $this->userModel->find($userId);
        if (!$emetteur) {
            throw new \RuntimeException("Utilisateur introuvable.");
        }

        if ($telephoneDestinataire === $emetteur['telephone']) {
            throw new \RuntimeException("Vous ne pouvez pas transférer à vous-même.");
        }

        if (!$this->prefixeValide($telephoneDestinataire)) {
            throw new \RuntimeException("Ce préfixe n'est pas pris en charge par l'opérateur.");
        }

        $frais = $this->calculerFrais(
            $montant,
            self::TYPE_TRANSFERT
        );

        $operateurDestinataire =
            $this->getOperateurParTelephone($telephoneDestinataire);

        $frais_operateur2 =
            $this->calculerFraisOperateur2(
                $frais,
                $operateurDestinataire
            );

        $totalDebit = $montant + $frais + $frais_operateur2;

        if ($this->soldeClient($userId) < $totalDebit) {
            throw new \RuntimeException("Solde insuffisant.");
        }

        $db = db_connect();
        $db->transStart();

        $this->historiqueModel->insert([
            'montant' => $montant,
            'frais' => $frais,
            'frais_operateur2' => $frais_operateur2,
            'type_mouvement' => 'debit',
            'numero' => $emetteur['telephone'],
            'destinataire_numero' => $telephoneDestinataire,
            'type_operation_id' => self::TYPE_TRANSFERT,
        ]);

        $this->historiqueModel->insert([
            'montant' => $montant,
            'frais' => 0,
            'frais_operateur2' => 0,
            'type_mouvement' => 'credit',
            'numero' => $telephoneDestinataire,
            'destinataire_numero' => $emetteur['telephone'],
            'type_operation_id' => self::TYPE_TRANSFERT,
        ]);


        $db->transComplete();

        if ($db->transStatus() === false) {
            throw new \RuntimeException("Échec de l'enregistrement du transfert.");
        }

        return $this->userModel->find($emetteur['id']);
    }

    public function getPourcentageEpargne(string $telephone): float|int
    {
        $pourcentage = $this->coffreEpargneModel
            ->where('telephone', $telephone)
            ->orderBy('id', 'DESC')
            ->first();

        return $pourcentage['pourcentage'] ?? 0;

    }
    public function getCoffre(string $telephone): array
    {
        $pourcentage = $this->coffreEpargneModel
            ->where('telephone', $telephone)
            ->orderBy('id', 'DESC')
            ->first();
        if ($pourcentage === null) {
            $pourcentage = $this->coffreEpargneModel->insert([
                'telephone' => $telephone,
                'solde' => 0,
                'pourcentage' => 0
            ]);
        }

        return $pourcentage ?? [];

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
        $client = $this->userModel->find($userId);

        $lignes = $this->historiqueModel
            ->select('historique_transaction.*, type_operation.libelle as type_operation_libelle')
            ->join('type_operation', 'type_operation.id = historique_transaction.type_operation_id')
            ->where('historique_transaction.numero', $client['telephone'])
            ->orderBy('historique_transaction.date', 'DESC')
            ->findAll();

        foreach ($lignes as &$ligne) {
            $ligne['contrepartie_telephone'] = $ligne['destinataire_numero'] ?? null;
        }

        return $lignes;
    }

    private function getPromotion(?array $operateur): float
    {
        $promotion = $this->promotionModel
            ->where('operateur_id', $operateur['id'])
            ->orderBy('created_at', 'DESC')
            ->first();

        return $promotion['pourcentage'] ?? 0;
    }

    public function transfertMultiple(
        int $userId,
        array $telephones,
        float $montant,
        bool $inclureFraisRetrait
    ): array {
        if ($montant <= 0) {
            throw new \RuntimeException("Le montant doit être positif.");
        }

        $telephones = array_values(array_unique(array_filter(
            $telephones,
            static fn($tel) => trim((string) $tel) !== ''
        )));

        $nombre = count($telephones);

        if ($nombre <= 0) {
            throw new \RuntimeException("Aucun destinataire.");
        }

        $emetteur = $this->userModel->find($userId);
        if (!$emetteur) {
            throw new \RuntimeException("Utilisateur introuvable.");
        }

        if (in_array($emetteur['telephone'], $telephones, true)) {
            throw new \RuntimeException("Vous ne pouvez pas transférer à vous-même.");
        }

        $operateurDestinataire = $this->verifierMemeOperateur($telephones);

        if ($inclureFraisRetrait && empty($operateurDestinataire['principale'])) {
            throw new \RuntimeException(
                "L'inclusion des frais de retrait n'est pas disponible pour les autres opérateurs."
            );
        }

        $montantParPersonne = $montant / $nombre;

        $fraisTransfert =
            $this->calculerFrais(
                $montantParPersonne,
                self::TYPE_TRANSFERT
            ) * $nombre;

        if ($this->memeOperateur($emetteur['telephone'], $telephones)) {
            $promotion = $this->getPromotion($operateurDestinataire);
            $fraisTransfert -= $fraisTransfert * $promotion / 100;
        }

        $fraisOperateur2 = $this->calculerFraisOperateur2($fraisTransfert, $operateurDestinataire);

        $fraisRetrait = 0;

        if ($inclureFraisRetrait) {
            $fraisRetrait =
                $this->calculerFrais(
                    $montantParPersonne,
                    self::TYPE_RETRAIT
                ) * $nombre;
        }

        $fraisTotal = $fraisTransfert + $fraisOperateur2 + $fraisRetrait;

        $totalDebit = $montant + $fraisTotal;

        if ($this->soldeClient($userId) < $totalDebit) {
            throw new \RuntimeException("Solde insuffisant.");
        }

        $db = db_connect();
        $db->transStart();

        $fraisRetraitParPersonne = $inclureFraisRetrait ? ($fraisRetrait / $nombre) : 0;

        foreach ($telephones as $telephone) {
            $pourcentage = $this->getPourcentageEpargne($telephone);


            $this->historiqueModel->insert([
                'montant' => $montantParPersonne + $fraisRetraitParPersonne,
                'frais' => $fraisTransfert / $nombre,
                'frais_operateur2' => $fraisOperateur2 / $nombre,
                'type_mouvement' => 'debit',
                'numero' => $emetteur['telephone'],
                'destinataire_numero' => $telephone,
                'type_operation_id' => self::TYPE_TRANSFERT,
                'frais_retrait_inclus' => $inclureFraisRetrait
            ]);

            $this->historiqueModel->insert([
                'montant' => $montantParPersonne + $fraisRetraitParPersonne,
                'frais' => 0,
                'frais_operateur2' => 0,
                'type_mouvement' => 'credit',
                'numero' => $telephone,
                'destinataire_numero' => $emetteur['telephone'],
                'type_operation_id' => self::TYPE_TRANSFERT,
                'frais_retrait_inclus' => $inclureFraisRetrait
            ]);
            if ($pourcentage > 0) {
                $solde = $this->getCoffre($telephone)['solde'] + $montantParPersonne * $pourcentage / 100;
                $this->historiqueModel->insert([
                    'montant' => $montantParPersonne * $pourcentage / 100,
                    'frais' => 0,
                    'frais_operateur2' => 0,
                    'type_mouvement' => 'debit',
                    'numero' => $telephone,
                    'destinataire_numero' => null,
                    'type_operation_id' => self::TYPE_EPARGNE,
                    'frais_retrait_inclus' => 0
                ]);
                
                $id = $this->getCoffre($telephone)['id'];
                $this->coffreEpargneModel->update($id, ['solde' => $solde]);

            }
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            throw new \RuntimeException(
                "Erreur lors du transfert."
            );
        }

        return $this->userModel->find($userId);
    }
}