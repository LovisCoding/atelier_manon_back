<?php

namespace App\Models;

use CodeIgniter\Model;

class PieProdModel extends Model
{
    protected $table = 'filprod';
    protected $primaryKey = null;

    protected $allowedFields = ['idProd', 'libCouleur'];

    protected $useTimestamps = false;
    protected $returnType = 'array';
}
