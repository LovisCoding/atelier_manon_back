<?php

namespace App\Models;

use CodeIgniter\Model;

class PieProdModel extends Model
{
    protected $table = 'matprod';
    protected $primaryKey = null;

    protected $allowedFields = ['idProd', 'libMateriel'];

    protected $useTimestamps = false;
    protected $returnType = 'array';
}
