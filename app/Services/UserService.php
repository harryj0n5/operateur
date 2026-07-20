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

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->configurationModel = new ConfigurationModel();
        $this->historiqueTransactionModel = new HistoriqueTransactionModel();
        $this->typeUserModel = new TypeUserModel();
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

        $data['telephone'] = trim((string) ($data['telephone'] ?? ''));

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

        // Valeurs par défaut
        $data['solde'] = $data['solde'] ?? 0;
        $data['type_user_id'] = $data['type_user_id'] ?? 2;

        // Insertion (la vérification d'unicité du téléphone est gérée par le modèle)
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
