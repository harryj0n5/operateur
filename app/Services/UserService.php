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
        int $id,
        array $data
    ): array {

        $user = $this->getUserById($id);


        if (!$user) {
            throw new \RuntimeException(
                "Utilisateur introuvable."
            );
        }

        $data['telephone'] = trim((string) ($data['telephone'] ?? ''));

        if ($data['telephone'] === '') {
            throw new \RuntimeException(
                "Le numéro de téléphone est obligatoire."
            );
        }

        // Vérifie le préfixe uniquement si le numéro a changé
        if ($data['telephone'] !== $user['telephone'] && !$this->prefixeValide($data['telephone'])) {
            throw new \RuntimeException(
                "Ce préfixe n'est pas pris en charge par l'opérateur."
            );
        }

        // Vérifie qu'un AUTRE utilisateur n'utilise pas déjà ce numéro
        $userExiste = $this->userModel
            ->where('telephone', $data['telephone'])
            ->where('id !=', $id)
            ->first();

        if ($userExiste) {
            throw new \RuntimeException(
                "Ce numéro existe déjà."
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

    public function creerUser(array $data): array
    {
        $data['telephone'] = trim((string) ($data['telephone'] ?? ''));

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


        // Vérifier si le téléphone existe déjà
        $userExiste = $this->userModel
            ->where('telephone', $data['telephone'])
            ->first();


        if ($userExiste) {
            throw new \RuntimeException(
                "Ce numéro existe déjà."
            );
        }


        // Valeurs par défaut
        $data['solde'] = $data['solde'] ?? 0;
        $data['type_user_id'] = $data['type_user_id'] ?? 2;


        // Insertion
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


}
