<?php

namespace App\Services;

use App\Models\OperateurModel;
use App\Models\PromotionModel;

class PromotionService
{
    protected PromotionModel $promotionModel;
    protected OperateurModel $operateurModel;

    public function __construct()
    {
        $this->promotionModel = new PromotionModel();
        $this->operateurModel = new OperateurModel();
    }

    public function getAll()
    {
        return $this->promotionModel
            ->select('promotion.*, operateur.libelle as operateur_libelle')
            ->join('operateur', 'operateur.id = promotion.operateur_id')
            ->findAll();
    }

    public function getById(int $id): array|null
    {
        return $this->promotionModel->find($id);
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
}