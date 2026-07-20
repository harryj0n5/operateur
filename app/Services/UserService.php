<?php

namespace App\Services;

use App\Models\ConfigurationModel;
use App\Models\HistoriqueTransactionModel;
use App\Models\TypeUserModel;
use App\Models\UserModel;

class UserService
{
    protected UserModel $userModel;
    private ConfigurationModel $configurationModel;
    protected HistoriqueTransactionModel $historiqueTransactionModel;
    protected TypeUserModel $typeUserModel;
    private HistoriqueTransactionModel $historiqueModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->configurationModel = new ConfigurationModel();
        $this->historiqueTransactionModel = new HistoriqueTransactionModel();
        $this->typeUserModel = new TypeUserModel();
        $this->historiqueModel = new HistoriqueTransactionModel();
    }

    public function prefixeValide(string $telephone): bool
    {
        $prefixes = $this->configurationModel->select('prefix')->findAll();

        foreach ($prefixes as $row) {
            if (str_starts_with($telephone, $row['prefix'])) {
                return true;
            }
        }

        return false;
    }

    public function loginOuCreer(string $telephone): array
    {
        if (!$this->prefixeValide($telephone)) {
            throw new \RuntimeException("Ce préfixe n'est pas pris en charge par l'opérateur.");
        }

        $user = $this->userModel->where('telephone', $telephone)->first();

        if ($user) {
            return $user;
        }

        $id = $this->userModel->insert([
            'telephone' => $telephone,
            'solde' => 0,
            'type_user_id' => 2,
        ]);

        return $this->userModel->find($id);
    }

    public function getAllUsers(): array
    {
        return $this->userModel
            ->select('user.*, type_user.libelle as type_user_libelle')
            ->join('type_user', 'type_user.id = user.type_user_id')
            ->findAll();
    }

    public function getUserById(int $id): array|null
    {
        return $this->userModel
            ->find($id);
    }

    public function getTypeUsers(): array
    {
        return $this->typeUserModel->findAll();
    }

    public function deleteUser(int $id): bool
    {
        $user = $this->getUserById($id);

        if (!$user) {
            throw new \RuntimeException(
                "Utilisateur introuvable."
            );
        }

        return $this->userModel
            ->delete($id);
    }

    public function updateUser(int $id, array $data): array
    {
        $user = $this->getUserById($id);

        if (!$user) {
            throw new \RuntimeException(
                "Utilisateur introuvable."
            );
        }

        $data['telephone'] = trim((string)($data['telephone'] ?? ''));

        // Vérifie le préfixe uniquement si le numéro a changé
        if ($data['telephone'] !== '' && $data['telephone'] !== $user['telephone'] && !$this->prefixeValide($data['telephone'])) {
            throw new \RuntimeException(
                "Ce préfixe n'est pas pris en charge par l'opérateur."
            );
        }

        $data['solde'] = $data['solde'] ?? $user['solde'];
        $data['type_user_id'] = $data['type_user_id'] ?? $user['type_user_id'];

        $updated = $this->userModel
            ->update($id, $data);

        if (!$updated) {
            throw new \RuntimeException(
                implode(", ", $this->userModel->errors() ?: ["Échec de la mise à jour."])
            );
        }

        return $this->getUserById($id);
    }

    public function soldeClient(int $clientId)
    {
        $user = $this->getUserById($clientId);

        if (!$user) {
            throw new \RuntimeException(
                "Utilisateur introuvable."
            );
        }

        return $user['solde'];
    }

    public function creerUser(array $data): array
    {
        $data['telephone'] = trim((string)($data['telephone'] ?? ''));

        if ($data['telephone'] === '') {
            throw new \RuntimeException(
                "Le numéro de téléphone est obligatoire."
            );
        }

        // Vérification du préfixe opérateur
        if (!$this->prefixeValide($data['telephone'])) {
            throw new \RuntimeException(
                "Ce préfixe n'est pas pris en charge par l'opérateur."
            );
        }

        $data['solde'] = $data['solde'] ?? 0;
        $data['type_user_id'] = $data['type_user_id'] ?? 2;

        $id = $this->userModel->insert($data);

        if (!$id) {
            throw new \RuntimeException(
                implode(
                    ", ",
                    $this->userModel->errors() ?: ["Échec de la création."]
                )
            );
        }

        // Retourner l'utilisateur créé
        return $this->getUserById($id);
    }

    public function situationGain(string $date): array
    {
        $dateDebutJournee = $date . ' 00:00:00';
        $dateFinJournee = $date . ' 23:59:59';

        $lignes = $this->historiqueModel
            ->select('type_operation.libelle as type_operation_libelle, SUM(historique_transaction.frais) as total_gain, COUNT(historique_transaction.id) as nombre_transaction')
            ->join('type_operation', 'type_operation.id = historique_transaction.type_operation_id')
            ->where('historique_transaction.date >=', $dateDebutJournee)
            ->where('historique_transaction.date <=', $dateFinJournee)
            ->groupBy('historique_transaction.type_operation_id')
            ->findAll();

        $totalGeneral = 0;
        $nombreTotal = 0;

        foreach ($lignes as $ligne) {
            $totalGeneral += (float)$ligne['total_gain'];
            $nombreTotal += (int)$ligne['nombre_transaction'];
        }

        return [
            'date' => $date,
            'par_operation' => $lignes,
            'total_gain' => $totalGeneral,
            'nombre_transaction' => $nombreTotal,
        ];
    }

    public function situationGainClient(int $clientId, string $date): array
    {
        $client = $this->getUserById($clientId);

        if (!$client) {
            throw new \RuntimeException("Client introuvable.");
        }

        if ($client['type_user_id'] != 2) {
            throw new \RuntimeException("Cet utilisateur n'est pas un client.");
        }

        $dateFinJournee = $date . ' 23:59:59';

        $transaction = $this->historiqueModel
            ->select("
            COUNT(id) AS nombre_transaction,
            SUM(CASE WHEN type_mouvement = 'credit' THEN montant ELSE 0 END) AS total_credit,
            SUM(CASE WHEN type_mouvement = 'debit' THEN montant ELSE 0 END) AS total_debit,
            SUM(frais) AS total_frais
        ")
            ->where('user_id', $clientId)
            ->where('date <=', $dateFinJournee)
            ->first();

        return [
            'client_id' => $client['id'],
            'telephone' => $client['telephone'],
            'date_situation' => $date,
            'solde_actuel' => $client['solde'],
            'nombre_transaction' => $transaction['nombre_transaction'] ?? 0,
            'total_credit' => $transaction['total_credit'] ?? 0,
            'total_debit' => $transaction['total_debit'] ?? 0,
            'total_frais' => $transaction['total_frais'] ?? 0,
        ];
    }

    public function getClients(): array
    {
        return $this->userModel
            ->where('type_user_id', 2)
            ->orderBy('telephone', 'ASC')
            ->findAll();
    }

}
