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
}