<?php

namespace App\Models;

use CodeIgniter\Model;

class TailleModel extends Model
{
    protected $table = 'Taille';
    protected $primaryKey = 'libTaille';
    protected $useAutoIncrement = false;

    protected $allowedFields = ['libTaille'];

    protected $useTimestamps = false;
    protected $returnType = 'array';

    public function getTaille($libTaille) 
    {
        return $this->where("libTaille", $libTaille)->first();
    }

    public function getTailles() {
        return $this->findAll();
    }

    public function addTaille($libTaille) {
        $taille = $this->getTaille($libTaille);

        if (!$taille && $this->insert(["libTaille" => $libTaille])) {
            return true;
        }
        return false;
    }

    public function deleteTaille($libTaille) {
        $taille = $this->getTaille($libTaille);

        if ($taille) {
            $this->delete($taille);
            return true;
        }

        return false;
    }
}
