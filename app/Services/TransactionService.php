<?php

namespace App\Services;

use App\Models\UserModel;
use App\Models\HistoriqueTransactionModel;
use App\Models\FraisOperationModel;

class TransactionService
{
    private const TYPE_DEPOT = 1;
    private const TYPE_RETRAIT = 2;
    private const TYPE_TRANSFERT = 3;

    protected UserModel $userModel;
    protected HistoriqueTransactionModel $historiqueModel;
    protected FraisOperationModel $fraisModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->historiqueModel = new HistoriqueTransactionModel();
        $this->fraisModel = new FraisOperationModel();
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

        $nouveauSolde = $user['solde'] + $montant;

        $this->userModel->update($userId, ['solde' => $nouveauSolde]);

        $this->historiqueModel->insert([
            'montant' => $montant,
            'frais' => 0,
            'type_mouvement' => 'credit',
            'solde_apres' => $nouveauSolde,
            'user_id' => $userId,
            'destinataire_id' => null,
            'type_operation_id' => self::TYPE_DEPOT,
        ]);

        $db->transComplete();

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

        if ($user['solde'] < $totalDebit) {
            throw new \RuntimeException("Solde insuffisant.");
        }

        $db = db_connect();
        $db->transStart();

        $nouveauSolde = $user['solde'] - $totalDebit;

        $this->userModel->update($userId, ['solde' => $nouveauSolde]);

        $this->historiqueModel->insert([
            'montant' => $montant,
            'frais' => $frais,
            'type_mouvement' => 'debit',
            'solde_apres' => $nouveauSolde,
            'user_id' => $userId,
            'destinataire_id' => null,
            'type_operation_id' => self::TYPE_RETRAIT,
        ]);

        $db->transComplete();

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

        $destinataire = $this->userModel->where('telephone', $telephoneDestinataire)->first();
        if (!$destinataire) {
            throw new \RuntimeException("Destinataire introuvable.");
        }

        if ($destinataire['id'] === $emetteur['id']) {
            throw new \RuntimeException("Vous ne pouvez pas transférer à vous-même.");
        }

        $frais = $this->calculerFrais($montant, self::TYPE_TRANSFERT);
        $totalDebit = $montant + $frais;

        if ($emetteur['solde'] < $totalDebit) {
            throw new \RuntimeException("Solde insuffisant.");
        }

        $db = db_connect();
        $db->transStart();

        $soldeEmetteurApres = $emetteur['solde'] - $totalDebit;
        $soldeDestinataireApres = $destinataire['solde'] + $montant;

        $this->userModel->update($emetteur['id'], ['solde' => $soldeEmetteurApres]);
        $this->userModel->update($destinataire['id'], ['solde' => $soldeDestinataireApres]);

        $this->historiqueModel->insert([
            'montant' => $montant,
            'frais' => $frais,
            'type_mouvement' => 'debit',
            'solde_apres' => $soldeEmetteurApres,
            'user_id' => $emetteur['id'],
            'destinataire_id' => $destinataire['id'],
            'type_operation_id' => self::TYPE_TRANSFERT,
        ]);

        $this->historiqueModel->insert([
            'montant' => $montant,
            'frais' => 0,
            'type_mouvement' => 'credit',
            'solde_apres' => $soldeDestinataireApres,
            'user_id' => $destinataire['id'],
            'destinataire_id' => $emetteur['id'],
            'type_operation_id' => self::TYPE_TRANSFERT,
        ]);

        $db->transComplete();

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
            $ligne['contrepartie_telephone'] = null;

            if (!empty($ligne['destinataire_id'])) {
                $contrepartie = $this->userModel->find($ligne['destinataire_id']);
                $ligne['contrepartie_telephone'] = $contrepartie['telephone'] ?? null;
            }
        }

        return $lignes;
    }
}