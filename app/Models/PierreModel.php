<?php

namespace App\Models;

use CodeIgniter\Model;

class PierreModel extends Model
{
    protected $table = 'pierre';
    protected $primaryKey = 'libPierre';

    protected $allowedFields = ['libPierre', 'descriptionPierre'];

    protected $useTimestamps = false;
    protected $returnType = 'array';
}
