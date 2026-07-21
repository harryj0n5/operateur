<?php

namespace App\Models;

use CodeIgniter\Model;

class CoffreEpargneModel extends Model
{
    protected $table = 'coffre_eparge';
    protected $primaryKey = 'id';

    protected $returnType = 'array';

    protected $allowedFields = [
        'telephone',
        'pourcentage',
        'solde'
    ];


}