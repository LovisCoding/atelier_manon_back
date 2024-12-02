<?php

namespace App\Models;

use CodeIgniter\Model;

class MatProdModel extends Model
{
    protected $table = 'matprod';

    protected $primaryKey = ['idprod', 'libmateriau'];
    protected $useAutoIncrement = false;

    protected $allowedFields = ['idprod', 'libmateriau'];

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
        $matProd = $this->where("idprod", $idProd)
            ->where("libmateriau", $libMat)
            ->first();

        if ($this->existsProdAndMat($idProd, $libMat) && !$matProd) {

            $this->db->table($this->table)->insert([
                "idprod" => $idProd,
                "libmateriau" => $libMat
            ]);

            return "Matériau ajouté au produit avec succès !";
        }

        return "Impossible d'ajouter ce matériau à ce produit ! (matériau ou produit inexistant)";
    }


    public function deleteMatProd($idProd, $libMat)
    {
        $matProd = $this->where("idprod", $idProd)
            ->where("libmateriau", $libMat)
            ->first();

        if ($this->existsProdAndMat($idProd, $libMat) && $matProd) {
            $this->where("idprod", $idProd)
                ->where("libmateriau", $libMat)
                ->delete();

            return "Matériau supprimé du produit avec succès !";
        }

        return "Impossible de supprimer ce matériau de ce produit ! (matériau ou produit inexistant)";
    }
}
