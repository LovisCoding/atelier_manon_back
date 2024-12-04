<?php

namespace App\Models;

use CodeIgniter\Model;

use function PHPUnit\Framework\isEmpty;

class CommandeProduitModel extends Model
{
    protected $table = 'CommandeProduit';
    protected $primaryKey = null;

    protected $allowedFields = ['idProd', 'idCommande', 'gravure', 'variante', 'qa'];

    protected $useTimestamps = false;
    protected $returnType = 'array';

    public function getProduit($idProd)
    {
        $produitModel = new ProduitModel();
        return $produitModel->where("idProd", $idProd)->first();
    }

    public function getCommande($idCommande)
    {
        $commandeModel = new CommandeModel();
        return $commandeModel->where("idCommande", $idCommande)->first();
    }

    public function addProduitToCommande($idProd, $idCommande, $gravure, $variante, $qa)
    {
        if ($this->db->table($this->table)->insert([
            "idProd" => $idProd,
            "idCommande" => $idCommande,
            "gravure" => $gravure,
            "variante" => $variante,
            "qa" => $qa
        ])) 
        {
            return "Produit ajouté à la commande avec succès.";
        }
        return "Impossible d'ajouter ce produit.";
    }



    public function getProduitsCommande($idCommande)
    {
        if ($this->getCommande($idCommande)) {
            $produits = $this->where("idCommande", $idCommande)->findAll();

            $newProduits = [];
            foreach ($produits as $newProduit) {
                $produit = $this->getProduit($newProduit["idProd"]);
                if ($produit) {
                    $newProduit["produit"] = $produit;
                    $tabPhoto = $this->parsePgArray($produit['tabPhoto']);
                    if (!isEmpty($tabPhoto))
                        return $tabPhoto[0];
                    $newProduit["photo"] = $tabPhoto[0];
                }
                $newProduits[] = $newProduit;
            }
            return $newProduits;
        }
    }

    private function parsePgArray(string $pgArray): array
    {
        $pgArray = trim($pgArray, '{}');

        $elements = preg_split('/(?<!\\\\),/', $pgArray);

        return array_map(function ($element) {
            return trim(stripslashes($element), '"');
        }, $elements);
    }
}
