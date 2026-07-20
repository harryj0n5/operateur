<?php

namespace App\Services;

use App\Models\ConfigurationModel;
use App\Models\OperateurModel;

class ConfigurationService
{
    protected ConfigurationModel $configurationModel;
    protected OperateurModel $operateurModel;

    public function __construct()
    {
        $this->configurationModel = new ConfigurationModel();
        $this->operateurModel = new OperateurModel();
    }

    public function getAll(): array
    {
        return $this->configurationModel
            ->select('configuration.*, operateur.libelle as operateur_libelle, operateur.principale as is_principale')
            ->join('operateur', 'operateur.id = configuration.operateur_id')
            ->findAll();
    }

    public function getById(int $id): array|null
    {
        return $this->configurationModel->find($id);
    }

    public function getOperateurs(): array
    {
        return $this->operateurModel->findAll();
    }

    private function verifierOperateur(int $operateurId): void
    {
        if (!$this->operateurModel->find($operateurId)) {
            throw new \RuntimeException("L'opérateur sélectionné est invalide.");
        }
    }

    public function create(array $data): array
    {
        $data['prefix'] = trim((string)($data['prefix'] ?? ''));

        if (!empty($data['operateur_id'])) {
            $this->verifierOperateur((int)$data['operateur_id']);
        }

        $id = $this->configurationModel->insert($data);

        if (!$id) {
            throw new \RuntimeException(
                implode(", ", $this->configurationModel->errors() ?: ["Échec de la création."])
            );
        }

        return $this->getById($id);
    }

    public function update(int $id, array $data): array
    {
        $configuration = $this->getById($id);

        if (!$configuration) {
            throw new \RuntimeException("Configuration introuvable.");
        }

        $data['prefix'] = trim((string)($data['prefix'] ?? ''));

        if (!empty($data['operateur_id'])) {
            $this->verifierOperateur((int)$data['operateur_id']);
        }

        $updated = $this->configurationModel->update($id, $data);

        if (!$updated) {
            throw new \RuntimeException(
                implode(", ", $this->configurationModel->errors() ?: ["Échec de la mise à jour."])
            );
        }

        return $this->getById($id);
    }

    public function delete(int $id): bool
    {
        $configuration = $this->getById($id);

        if (!$configuration) {
            throw new \RuntimeException("Configuration introuvable.");
        }

        return $this->configurationModel->delete($id);
    }
}
