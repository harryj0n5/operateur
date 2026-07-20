<?php

namespace App\Services;

use App\Models\UserModel;
use App\Models\HistoriqueTransactionModel;
use App\Models\FraisOperationModel;
use App\Models\ConfigurationModel;

class TransactionService
{
    private const TYPE_DEPOT = 1;
    private const TYPE_RETRAIT = 2;
    private const TYPE_TRANSFERT = 3;

    protected UserModel $userModel;
    protected HistoriqueTransactionModel $historiqueModel;
    protected FraisOperationModel $fraisModel;
    protected ConfigurationModel $configurationModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->historiqueModel = new HistoriqueTransactionModel();
        $this->fraisModel = new FraisOperationModel();
        $this->configurationModel = new ConfigurationModel();
    }

    public function soldeClient(int $clientId): float|int
    {
        $user = $this->userModel->find($clientId);

        if (!$user) {
            throw new \RuntimeException("Client introuvable.");
        }

        $transactions = $this->historiqueModel
            ->where('user_id', $clientId)
            ->findAll();

        $solde = 0;

        foreach ($transactions as $transaction) {
            if ($transaction['type_mouvement'] === 'credit') {
                $solde += (float)$transaction['montant'];
            } elseif ($transaction['type_mouvement'] === 'debit') {
                $solde -= (float)$transaction['montant'];
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
        float  $frais,
        ?array $operateur
    ): float
    {

        if (!$operateur) {
            return 0;
        }

        if ($operateur['principale']) {
            return 0;
        }

        return $frais * ((float)$operateur['pourcentage_frais'] / 100);
    }

    private function calculerFrais(float $montant, int $typeOperationId): float
    {
        $tranche = $this->fraisModel
            ->where('type_operation_id', $typeOperationId)
            ->where('montant_min <=', $montant)
            ->where('montant_max >=', $montant)
            ->first();

        return $tranche ? (float)$tranche['frais'] : 0;
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
            'type_mouvement' => 'debit',
            'user_id' => $userId,
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

        $destinataire = $this->userModel->where('telephone', $telephoneDestinataire)->first();

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


        $totalDebit = $montant + $frais;

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
            'user_id' => $emetteur['id'],
            'destinataire_numero' => $telephoneDestinataire,
            'type_operation_id' => self::TYPE_TRANSFERT,
        ]);

        if ($destinataire) {
            $this->historiqueModel->insert([
                'montant' => $montant,
                'frais' => 0,
                'frais_operateur2' => 0,
                'type_mouvement' => 'credit',
                'user_id' => $destinataire['id'],
                'destinataire_numero' => $emetteur['telephone'],
                'type_operation_id' => self::TYPE_TRANSFERT,
            ]);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            throw new \RuntimeException("Échec de l'enregistrement du transfert.");
        }

        return $this->userModel->find($emetteur['id']);
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

    public function transfertMultiple(
        int   $userId,
        array $telephones,
        float $montant,
        bool  $inclureFraisRetrait
    ): array
    {
        $nombre = count($telephones);

        if ($nombre <= 0) {
            throw new \RuntimeException("Aucun destinataire.");
        }


        $montantParPersonne = $montant / $nombre;


        $fraisTransfert =
            $this->calculerFrais(
                $montantParPersonne,
                self::TYPE_TRANSFERT
            ) * $nombre;


        $fraisRetrait = 0;


        if ($inclureFraisRetrait) {

            $fraisRetrait =
                $this->calculerFrais(
                    $montantParPersonne,
                    self::TYPE_RETRAIT
                ) * $nombre;
        }


        $fraisTotal = $fraisTransfert + $fraisRetrait;


        $totalDebit = $montant + $fraisTotal;


        if ($this->soldeClient($userId) < $totalDebit) {
            throw new \RuntimeException("Solde insuffisant.");
        }


        $db = db_connect();
        $db->transStart();


        foreach ($telephones as $telephone) {

            $this->historiqueModel->insert([
                'montant' => $montantParPersonne,
                'frais' => $fraisTransfert / $nombre,
                'frais_operateur2' => 0,
                'type_mouvement' => 'debit',
                'user_id' => $userId,
                'destinataire_numero' => $telephone,
                'type_operation_id' => self::TYPE_TRANSFERT,
                'frais_retrait_inclus' => $inclureFraisRetrait
            ]);

        }


        // Enregistrement du frais de retrait séparément
        if ($inclureFraisRetrait && $fraisRetrait > 0) {

            $this->historiqueModel->insert([
                'montant' => 0,
                'frais' => $fraisRetrait,
                'frais_operateur2' => 0,
                'type_mouvement' => 'debit',
                'user_id' => $userId,
                'destinataire_numero' => null,
                'type_operation_id' => self::TYPE_RETRAIT,
                'frais_retrait_inclus' => true
            ]);

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