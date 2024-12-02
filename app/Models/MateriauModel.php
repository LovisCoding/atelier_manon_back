<?php

namespace App\Models;

use CodeIgniter\Model;

class MateriauModel extends Model
{
    protected $table = 'materiau';
    protected $primaryKey = 'libMateriau';

    protected $allowedFields = ['libMateriau'];

    protected $useTimestamps = false;
    protected $returnType = 'array';

    public function getMateriau($libMateriau) 
    {
        return $this->where("libmateriau", $libMateriau)->first();
    }
}
