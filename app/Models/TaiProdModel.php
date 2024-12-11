<?php

namespace App\Models;

use CodeIgniter\Model;

class TaiProdModel extends Model
{
    protected $table = 'TaiProd';

    protected $primaryKey = ['idProd', 'libTaille'];
    protected $useAutoIncrement = false;

    protected $allowedFields = ['idProd', 'libTaille'];

    protected $useTimestamps = false;
    protected $returnType = 'array';

    public function existsProdAndTaille($idProd, $libTaille)
    {
        $produitModel = new ProduitModel();
        $existProd = $produitModel->getProduit($idProd);

        $tailleModel = new TailleModel();
        $existTaille = $tailleModel->getTaille($libTaille);


        if (!$existProd || !$existTaille) {
            return false;
        }

        return true;
    }


    public function addTaiProd($idProd, $libTaille)
    {
        $taiProd = $this->where("idProd", $idProd)
            ->where("libTaille", $libTaille)
            ->first();

        if ($this->existsProdAndTaille($idProd, $libTaille) && !$taiProd) {

            $this->db->table($this->table)->insert([
                "idProd" => $idProd,
                "libTaille" => $libTaille
            ]);

            return true;
        }

        return false;
    }

    public function addTaisProd($idProd, $tabTailles)
    {
        $this->where("idProd", $idProd)->delete();

        foreach($tabTailles as $libTaille) {
            $response = $this->addTaiProd($idProd, $libTaille);

            if (!$response) 
                return false;
        }

        return true;
    }



    public function deleteTaiProd($idProd, $libTaille)
    {
        $taiProd = $this->where("idProd", $idProd)
            ->where("libTaille", $libTaille)
            ->first();

        if ($this->existsProdAndTaille($idProd, $libTaille) && $taiProd) {
            $this->where("idProd", $idProd)
                ->where("libTaille", $libTaille)
                ->delete();

            return true;
        }

        return false;
    }


    public function getTaillesProduit($idProd)
    {
        $result = $this->where("idProd", $idProd)
                       ->select("libTaille")
                       ->get()
                       ->getResultArray();
    
        return array_column($result, 'libTaille');
    }
}
