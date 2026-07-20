<?php

namespace App\Services;

use App\Models\TypeOperationModel;

class TypeOperationService
{
    protected TypeOperationModel $typeOperationModel;

    public function __construct()
    {
        $this->typeOperationModel = new TypeOperationModel();
    }

    public function getAll(): array
    {
        return $this->typeOperationModel->findAll();
    }

    public function getById(int $id): array|null
    {
        return $this->typeOperationModel->find($id);
    }

    public function create(array $data): array
    {
        $data['libelle'] = trim((string) ($data['libelle'] ?? ''));

        $id = $this->typeOperationModel->insert($data);

        if (!$id) {
            throw new \RuntimeException(
                implode(", ", $this->typeOperationModel->errors() ?: ["Échec de la création."])
            );
        }

        return $this->getById($id);
    }

    public function update(int $id, array $data): array
    {
        $typeOperation = $this->getById($id);

        if (!$typeOperation) {
            throw new \RuntimeException("Type d'opération introuvable.");
        }

        $data['libelle'] = trim((string) ($data['libelle'] ?? ''));

        $updated = $this->typeOperationModel->update($id, $data);

        if (!$updated) {
            throw new \RuntimeException(
                implode(", ", $this->typeOperationModel->errors() ?: ["Échec de la mise à jour."])
            );
        }

        return $this->getById($id);
    }

    public function delete(int $id): bool
    {
        $typeOperation = $this->getById($id);

        if (!$typeOperation) {
            throw new \RuntimeException("Type d'opération introuvable.");
        }

        return $this->typeOperationModel->delete($id);
    }
}
