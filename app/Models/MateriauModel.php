<?php

namespace App\Models;

use CodeIgniter\Model;

class MateriauModel extends Model
{
    protected $table = 'Materiau';
    protected $primaryKey = 'libMateriau';
    protected $useAutoIncrement = false;

    protected $allowedFields = ['libMateriau'];

    protected $useTimestamps = false;
    protected $returnType = 'array';

    public function getMateriau($libMateriau) 
    {
        return $this->where("libMateriau", $libMateriau)->first();
    }

    public function getMateriaux() {
        return $this->findAll();
    }

    public function addMateriau($libMateriau) {
        $materiau = $this->getMateriau($libMateriau);

        if (!$materiau && $this->insert(["libMateriau" => $libMateriau])) {
            return true;
        }
        return false;
    }

    public function deleteMateriau($libMateriau) {
        $materiau = $this->getMateriau($libMateriau);

        if ($materiau) {
            $this->delete($materiau);
            return true;
        }

        return false;
    }
}
