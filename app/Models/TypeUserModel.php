<?php

namespace App\Models;

use CodeIgniter\Model;

class TypeUserModel extends Model
{
    protected $table = 'type_user';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'libelle'
    ];

    protected $useTimestamps = false;
}