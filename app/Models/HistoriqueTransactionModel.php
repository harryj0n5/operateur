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
        'type_mouvement',
        'solde_apres',
        'date',
        'user_id',
        'destinataire_id',
        'type_operation_id'
    ];

    protected $validationRules = [
        'montant' => 'required|numeric',
        'frais' => 'required|numeric',
        'type_mouvement' => 'required|in_list[credit,debit]',
        'solde_apres' => 'required|numeric',
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

        'type_mouvement' => [
            'required' => 'Le type de mouvement est obligatoire.',
            'in_list' => 'Le mouvement doit être credit ou debit.'
        ],

        'solde_apres' => [
            'required' => 'Le solde après opération est obligatoire.',
            'numeric' => 'Le solde doit être numérique.'
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