<?php

namespace App\Models;

use CodeIgniter\Model;

class FilProdModel extends Model
{
    protected $table = 'FilProd';

    protected $primaryKey = ['idProd', 'libcouleur'];
    protected $useAutoIncrement = false;

    protected $allowedFields = ['idProd', 'libcouleur'];

    protected $useTimestamps = false;
    protected $returnType = 'array';

    public function existsProdAndFil($idProd, $libCouleur)
    {
        $produitModel = new ProduitModel();
        $existProd = $produitModel->getProduit($idProd);

        $filModel = new FilModel();
        $existCouleur = $filModel->getFil($libCouleur);

        if (!$existProd || !$existCouleur) {
            return false;
        }

        return true;
    }


    public function addFilProd($idProd, $libCouleur)
    {
        $filProd = $this->where("idProd", $idProd)
            ->where("libCouleur", $libCouleur)
            ->first();

        if ($this->existsProdAndFil($idProd, $libCouleur) && !$filProd) {

            $this->db->table($this->table)->insert([
                "idProd" => $idProd,
                "libCouleur" => $libCouleur
            ]);

            return "Fil ajouté au produit avec succès !";
        }

        return "Impossible d'ajouter ce fil à ce produit ! (matériau ou produit inexistant)";
    }


    public function deleteFilProd($idProd, $libCouleur)
    {
        $filProd = $this->where("idProd", $idProd)
            ->where("libCouleur", $libCouleur)
            ->first();

        if ($this->existsProdAndFil($idProd, $libCouleur) && $filProd) {
            $this->where("idProd", $idProd)
                ->where("libCouleur", $libCouleur)
                ->delete();

            return "Fil supprimé du produit avec succès !";
        }

        return "Impossible de supprimer cette fil de ce produit ! (matériau ou produit inexistant)";
    }

    public function getFilsProduit($idProd)
    {
        $result = $this->where("idProd", $idProd)
            ->select("libCouleur")
            ->get()
            ->getResultArray();

        return array_column($result, 'libCouleur');
    }
}
