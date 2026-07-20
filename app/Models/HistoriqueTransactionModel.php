<?php

namespace App\Models;

use CodeIgniter\Model;

class HistoriqueTransactionModel extends Model
{
    protected $table = 'historique_transaction';
    protected $primaryKey = 'id';

    protected $returnType = 'array';

    protected $allowedFields = [
        'frais',
        'date',
        'id_user',
        'type_operation_id'
    ];

    protected $validationRules = [
        'frais' => 'required|numeric',
        'date' => 'required',
        'id_user' => 'required|integer',
        'type_operation_id' => 'required|integer'
    ];

    protected $validationMessages = [
        'frais' => [
            'required' => 'Le frais est obligatoire.',
            'numeric' => 'Le frais doit être un nombre.'
        ],

        'date' => [
            'required' => 'La date est obligatoire.'
        ],

        'id_user' => [
            'required' => "L'utilisateur est obligatoire.",
            'integer' => "L'identifiant utilisateur doit être un entier."
        ],

        'type_operation_id' => [
            'required' => "Le type d'opération est obligatoire.",
            'integer' => "L'identifiant du type d'opération doit être un entier."
        ]
    ];
}