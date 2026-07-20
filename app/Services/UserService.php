<?php

namespace App\Services;

use App\Models\ConfigurationModel;
use App\Models\HistoriqueTransactionModel;
use App\Models\UserModel;

class UserService
{
    protected UserModel $userModel;
    private ConfigurationModel $configurationModel;
    protected HistoriqueTransactionModel $historiqueTransactionModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->configurationModel = new ConfigurationModel();
        $this->historiqueTransactionModel = new HistoriqueTransactionModel();
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
        return $this->userModel->findAll();
    }

    public function getUserById(int $id): array|null
    {
        return $this->userModel
            ->find($id);
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

    public function updateUser(
        int   $id,
        array $data
    ): array
    {

        $user = $this->getUserById($id);


        if (!$user) {
            throw new \RuntimeException(
                "Utilisateur introuvable."
            );
        }


        $this->userModel
            ->update($id, $data);


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
}