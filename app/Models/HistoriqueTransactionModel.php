<?php

namespace App\Models;

use CodeIgniter\Model;

class HistoriqueTransactionModel extends Model
{
    protected $table = 'historique_transaction';
    protected $primaryKey = 'id';

    protected $returnType = 'array';

    protected $allowedFields = [
        'montant',
        'frais',
        'frais_operateur2',
        'type_mouvement',
        'date',
        'user_id',
        'destinataire_numero',
        'type_operation_id',
        'frais_retrait_inclus'
    ];

    protected $validationRules = [
        'montant' => 'required|numeric',
        'frais' => 'required|numeric',
        'frais_operateur2' => 'required|numeric',
        'type_mouvement' => 'required|in_list[credit,debit]',
        'user_id' => 'required|integer',
        'type_operation_id' => 'required|integer'
    ];

    protected $validationMessages = [
        'montant' => [
            'required' => 'Le montant est obligatoire.',
            'numeric' => 'Le montant doit être numérique.'
        ],

        'frais' => [
            'required' => 'Le frais est obligatoire.',
            'numeric' => 'Le frais doit être numérique.'
        ],
        'frais_operateur2' => [
            'required' => 'Le montant est obligatoire.',
            'numeric' => 'Le montant doit être numérique.'
        ],

        'type_mouvement' => [
            'required' => 'Le type de mouvement est obligatoire.',
            'in_list' => 'Le mouvement doit être credit ou debit.'
        ],

        'user_id' => [
            'required' => "L'utilisateur est obligatoire.",
            'integer' => "L'utilisateur est invalide."
        ],

        'type_operation_id' => [
            'required' => "Le type d'opération est obligatoire.",
            'integer' => "Le type d'opération est invalide."
        ]
    ];
}