<?php

namespace App\Services;

use App\Models\FraisOperationModel;
use App\Models\TypeOperationModel;

class FraisOperationService
{
    protected FraisOperationModel $fraisOperationModel;
    protected TypeOperationModel $typeOperationModel;

    public function __construct()
    {
        $this->fraisOperationModel = new FraisOperationModel();
        $this->typeOperationModel = new TypeOperationModel();
    }

    public function getAll(): array
    {
        return $this->fraisOperationModel
            ->select('frais_operation.*, type_operation.libelle as type_operation_libelle')
            ->join('type_operation', 'type_operation.id = frais_operation.type_operation_id')
            ->findAll();
    }

    public function getById(int $id): array|null
    {
        return $this->fraisOperationModel->find($id);
    }

    public function getTypeOperations(): array
    {
        return $this->typeOperationModel->findAll();
    }

    private function verifierTypeOperation(int $typeOperationId): void
    {
        if (!$this->typeOperationModel->find($typeOperationId)) {
            throw new \RuntimeException("Le type d'opération sélectionné est invalide.");
        }
    }

    private function verifierMontants(array $data): void
    {
        if (is_numeric($data['montant_min'] ?? null) && is_numeric($data['montant_max'] ?? null)
            && (float) $data['montant_min'] > (float) $data['montant_max']
        ) {
            throw new \RuntimeException("Le montant minimum ne peut pas être supérieur au montant maximum.");
        }
    }

    public function create(array $data): array
    {
        if (!empty($data['type_operation_id'])) {
            $this->verifierTypeOperation((int) $data['type_operation_id']);
        }

        $this->verifierMontants($data);

        $id = $this->fraisOperationModel->insert($data);

        if (!$id) {
            throw new \RuntimeException(
                implode(", ", $this->fraisOperationModel->errors() ?: ["Échec de la création."])
            );
        }

        return $this->getById($id);
    }

    public function update(int $id, array $data): array
    {
        $fraisOperation = $this->getById($id);

        if (!$fraisOperation) {
            throw new \RuntimeException("Frais d'opération introuvable.");
        }

        if (!empty($data['type_operation_id'])) {
            $this->verifierTypeOperation((int) $data['type_operation_id']);
        }

        $this->verifierMontants($data);

        $updated = $this->fraisOperationModel->update($id, $data);

        if (!$updated) {
            throw new \RuntimeException(
                implode(", ", $this->fraisOperationModel->errors() ?: ["Échec de la mise à jour."])
            );
        }

        return $this->getById($id);
    }

    public function delete(int $id): bool
    {
        $fraisOperation = $this->getById($id);

        if (!$fraisOperation) {
            throw new \RuntimeException("Frais d'opération introuvable.");
        }

        return $this->fraisOperationModel->delete($id);
    }
}
