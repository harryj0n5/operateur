<?php

namespace App\Models;

use CodeIgniter\Model;

class TypeOperationModel extends Model
{
    protected $table = 'type_operation';
    protected $primaryKey = 'id';

    protected $returnType = 'array';

    protected $allowedFields = [
        'libelle'
    ];

    protected $validationRules = [
        'libelle' => 'required|min_length[3]|max_length[50]'
    ];

    protected $validationMessages = [
        'libelle' => [
            'required' => 'Le libellé est obligatoire.',
            'min_length' => 'Le libellé doit contenir au moins 3 caractères.',
            'max_length' => 'Le libellé ne doit pas dépasser 50 caractères.'
        ]
    ];
}