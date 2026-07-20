<?php

namespace App\Models;

use CodeIgniter\Model;

class OperateurModel extends Model
{
    protected $table = 'operateur';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'libelle',
        'principale',
        'pourcentage_frais'
    ];

    protected $validationRules = [
        'libelle' => 'required|max_length[255]',
        'principale' => 'required|in_list[0,1]',
        'pourcentage_frais' => 'required|decimal'
    ];

    protected $validationMessages = [
        'libelle' => [
            'required' => 'Le libelle du operateur est obligatoire.'
        ],
        'principale' => [
            'required' => 'Le principale du operateur est obligatoire.'
        ],
        'pourcentage_frais' => [
            'required' => 'Le pourcentage de frais du operateur est obligatoire.'
        ]
    ];
}