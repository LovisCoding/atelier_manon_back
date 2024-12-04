<?php

namespace App\Controllers;

use App\Models\CommandeModel;
use CodeIgniter\RESTful\ResourceController;
use App\Models\ProduitModel;

class ProduitController extends ResourceController
{
    protected $modelName = 'App\Models\ProduitModel';
    protected $format    = 'json';

    public function getImage($image)
    {
        $filePath = FCPATH . 'images/' . $image;
    
        if (!is_file($filePath)) {
            return $this->failNotFound('Image not found');
        }
    
        $mimeType = mime_content_type($filePath);
    
        header("Content-Type: $mimeType");
        header("Content-Length: " . filesize($filePath));
    
        readfile($filePath);
        exit;
    }
    

    public function produits()
    {
        $page = intval($this->request->getGet('page'));
        $nbDisplay = intval($this->request->getGet('nbDisplay'));

        $search = $this->request->getGet('search');
        $category = $this->request->getGet('category');
        $priceInf = floatval($this->request->getGet('priceInf'));
        $priceSup = floatval($this->request->getGet('priceSup')) ?? null;

        $produits = $this->model->getProduitsPage($page, $nbDisplay, $search, $category, $priceInf, $priceSup);

        return $this->respond(
            [
                'page' => $page,
                'nbDisplay' => $nbDisplay,
                'produits' => $produits
            ]
        );
    }

    public function produit()
    {
        $idProd = $this->request->getGet('idProd');

        $produit = $this->model->getProduit($idProd);

        return $this->respond($produit);
    }

    public function updateProduit()
    {
        $data = $this->request->getJSON();
        if (is_array($data->tabPhoto)) {
            $tabPhoto = '{' . implode(',', array_map(fn($item) => "\"$item\"", $data->tabPhoto)) . '}';
        }

        if ($data->idProd !== -1) {
            $this->model->updateProduit(
                $data->idProd,
                $data->libProd,
                $data->descriptionProd,
                floatval($data->prix),
                boolval($data->estGravable),
                $tabPhoto,
                intval($data->tempsRea),
                intval($data->idCateg)
            );

            return $this->respond("Modification effectuée avec succès.");
        } else {
            $id = $this->model->createProduit(
                $data->libProd,
                $data->descriptionProd,
                floatval($data->prix),
                boolval($data->estGravable),
                $tabPhoto,
                intval($data->tempsRea),
                intval($data->idCateg)
            );

            if ($id == -1) {
                return $this->respond("Ce nom est déjà utilisé");
            }
            return $this->respond($id);
        }
    }

    public function deleteProduit()
    {
        $data = $this->request->getJSON();

        $this->model->deleteProduit($data->idProd);

        return $this->respond("Produit supprimé avec succès !");
    }



    public function addMateriel()
    {
        $data = $this->request->getJSON();
        $idProd = $data->idProd;
        $idMateriel = $data->idMateriel;

        $this->model->deleteProduit($data->idProd);

        return $this->respond("Produit supprimé avec succès !");
    }

    public function getBestSellers()
    {
        $quantiteToDisplay = $this->request->getGet("quantiteToDisplay");
        $bestSellers = $this->model->getBestSellers($quantiteToDisplay);
        return $this->respond($bestSellers);
    }


    // ...
}
