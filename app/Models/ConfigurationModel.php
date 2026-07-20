<?php

namespace App\Models;

use CodeIgniter\Model;

class ConfigurationModel extends Model
{
    protected $table = 'configuration';
    protected $primaryKey = 'id';

    protected $returnType = 'array';

    protected $allowedFields = [
        'prefix'
    ];

    protected $validationRules = [
        'prefix' => 'required|min_length[1]|max_length[20]'
    ];

    protected $validationMessages = [
        'prefix' => [
            'required' => 'Le préfixe est obligatoire.',
            'min_length' => 'Le préfixe doit contenir au moins 1 caractère.',
            'max_length' => 'Le préfixe ne doit pas dépasser 20 caractères.'
        ]
    ];
}