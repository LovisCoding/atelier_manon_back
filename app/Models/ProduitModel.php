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
        $produit["estGravable"] = $produit["estGravable"] == "t";
        $produit["tabPhoto"] = $this->parsePgArray($produit["tabPhoto"]);
        return $produit;
    }


    public function getProduitsPage($search = "", $category = null, $priceInf = 0, $priceSup = null)
    {
        // Calculer l'offset (début des éléments à afficher)

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

        $produits = $query->orderBy("libProd")->findAll();

        $newProduits = [];
        foreach ($produits as $produit) {
            $newProd = $produit;
            $newProd["tabPhoto"] = $this->parsePgArray($produit["tabPhoto"]);
            $newProd["estGravable"] = $produit["estGravable"] == "t";

            $newProduits[] = $newProd;
        }

        return $newProduits;
    }


    public function updateProduit($idProd, $libProd, $descriptionProd, $prix, $estGravable, $tempsRea, $idCateg)
    {
        return $this->update($idProd, [
            "libProd" => $libProd,
            "descriptionProd" => $descriptionProd,
            "prix" => $prix,
            "estGravable" => $estGravable,
            "tempsRea" => $tempsRea,
            "idCateg" => $idCateg
        ]);
    }


    public function createProduit($libProd, $descriptionProd, $prix, $estGravable, $tempsRea, $idCateg)
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
            "tabPhoto" => "{}",
            "tempsRea" => $tempsRea,
            "idCateg" => $idCateg
        ]);

        return $this->getInsertID();
    }

    public function deleteProduit($idProd)
    {
        return $this->delete($idProd);
    }


    public function getBestSellers($quantiteToDisplay)
    {
        $commandeModel = new CommandeModel();

        $commandes = $commandeModel
            ->where('dateCommande >=', date('Y-m-01', strtotime('first day of last month')))
            ->where('dateCommande <=', date('Y-m-t', strtotime('last day of last month')))
            ->orderBy("libProd")
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
            $produits = $this->limit(3)->orderBy("libProd")->findAll();
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

    public function getProduits()
    {
        $produits = $this->orderBy("libProd")->findAll();
        $newProduits = [];
        foreach ($produits as $produit) {
            $tabPhoto = $this->parsePgArray($produit['tabPhoto']);
            if (sizeof($tabPhoto) > 0) {
                $produit["photo"] = $tabPhoto[0];
                $produit["tabPhoto"] = $tabPhoto;
            }
            $newProduits[] = $produit;
        }
        return $newProduits;
    }

    public function getProduitsVente()
    {
        $produits = $this->getProduits();

        $commandeProduitModel = new CommandeProduitModel();
        $newProd = [];
        $totalQuantity = 0;
        $prixTotalEstimated = 0;
        foreach ($produits as $produit) {
            $nbSell = 0;
            $commandeProduits = $commandeProduitModel->getCommandeProduits($produit["idProd"]);

            foreach ($commandeProduits as $commandeProduit)
                $nbSell += $commandeProduit["qa"];

            $produit["quantiteVendue"] = $nbSell;
            $produit["prixEstime"] = $nbSell * floatval($produit["prix"]);

            $totalQuantity += $nbSell;
            $prixTotalEstimated += $nbSell * floatval($produit["prix"]);

            $newProd[] = $produit;
        }

        return [
            "prixTotalEstime" => $prixTotalEstimated,
            "quantiteTotal" => $totalQuantity,
            "produits" => $newProd
        ];
    }

    private function parsePgArray(string $pgArray): array
    {
        $pgArray = trim($pgArray, '{}');

        if ($pgArray === '') {
            return [];
        }

        $elements = preg_split('/(?<!\\\\),/', $pgArray);

        return array_map(function ($element) {
            return trim(stripslashes($element), '"');
        }, $elements);
    }

    private function toPgArray(array $phpArray): string
    {
        $escapedElements = array_map(function ($element) {

            if (is_string($element)) {
                return '"' . addslashes($element) . '"';
            }
            return $element;
        }, $phpArray);

        return '{' . implode(',', $escapedElements) . '}';
    }



    public function uploadPhoto($photo, $libPhoto, $idProd)
    {
        $produit = $this->getProduit($idProd);

        if (!$produit)
            return false;

        $tabPhoto = $this->parsePgArray($this->toPgArray($produit["tabPhoto"]));

        $uploadDir = FCPATH . 'images/' . $idProd . '/';

        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0755, true)) {
                return false;
            }
        }

        $fileName = $libPhoto;
        $filePath = $uploadDir . $fileName;

        if (file_exists($filePath))
            return false;

        $photoBase64 = preg_replace('/^data:image\/\w+;base64,/', '', $photo);


        $imageData = base64_decode($photoBase64);
        if ($imageData === false) {
            return false;
        }

        $image = imagecreatefromstring($imageData);
        if ($image === false) {
            return false;
        }

        imagewebp($image, $filePath, 80);
        imagedestroy($image);

        $tabPhoto[] = $fileName;

        $newTabPhoto = $this->toPgArray($tabPhoto);
        $this->update($idProd, [
            "tabPhoto" => $newTabPhoto
        ]);

        return true;
    }

    public function deletePhoto($libPhoto, $idProd)
    {
        $produit = $this->getProduit($idProd);

        if (!$produit)
            return false;

        $tabPhoto = $this->parsePgArray($this->toPgArray($produit["tabPhoto"]));

        $uploadDir = FCPATH . 'images/';

        $fileName = $libPhoto;

        $filePath = $uploadDir . $fileName;

        if (!file_exists($filePath))
            return false;

        if (!unlink($filePath)) {
            return false;
        }

        $tabPhoto = array_filter($tabPhoto, function ($photo) use ($libPhoto) {
            return $photo !== $libPhoto;
        });

        $newTabPhoto = $this->toPgArray($tabPhoto);

        $this->update($idProd, [
            "tabPhoto" => $newTabPhoto
        ]);

        return true;
    }

    public function updateImagesOrder($tabPhoto, $idProd)
    {
        $produit = $this->getProduit($idProd);

        if ($produit) {
            $this->update($idProd, [
                "tabPhoto" => $tabPhoto
            ]);
            return true;
        }

        return false;
    }



    /******************/
    //     STATS      //
    /******************/

    public function getStatsProportionVente()
    {
        $commandeModel = new CommandeModel();
        $commandeProduitModel = new CommandeProduitModel();

        $commandes = $commandeModel->getCommandes();
        $stats = [];

        if ($commandes) {
            foreach ($commandes as $commande) {

                $dateCommande = new \DateTime($commande["dateCommande"]);
                $yearMonth = $dateCommande->format('Y-m');

                $produits = $commandeProduitModel->getProduitsCommande($commande["idCommande"]);

                if (!isset($stats[$yearMonth])) {
                    $stats[$yearMonth] = [];
                }

                foreach ($produits as $produit) {
                    $libProd = $produit['produit']['libProd'];

                    if (!isset($stats[$yearMonth][$libProd])) {
                        $stats[$yearMonth][$libProd] = 0;
                    }

                    $stats[$yearMonth][$libProd] += $produit['qa'];
                }
            }
        }

        $proportions = [];
        foreach ($stats as $month => $products) {
            $totalProducts = array_sum($products);

            foreach ($products as $product => $quantity) {
                $proportions[$month][$product] = ($quantity / $totalProducts) * 100;
            }
        }

        return $proportions;
    }


    public function getStatsYearCA()
    {
        $commandeModel = new CommandeModel();
        $commandes = $commandeModel->getCommandes();

        $stats = [];

        if ($commandes) {
            foreach ($commandes as $commande) {

                $dateCommande = new \DateTime($commande['dateCommande']);
                $yearMonth = $dateCommande->format('Y-m');

                if (!isset($stats[$yearMonth])) {
                    $stats[$yearMonth] = 0;
                }

                $stats[$yearMonth] += $commande['prixTotal'];
            }
        }

        return $stats;
    }

    public function getStatsProportionGravure()
    {
        $commandeModel = new CommandeModel();
        $commandeProduitModel = new CommandeProduitModel();

        $commandes = $commandeModel->getCommandes();
        $totalCommandes = count($commandes);
        $customizedOrders = 0;

        if ($commandes) {
            foreach ($commandes as $commande) {
                $produits = $commandeProduitModel->getProduitsCommande($commande["idCommande"]);

                foreach ($produits as $produit) {
                    if (!empty($produit["gravure"])) {
                        $customizedOrders++;
                        break; // Une seule personnalisation suffit pour marquer la commande comme personnalisée
                    }
                }
            }
        }

        return $totalCommandes > 0 ? ($customizedOrders / $totalCommandes) * 100 : 0;
    }

    public function getStatsProportionCategorie()
    {
        $commandeModel = new CommandeModel();
        $commandeProduitModel = new CommandeProduitModel();
        $categorieModel = new CategorieModel();

        $commandes = $commandeModel->getCommandes();
        $categorySales = [];
        $totalQuantity = 0;

        if ($commandes) {
            foreach ($commandes as $commande) {
                $produits = $commandeProduitModel->getProduitsCommande($commande["idCommande"]);

                foreach ($produits as $produit) {
                    $categoryId = $produit['produit']['idCateg'];
                    $quantity = $produit['qa'];

                    $category = $categorieModel->getCategorie($categoryId);

                    if ($category) {
                        if (!isset($categorySales[$category['libCateg']])) {
                            $categorySales[$category['libCateg']] = 0;
                        }

                        $categorySales[$category['libCateg']] += $quantity;
                        $totalQuantity += $quantity;
                    }
                }
            }
        }

        foreach ($categorySales as $category => $sales) {
            $categorySales[$category] = ($sales / $totalQuantity) * 100;
        }

        return $categorySales;
    }

    public function getStatsVenteMoyenCommande()
    {
        $commandeModel = new CommandeModel();

        $commandes = $commandeModel->getCommandes();
        $totalRevenue = 0;
        $totalOrders = count($commandes);

        if ($commandes) {
            foreach ($commandes as $commande) {
                $totalRevenue += $commande['prixTotalReduc']; // On prend en compte le prix réduit
            }
        }

        return $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;
    }

    public function getStatsProportionCadeau()
    {
        $commandeModel = new CommandeModel();

        $commandes = $commandeModel->getCommandes();
        $totalCommandes = count($commandes);
        $giftCardOrders = 0;

        if ($commandes) {
            foreach ($commandes as $commande) {
                if ($commande['estCadeau'] === 't') {
                    $giftCardOrders++;
                }
            }
        }

        return $totalCommandes > 0 ? ($giftCardOrders / $totalCommandes) * 100 : 0;
    }
}
