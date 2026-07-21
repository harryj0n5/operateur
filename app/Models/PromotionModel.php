<?php

namespace App\Models;

use CodeIgniter\Model;

class PromotionModel extends Model
{
    protected $table = 'promotion';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'pourcentage',
        'created_at',
        'operateur_id'
    ];

    protected $validationRules = [
        'pourcentage' => 'required|decimal',
        'operateur_id' => 'required|integer'
    ];

    protected $validationMessages = [
        'pourcentage' => [
            'required' => 'Le pourcentage est obligatoire.'
        ],
        'operateur_id' => [
            'required' => 'L\'operateur est obligatoire.',
            'integer' => 'L\'operateur est invalide.'
        ]
    ];
}