<?php

namespace App\Services;

use App\Models\ConfigurationModel;
use App\Models\UserModel;

class UserService
{
    protected UserModel $userModel;
    private ConfigurationModel $configurationModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->configurationModel = new ConfigurationModel();
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
}