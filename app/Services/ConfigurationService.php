<?php

namespace App\Services;

use App\Models\ConfigurationModel;

class ConfigurationService
{
    protected ConfigurationModel $configurationModel;

    public function __construct()
    {
        $this->configurationModel = new ConfigurationModel();
    }

    public function getAll(): array
    {
        return $this->configurationModel->findAll();
    }

    public function getById(int $id): array|null
    {
        return $this->configurationModel->find($id);
    }

    public function create(array $data): array
    {
        $data['prefix'] = trim((string) ($data['prefix'] ?? ''));

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

        $data['prefix'] = trim((string) ($data['prefix'] ?? ''));

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
