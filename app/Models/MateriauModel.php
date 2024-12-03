<?php

namespace App\Models;

use CodeIgniter\Model;

class MateriauModel extends Model
{
    protected $table = 'materiau';
    protected $primaryKey = 'libmateriau';
    protected $useAutoIncrement = false;

    protected $allowedFields = ['libmateriau'];

    protected $useTimestamps = false;
    protected $returnType = 'array';

    public function getMateriau($libMateriau) 
    {
        return $this->where("libmateriau", $libMateriau)->first();
    }

    public function getMateriaux() {
        return $this->findAll();
    }

    public function addMateriau($libMateriau) {
        $materiau = $this->getMateriau($libMateriau);

        if (!$materiau && $this->insert(["libmateriau" => $libMateriau])) {
            return "Matériau ajouté avec succès.";
        }
        return "Impossible d'ajouter ce matériau.";
    }

    public function deleteMateriau($libMateriau) {
        $materiau = $this->getMateriau($libMateriau);

        if ($materiau) {
            $this->delete($materiau);
            return "Matériau supprimé avec succès.";
        }

        return "Impossible de supprimer ce matériau.";
    }
}
