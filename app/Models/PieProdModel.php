<?php

namespace App\Models;

use CodeIgniter\Model;

class PieProdModel extends Model
{
    protected $table = 'pieprod';
    protected $primaryKey = null;

    protected $allowedFields = ['idProd', 'libPierre'];

    protected $useTimestamps = false;
    protected $returnType = 'array';
}
