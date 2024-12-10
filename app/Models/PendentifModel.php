<?php

namespace App\Models;

use CodeIgniter\Model;

class PendentifModel extends Model
{
    protected $table = 'Pendentif';
    protected $primaryKey = 'libPendentif';
    protected $useAutoIncrement = false;

    protected $allowedFields = ['libPendentif'];

    protected $useTimestamps = false;
    protected $returnType = 'array';

    public function getPendentif($libPendentif) 
    {
        return $this->where("libPendentif", $libPendentif)->first();
    }

    public function getPendentifs() {
        return $this->findAll();
    }

    public function addPendentif($libPendentif) {
        $pendentif = $this->getPendentif($libPendentif);

        if (!$pendentif && $this->insert(["libPendentif" => $libPendentif])) {
            return true;
        }
        return false;
    }

    public function deletePendentif($libPendentif) {
        $pendentif = $this->getPendentif($libPendentif);

        if ($pendentif) {
            $this->delete($pendentif);
            return true;
        }

        return false;
    }
}
