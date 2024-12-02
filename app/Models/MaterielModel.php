<?php

namespace App\Models;

use CodeIgniter\Model;

class MaterielModel extends Model
{
    protected $table = 'materiel';
    protected $primaryKey = 'libMateriel';

    protected $allowedFields = ['libMateriel'];

    protected $useTimestamps = false;
    protected $returnType = 'array';
}
