<?php

namespace App\Services;

use App\Models\CoffreEpargneModel;

class OperateurService
{
    protected CoffreEpargneModel $coffreEpargneModel;

    public function __construct()
    {
        $this->coffreEpargneModel = new CoffreEpargneModel();
    }

    public function getAll(): array
    {
        return $this->coffreEpargneModel->findAll();
    }

    public function getById(int $id): array|null
    {
        return $this->coffreEpargneModel->find($id);
    }

    

    public function create(array $data): array
    {
        $data['telephone'] = trim((string)($data['telephone'] ?? ''));
        $data['solde'] = !empty($data['solde']) ? 1 : 0;
        $data['pourcentage'] = $data['pourcentage'] ?? 0;

       

        $id = $this->coffreEpargneModel->insert($data);

        if (!$id) {
            throw new \RuntimeException(
                implode(", ", $this->coffreEpargneModel->errors() ?: ["Échec de la création."])
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

        $data['libelle'] = trim((string)($data['libelle'] ?? ''));
        $data['principale'] = !empty($data['principale']) ? 1 : 0;
        $data['pourcentage_frais'] = $data['pourcentage_frais'] ?? 0;

        if ($data['principale']) {
            $this->retirerStatutPrincipalDesAutres($id);
        }

        $updated = $this->coffreEpargneModel->update($id, $data);

        if (!$updated) {
            throw new \RuntimeException(
                implode(", ", $this->coffreEpargneModel->errors() ?: ["Échec de la mise à jour."])
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

        return $this->coffreEpargneModel->delete($id);
    }
}