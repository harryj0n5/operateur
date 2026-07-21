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
        'type_user_id'
    ];

    protected $validationRules = [
        'telephone' => 'required|max_length[15]',
        'type_user_id' => 'required|integer'
    ];

    protected $validationMessages = [
        'telephone' => [
            'required' => 'Le téléphone est obligatoire.',
        ],

        'type_user_id' => [
            'required' => 'Le type utilisateur est obligatoire.',
            'integer' => 'Le type utilisateur est invalide.'
        ]
    ];
}