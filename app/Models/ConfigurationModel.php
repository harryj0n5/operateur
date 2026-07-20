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
}