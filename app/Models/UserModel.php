<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'user';
    protected $primaryKey = 'id';

    protected $returnType = 'array';

    protected $allowedFields = [
        'telephone',
        'solde',
        'type_user_id'
    ];

    protected $validationRules = [
        'telephone' => 'required|max_length[15]',
        'solde' => 'required|numeric',
        'type_user_id' => 'required|integer'
    ];

    protected $validationMessages = [
        'telephone' => [
            'required' => 'Le téléphone est obligatoire.',
        ],

        'solde' => [
            'required' => 'Le solde est obligatoire.',
            'numeric' => 'Le solde doit être numérique.'
        ],

        'type_user_id' => [
            'required' => 'Le type utilisateur est obligatoire.',
            'integer' => 'Le type utilisateur est invalide.'
        ]
    ];
}