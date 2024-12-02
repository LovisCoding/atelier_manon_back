<?php

namespace App\Models;

use CodeIgniter\Model;

class PieProdModel extends Model
{
    protected $table = 'filprod';
    protected $primaryKey = null;

    protected $allowedFields = ['idprod', 'libcouleur'];

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


    public function addPieProd($idProd, $libCouleur)
    {
        $filProd = $this->where("idprod", $idProd)
            ->where("libcouleur", $libCouleur)
            ->first();

        if ($this->existsProdAndFil($idProd, $libCouleur) && !$filProd) {

            $this->db->table($this->table)->insert([
                "idprod" => $idProd,
                "libcouleur" => $filProd
            ]);

            return "Fil ajouté au produit avec succès !";
        }

        return "Impossible d'ajouter cette fil à ce produit ! (matériau ou produit inexistant)";
    }


    public function deletePieProd($idProd, $libCouleur)
    {
        $filProd = $this->where("idprod", $idProd)
            ->where("libcouleur", $libCouleur)
            ->first();

        if ($this->existsProdAndPierre($idProd, $libCouleur) && $filProd) {
            $this->where("idprod", $idProd)
                ->where("libcouleur", $libCouleur)
                ->delete();

            return "Fil supprimé du produit avec succès !";
        }

        return "Impossible de supprimer cette fil de ce produit ! (matériau ou produit inexistant)";
    }
}
