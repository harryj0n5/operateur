<?php

namespace App\Models;

use CodeIgniter\Model;

class TypeUserModel extends Model
{
    protected $table = 'type_user';
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
            'required' => 'Le libellé est obligatoire.',
            'max_length' => 'Le libellé est trop long.'
        ]
    ];
}