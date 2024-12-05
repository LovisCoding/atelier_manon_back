<?php

namespace App\Models;

use CodeIgniter\Model;

class PanierModel extends Model
{
    protected $table = 'Panier';
    protected $primaryKey = null;
    protected $useAutoIncrement = false;


    protected $allowedFields = ['idProd', 'idCli', 'gravure', 'variante', 'qa'];

    protected $useTimestamps = false;
    protected $returnType = 'array';


    public function getPaniersFromClient($idCli)
    {
        $paniers = $this->where("idCli", $idCli)->findAll();
        $newPaniers = [];
        foreach($paniers as $panier) {
            $produit = $this->getProduit($panier["idProd"]);
            if ($produit) {
                $panier["produit"] = $produit;
                $newPaniers[] = $panier;
            }
        }
        return $newPaniers;
    }

    public function deletePanierClient($idCli)
    {
        $existClient = $this->getClient($idCli);
        if ($existClient) {
            $this->where("idCli", $idCli)->delete();
            return "Panier supprimé avec succès.";
        }
        return "Impossible de supprimé ce panier.";
    }

    public function getProduit($idProd = -1)
    {
        if ($idProd == -1)
            return null;

        $produitModel = new ProduitModel();
        return $produitModel->getProduit($idProd);
    }

    public function getClient($idCli = -1)
    {
        if ($idCli == -1)
            return null;

        $accountModel = new CompteModel();
        return $accountModel->getAccountById($idCli);
    }

    public function existsProdAndCli($idProd, $idCli)
    {
        $produitModel = new ProduitModel();
        $existProd = $produitModel->getProduit($idProd);

        $existAccount = $this->getClient(intval($idCli));

        if (!$existProd || !$existAccount) {
            return false;
        }

        return true;
    }

    public function getPanierByProdAndCli($idProd, $idCli, $gravure, $variante)
    {
        return $this->where("idProd", $idProd)
            ->where("idCli", $idCli)
            ->where("gravure", $gravure)
            ->where("variante", $variante)
            ->first();
    }

    public function addProductToPanier($idProd, $gravure, $variante, $idCli)
    {
        if ($this->existsProdAndCli($idProd, $idCli)) {
            $panier = $this->getPanierByProdAndCli($idProd, $idCli, $gravure, $variante);
            if ($panier) {
                $qa = intval($panier["qa"]) + 1;

                $this->where("idProd", $idProd)
                ->where("idCli", $idCli)
                ->where("gravure", $gravure)
                ->where("variante", $variante)
                ->set([
                    "qa" => $qa
                ])->update();

                return true;
            } else {
                $qa = 1;

                $this->db->table($this->table)->insert([
                    "idProd" => $idProd,
                    "gravure" => $gravure,
                    "variante" => $variante,
                    "idCli" => $idCli,
                    "qa" => $qa
                ]);

                return true;
            }
        }
        return false;
    }

    public function deleteProductFromPanier($idProd, $gravure, $variante, $idCli)
    {
        if ($this->existsProdAndCli($idProd, $idCli)) {
            $panier = $this->getPanierByProdAndCli($idProd, $idCli, $gravure, $variante);
            if ($panier) {
                $this->where("idProd", $idProd)
                ->where("idCli", $idCli)
                ->where("gravure", $gravure)
                ->where("variante", $variante)
                ->delete();
                return true;
            }
        }

        return false;
    }

    public function reduceProductFromPanier($idProd, $gravure, $variante, $idCli)
    {
        if ($this->existsProdAndCli($idProd, $idCli)) {
            $panier = $this->getPanierByProdAndCli($idProd, $idCli, $gravure, $variante);
            if ($panier) {
                $qa = intval($panier["qa"]) - 1;

                if ($qa == 0) {
                    $this->where("idProd", $idProd)
                    ->where("idCli", $idCli)
                    ->where("gravure", $gravure)
                    ->where("variante", $variante)
                    ->delete();
                    return true;
                } else {
                    $this->where("idProd", $idProd)
                    ->where("idCli", $idCli)
                    ->where("gravure", $gravure)
                    ->where("variante", $variante)
                    ->set([
                        "qa" => $qa
                    ])->update();

                    return true;
                }
            }
        }

        return false;
    }
}
