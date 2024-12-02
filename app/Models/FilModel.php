<?php

namespace App\Models;

use CodeIgniter\Model;

class FilModel extends Model
{
    protected $table = 'fil';
    protected $primaryKey = 'libcouleur';
    protected $useAutoIncrement = false;

    protected $allowedFields = ['libcouleur'];

    protected $useTimestamps = false;
    protected $returnType = 'array';

    public function getFil($libCouleur) {
        return $this->where("libcouleur", $libCouleur)->first();
    }

    public function getFils() {
        return $this->findAll();
    }

    public function addFil($libCouleur) {
        $fil = $this->getFil($libCouleur);

        if (!$fil && $this->insert(["libcouleur" => $libCouleur])) {
            return "Fil ajouté avec succès.";
        }
        return "Impossible d'ajouter ce fil.";
    }

    public function deleteFil($libCouleur) {
        $fil = $this->getFil($libCouleur);

        if ($fil) {
            $this->delete($fil);
            return "Fil supprimé avec succès.";
        }

        return "Impossible de supprimer ce fil.";
    }
}
