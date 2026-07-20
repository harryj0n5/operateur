<?php

namespace App\Services;

use App\Models\OperateurModel;

class OperateurService
{
    protected OperateurModel $operateurModel;

    public function __construct()
    {
        $this->operateurModel = new OperateurModel();
    }

    public function getAll(): array
    {
        return $this->operateurModel->findAll();
    }

    public function getById(int $id): array|null
    {
        return $this->operateurModel->find($id);
    }

    public function create(array $data): array
    {
        $data['libelle'] = trim((string) ($data['libelle'] ?? ''));
        $data['principale'] = !empty($data['principale']) ? 1 : 0;
        $data['pourcentage_frais'] = $data['pourcentage_frais'] ?? 0;

        $id = $this->operateurModel->insert($data);

        if (!$id) {
            throw new \RuntimeException(
                implode(", ", $this->operateurModel->errors() ?: ["Échec de la création."])
            );
        }

        return $this->getById($id);
    }

    public function update(int $id, array $data): array
    {
        $operateur = $this->getById($id);

        if (!$operateur) {
            throw new \RuntimeException("Opérateur introuvable.");
        }

        $data['libelle'] = trim((string) ($data['libelle'] ?? ''));
        $data['principale'] = !empty($data['principale']) ? 1 : 0;
        $data['pourcentage_frais'] = $data['pourcentage_frais'] ?? 0;

        $updated = $this->operateurModel->update($id, $data);

        if (!$updated) {
            throw new \RuntimeException(
                implode(", ", $this->operateurModel->errors() ?: ["Échec de la mise à jour."])
            );
        }

        return $this->getById($id);
    }

    public function delete(int $id): bool
    {
        $operateur = $this->getById($id);

        if (!$operateur) {
            throw new \RuntimeException("Opérateur introuvable.");
        }

        return $this->operateurModel->delete($id);
    }
}
