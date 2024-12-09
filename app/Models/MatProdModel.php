<?php

namespace App\Models;

use CodeIgniter\Model;

class MatProdModel extends Model
{
    protected $table = 'MatProd';

    protected $primaryKey = ['idProd', 'libmateriau'];
    protected $useAutoIncrement = false;

    protected $allowedFields = ['idProd', 'libmateriau'];

    protected $useTimestamps = false;
    protected $returnType = 'array';

    public function existsProdAndMat($idProd, $libMat)
    {
        $produitModel = new ProduitModel();
        $existProd = $produitModel->getProduit($idProd);

        $materiauModel = new MateriauModel();
        $existMat = $materiauModel->getMateriau($libMat);



        if (!$existProd || !$existMat) {
            return false;
        }

        return true;
    }


    public function addMatProd($idProd, $libMat)
    {
        $matProd = $this->where("idProd", $idProd)
            ->where("libMateriau", $libMat)
            ->first();

        if ($this->existsProdAndMat($idProd, $libMat) && !$matProd) {

            $this->db->table($this->table)->insert([
                "idProd" => $idProd,
                "libMateriau" => $libMat
            ]);

            return true;
        }

        return false;
    }

    public function addMatsProd($idProd, $tabMateriaux)
    {
        $this->where("idProd", $idProd)->delete();

        foreach($tabMateriaux as $libMateriau) {
            $response = $this->addMatProd($idProd, $libMateriau);

            if (!$response) 
                return false;
        }

        return true;
    }



    public function deleteMatProd($idProd, $libMat)
    {
        $matProd = $this->where("idProd", $idProd)
            ->where("libMateriau", $libMat)
            ->first();

        if ($this->existsProdAndMat($idProd, $libMat) && $matProd) {
            $this->where("idProd", $idProd)
                ->where("libMateriau", $libMat)
                ->delete();

            return true;
        }

        return false;
    }


    public function getMateriauxProduit($idProd)
    {
        $result = $this->where("idProd", $idProd)
                       ->select("libMateriau")
                       ->get()
                       ->getResultArray();
    
        return array_column($result, 'libMateriau');
    }
}
