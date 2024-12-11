<?php

namespace App\Models;

use CodeIgniter\Model;

class PenProdModel extends Model
{
    protected $table = 'PenProd';

    protected $primaryKey = ['idProd', 'libPendentif'];
    protected $useAutoIncrement = false;

    protected $allowedFields = ['idProd', 'libPendentif'];

    protected $useTimestamps = false;
    protected $returnType = 'array';

    public function existsProdAndPendentif($idProd, $libPendentif)
    {
        $produitModel = new ProduitModel();
        $existProd = $produitModel->getProduit($idProd);

        $pendentifModel = new PendentifModel();
        $existPendentif = $pendentifModel->getPendentif($libPendentif);

        if (!$existProd || !$existPendentif) {
            return false;
        }

        return true;
    }


    public function addPenProd($idProd, $libPendentif)
    {
        $taiProd = $this->where("idProd", $idProd)
            ->where("libPendentif", $libPendentif)
            ->first();

        if ($this->existsProdAndPendentif($idProd, $libPendentif) && !$taiProd) {

            $this->db->table($this->table)->insert([
                "idProd" => $idProd,
                "libPendentif" => $libPendentif
            ]);

            return true;
        }

        return false;
    }

    public function addPendProd($idProd, $tabPendentifs)
    {
        $this->where("idProd", $idProd)->delete();

        foreach($tabPendentifs as $libPendentif) {
            $response = $this->addPenProd($idProd, $libPendentif);

            if (!$response) 
                return false;
        }

        return true;
    }



    public function deletePendProd($idProd, $libPendentif)
    {
        $penProd = $this->where("idProd", $idProd)
            ->where("libPendentif", $libPendentif)
            ->first();

        if ($this->existsProdAndPendentif($idProd, $libPendentif) && $penProd) {
            $this->where("idProd", $idProd)
                ->where("libPendentif", $libPendentif)
                ->delete();

            return true;
        }

        return false;
    }


    public function getPendentifsProduit($idProd)
    {
        $result = $this->where("idProd", $idProd)
                       ->select("libPendentif")
                       ->get()
                       ->getResultArray();
    
        return array_column($result, 'libPendentif');
    }
}
