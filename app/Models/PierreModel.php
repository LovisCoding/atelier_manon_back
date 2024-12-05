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
            return true;
        }
        return false;
    }

    public function deletePierre($libPierre) {
        $pierre = $this->getPierre($libPierre);

        if ($pierre) {
            $this->delete($pierre);
            return true;
        }

        return false;
    }


}
