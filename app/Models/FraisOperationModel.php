<?php

namespace App\Models;

use CodeIgniter\Model;

class FraisOperationModel extends Model
{
    protected $table = 'frais_operation';
    protected $primaryKey = 'id';

    protected $returnType = 'array';

    protected $allowedFields = [
        'montant_min',
        'montant_max',
        'frais',
        'type_operation_id'
    ];

    protected $validationRules = [
        'montant_min' => 'required|numeric',
        'montant_max' => 'required|numeric',
        'frais' => 'required|numeric',
        'type_operation_id' => 'required|integer'
    ];

    protected $validationMessages = [
        'montant_min' => [
            'required' => 'Le montant minimum est obligatoire.',
            'numeric' => 'Le montant minimum doit être un nombre.'
        ],

        'montant_max' => [
            'required' => 'Le montant maximum est obligatoire.',
            'numeric' => 'Le montant maximum doit être un nombre.'
        ],

        'frais' => [
            'required' => 'Le frais est obligatoire.',
            'numeric' => 'Le frais doit être un nombre.'
        ],

        'type_operation_id' => [
            'required' => "Le type d'opération est obligatoire.",
            'integer' => "L'identifiant du type d'opération doit être un entier."
        ]
    ];
}