<?php

namespace App\Models;

use CodeIgniter\Model;

use function PHPUnit\Framework\isEmpty;

class ProduitModel extends Model
{
    protected $table = 'Produit';
    protected $primaryKey = 'idProd';

    protected $allowedFields = [
        'libProd',
        'descriptionProd',
        'prix',
        'estGravable',
        'tabPhoto',
        'tempsRea',
        'idCateg'
    ];

    protected $useTimestamps = false;
    protected $returnType = 'array';

    public function getProduit($idProd = -1)
    {
        if ($idProd == -1)
            return null;
        $produit = $this->where("idProd", $idProd)->first();
        $produit["estGravable"] = boolval($produit["estGravable"]);
        $produit["tabPhoto"] = $this->parsePgArray($produit["tabPhoto"]);
        return $produit;
    }


    public function getProduitsPage($page = 1, $nbDisplay = 9, $search = "", $category = null, $priceInf = 0, $priceSup = null)
    {
        // Calculer l'offset (début des éléments à afficher)
        $offset = ($page - 1) * $nbDisplay;

        $query = $this->asArray()->orderBy('idProd', 'ASC');

        if (!empty($search)) {
            $query->groupStart()
                ->like('libProd', $search)
                ->orLike('descriptionProd', $search)
                ->groupEnd();
        }

        if (!empty($category)) {
            $query->where('idCateg', $category);
        }

        $query->where('prix >=', $priceInf);

        if (isset($priceSup) && !is_null($priceSup) && intval($priceSup) !== 0) {
            $query->where('prix <=', $priceSup);
        }

        $query->limit($nbDisplay, $offset);
        $produits = $query->findAll();

        $newProduits = [];
        foreach($produits as $produit) {
            $newProd = $produit;
            $newProd["tabPhoto"] = $this->parsePgArray($produit["tabPhoto"]);
            $newProd["estGravable"] = boolval($produit["estGravable"]);

            $newProduits[] = $newProd;
        }

        return $newProduits;
    }


    public function updateProduit($idProd, $libProd, $descriptionProd, $prix, $estGravable, $tabPhoto, $tempsRea, $idCateg)
    {
        $this->update($idProd, [
            "libProd" => $libProd,
            "descriptionProd" => $descriptionProd,
            "prix" => $prix,
            "estGravable" => $estGravable,
            "tabPhoto" => $tabPhoto,
            "tempsRea" => $tempsRea,
            "idCateg" => $idCateg
        ]);
    }


    public function createProduit($libProd, $descriptionProd, $prix, $estGravable, $tabPhoto, $tempsRea, $idCateg)
    {
        $existProduct = $this->where("libProd", $libProd)->first();

        if ($existProduct) {
            return -1;
        }

        $this->insert([
            "libProd" => $libProd,
            "descriptionProd" => $descriptionProd,
            "prix" => $prix,
            "estGravable" => $estGravable,
            "tabPhoto" => $tabPhoto,
            "tempsRea" => $tempsRea,
            "idCateg" => $idCateg
        ]);

        return $this->getInsertID();
    }

    public function deleteProduit($idProd)
    {
        $this->delete($idProd);
    }


    public function getBestSellers($quantiteToDisplay)
    {
        $commandeModel = new CommandeModel();

        $commandes = $commandeModel
            ->where('dateCommande >=', date('Y-m-01', strtotime('first day of last month')))
            ->where('dateCommande <=', date('Y-m-t', strtotime('last day of last month')))
            ->findAll();

        $commandeProduitModel = new CommandeProduitModel();
        $produitsQuantites = [];

        foreach ($commandes as $commande) {
            $commandeProduits = $commandeProduitModel->getProduitsCommande($commande["idCommande"]);

            foreach ($commandeProduits as $produit) {
                $idProd = $produit["idProd"];
                $qa = intval($produit["qa"]);
                if (isset($produitsQuantites[$idProd]))
                    $produitsQuantites[$idProd] += $qa;
                else
                    $produitsQuantites[$idProd] = $qa;
            }
        }

        $produits = [];
        if (sizeof($produitsQuantites) < $quantiteToDisplay) {
            $produits = $this->limit(3)->findAll();
        } else {
            arsort($produitsQuantites);
            $topProduits = array_slice($produitsQuantites, 0, 3, true);

            foreach ($topProduits as $idProd => $qa) {
                $produits[] = $this->getProduit($idProd);
            }
        }

        $newProduit = [];
        foreach ($produits as $produit) {
            $tabPhoto = $this->parsePgArray($produit['tabPhoto']);
            if (sizeof($tabPhoto) > 0) {
                $produit["photo"] = $tabPhoto[0];
                $newProduit[] = $produit;
            }
        }

        return $newProduit;

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
