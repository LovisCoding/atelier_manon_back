<?php

namespace App\Models;

use CodeIgniter\Model;

class FilModel extends Model
{
    protected $table = 'fil';
    protected $primaryKey = 'libCouleur';

    protected $allowedFields = ['libCouleur'];

    protected $useTimestamps = false;
    protected $returnType = 'array';
}
