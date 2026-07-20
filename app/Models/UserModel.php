<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'user';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'telephone',
        'idtypeUser'
    ];

    protected $useTimestamps = false;

    protected $validationRules = [
        'telephone' => 'required|min_length[10]|max_length[15]|is_unique[user.telephone]',
        'idtypeUser' => 'required|integer'
    ];

    protected $validationMessages = [
        'telephone' => [
            'required' => 'Le téléphone est obligatoire.',
            'min_length' => 'Le téléphone doit contenir au moins 10 caractères.',
            'max_length' => 'Le téléphone ne doit pas dépasser 15 caractères.',
            'is_unique' => 'Ce téléphone existe déjà.'
        ],

        'idtypeUser' => [
            'required' => 'Le type utilisateur est obligatoire.',
            'integer' => 'Le type utilisateur doit être un nombre.'
        ]
    ];
}