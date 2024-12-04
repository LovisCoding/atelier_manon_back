<?php

namespace App\Models;

use CodeIgniter\Model;

class PierreModel extends Model
{
    protected $table = 'Pierre';
    protected $primaryKey = 'libPierre';
    protected $useAutoIncrement = false;

    protected $allowedFields = ['libPierre', 'descriptionPierre'];

    protected $useTimestamps = false;
    protected $returnType = 'array';

    public function getPierre($libPierre) {
        return $this->where("libPierre", $libPierre)->first();
    }

    public function getPierres() {
        return $this->findAll();
    }

    public function addPierre($libPierre, $descriptionPierre) {
        $pierre = $this->getPierre($libPierre);

        if (!$pierre && $this->insert(["libPierre" => $libPierre, "descriptionPierre" => $descriptionPierre])) {
            return "Pierre ajouté avec succès.";
        }
        return "Impossible d'ajouter cette pierre.";
    }

    public function deletePierre($libPierre) {
        $pierre = $this->getPierre($libPierre);

        if ($pierre) {
            $this->delete($pierre);
            return "Pierre supprimé avec succès.";
        }

        return "Impossible de supprimer cette pierre.";
    }


}
