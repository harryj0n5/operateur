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
}