<?php

namespace App\Services;

use App\Models\ConfigurationModel;
use App\Models\HistoriqueTransactionModel;
use App\Models\TypeUserModel;
use App\Models\UserModel;

class UserService
{
    private const TYPE_DEPOT = 1;
    private const TYPE_RETRAIT = 2;
    private const TYPE_TRANSFERT = 3;

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
            throw new \RuntimeException("Utilisateur introuvable.");
        }

        $data['telephone'] = trim((string)($data['telephone'] ?? ''));

        if ($data['telephone'] !== '' && $data['telephone'] !== $user['telephone']) {
            $existant = $this->userModel->where('telephone', $data['telephone'])->first();
            if ($existant && (int)$existant['id'] !== $id) {
                throw new \RuntimeException("Ce numéro existe déjà.");
            }

            if (!$this->prefixeValide($data['telephone'])) {
                throw new \RuntimeException("Ce préfixe n'est pas pris en charge par l'opérateur.");
            }
        }

        $ancienSolde = (float)$user['solde'];
        $nouveauSolde = isset($data['solde']) ? (float)$data['solde'] : $ancienSolde;

        $data['solde'] = $nouveauSolde;
        $data['type_user_id'] = $data['type_user_id'] ?? $user['type_user_id'];

        $db = db_connect();
        $db->transStart();

        $updated = $this->userModel->update($id, $data);

        if (!$updated) {
            throw new \RuntimeException(
                implode(", ", $this->userModel->errors() ?: ["Échec de la mise à jour."])
            );
        }

        if ($nouveauSolde !== $ancienSolde) {
            $ecart = $nouveauSolde - $ancienSolde;

            $this->historiqueTransactionModel->insert([
                'montant' => abs($ecart),
                'frais' => 0,
                'type_mouvement' => $ecart > 0 ? 'credit' : 'debit',
                'solde_apres' => $nouveauSolde,
                'user_id' => $id,
                'destinataire_id' => null,
                'type_operation_id' => $ecart > 0 ? self::TYPE_DEPOT : self::TYPE_RETRAIT,
            ]);
        }

        $db->transComplete();

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

        $existant = $this->userModel->where('telephone', $data['telephone'])->first();
        if ($existant) {
            throw new \RuntimeException("Ce numéro existe déjà.");
        }
        
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
