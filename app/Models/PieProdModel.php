<?php

namespace App\Models;

use CodeIgniter\Model;

class PieProdModel extends Model
{
    protected $table = 'PieProd';
    protected $primaryKey = null;

    protected $allowedFields = ['idProd', 'libpierre'];

    protected $useTimestamps = false;
    protected $returnType = 'array';

    public function existsProdAndPierre($idProd, $libPierre)
    {
        $produitModel = new ProduitModel();
        $existProd = $produitModel->getProduit($idProd);

        $pierreModel = new PierreModel();
        $existPierre = $pierreModel->getPierre($libPierre);

        if (!$existProd || !$existPierre) {
            return false;
        }

        return true;
    }


    public function addPieProd($idProd, $libPierre)
    {
        $pierreProd = $this->where("idProd", $idProd)
            ->where("libPierre", $libPierre)
            ->first();

        if ($this->existsProdAndPierre($idProd, $libPierre) && !$pierreProd) {

            $this->db->table($this->table)->insert([
                "idProd" => $idProd,
                "libPierre" => $libPierre
            ]);

            return "Pierre ajouté au produit avec succès !";
        }

        return "Impossible d'ajouter cette pierre à ce produit ! (matériau ou produit inexistant)";
    }


    public function deletePieProd($idProd, $libPierre)
    {
        $pierreProd = $this->where("idProd", $idProd)
            ->where("libPierre", $libPierre)
            ->first();

        if ($this->existsProdAndPierre($idProd, $libPierre) && $pierreProd) {
            $this->where("idProd", $idProd)
                ->where("libPierre", $libPierre)
                ->delete();

            return "Pierre supprimé du produit avec succès !";
        }

        return "Impossible de supprimer cette pierre de ce produit ! (matériau ou produit inexistant)";
    }

    public function getPierresProduit($idProd)
    {
        $result = $this->where("idProd", $idProd)
                       ->select("libPierre")
                       ->get()
                       ->getResultArray();
    
        return array_column($result, 'libPierre');
    }
    
}
