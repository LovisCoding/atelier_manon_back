<?php

namespace App\Models;

use CodeIgniter\Model;

class FilModel extends Model
{
    protected $table = 'fil';
    protected $primaryKey = 'libcouleur';

    protected $allowedFields = ['libcouleur'];

    protected $useTimestamps = false;
    protected $returnType = 'array';

    public function getFil($libCouleur) {
        return $this->where("libcouleur", $libCouleur)->first();
    }
}
