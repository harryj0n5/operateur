<?php

namespace App\Models;

use CodeIgniter\Model;

class ConfigurationModel extends Model
{
    protected $table = 'configuration';
    protected $primaryKey = 'id';

    protected $returnType = 'array';

    protected $allowedFields = [
        'prefix',
        'operateur_id',
    ];

    protected $validationRules = [
        'prefix' => 'required|max_length[10]|is_unique[configuration.prefix,id,{id}]',
        'operateur_id' => 'required',
    ];

    protected $validationMessages = [
        'prefix' => [
            'required' => 'Le préfixe est obligatoire.',
            'is_unique' => 'Ce préfixe existe déjà.'
        ],
        'operateur_id' => [
            'required' => 'L\'operateur est obligatoire.',
        ]
    ];
}