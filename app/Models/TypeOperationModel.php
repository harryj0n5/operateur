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
        'libelle' => 'required|max_length[50]'
    ];

    protected $validationMessages = [
        'libelle' => [
            'required' => "Le libellé est obligatoire."
        ]
    ];
}