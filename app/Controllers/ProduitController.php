<?php

namespace App\Controllers;

use App\Models\CommandeModel;
use App\Models\CommandeProduitModel;
use CodeIgniter\RESTful\ResourceController;
use App\Models\ProduitModel;

class ProduitController extends ResourceController
{
    protected $modelName = 'App\Models\ProduitModel';
    protected $format    = 'json';

    public function getImage($image)
    {
        $width = $this->request->getGet('width');
    
        $filePath = FCPATH . 'images/' . $image;
    
        if (!is_file($filePath)) {
            return $this->failNotFound('Image not found');
        }
    
        $img = imagecreatefromwebp($filePath);
    
        if ($width) {

            $originalWidth = imagesx($img);
            $originalHeight = imagesy($img);
    
            $newWidth = (int)$width;

            $ratio = $newWidth / $originalWidth;
            $newHeight = (int)($originalHeight * $ratio);
    
            $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
    
            imagecopyresampled($resizedImage, $img, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);
    
            imagedestroy($img);
    
            $img = $resizedImage;
        }
    
        header("Content-Type: image/webp");    

        imagewebp($img);
        imagedestroy($img);

        exit;
    }


    public function produits()
    {
        $page = intval($this->request->getGet('page'));
        $nbDisplay = intval($this->request->getGet('nbDisplay'));

        $search = $this->request->getGet('search') ?? '';
        $category = $this->request->getGet('category') ?? '';
        $priceInf = floatval($this->request->getGet('priceInf')) ?? 0;
        $priceSup = floatval($this->request->getGet('priceSup')) ?? null;

        if ($page < 1) {
            return $this->respond("Le numéro de page doit être supérieur ou égal à 1.", 400);
        }
        if ($nbDisplay < 1 || $nbDisplay > 100) {
            return $this->respond("Le nombre d'articles à afficher doit être entre 1 et 100.", 400);
        }

        $produits = $this->model->getProduitsPage($page, $nbDisplay, $search, $category, $priceInf, $priceSup);

        return $this->respond([
            'page' => $page,
            'nbDisplay' => $nbDisplay,
            'produits' => $produits
        ]);
    }

    public function produit()
    {
        $idProd = $this->request->getGet('idProd');

        if (empty($idProd) || !is_numeric($idProd)) {
            return $this->respond("ID de produit invalide.", 400);
        }

        $produit = $this->model->getProduit($idProd);

        if (!$produit) {
            return $this->respond("Produit non trouvé.", 404);
        }

        return $this->respond($produit);
    }

    public function updateProduit()
    {
        $data = $this->request->getJSON();

        if (empty($data->libProd) || empty($data->descriptionProd)) {
            return $this->respond("Le libellé et la description du produit sont requis.", 400);
        }

        $tabPhoto = is_array($data->tabPhoto) ? '{' . implode(',', array_map(fn($item) => "\"$item\"", $data->tabPhoto)) . '}' : null;

        if ($data->idProd !== -1) {
            $response = $this->model->updateProduit(
                $data->idProd,
                $data->libProd,
                $data->descriptionProd,
                floatval($data->prix),
                boolval($data->estGravable),
                $tabPhoto,
                intval($data->tempsRea),
                intval($data->idCateg)
            );

            if ($response)
                return $this->respond("Modification effectuée avec succès.", 201);
            else
                return $this->respond("Impossible de modifier ce produit.", 500);
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
                return $this->respond("Ce nom est déjà utilisé", 400);
            }

            return $this->respond($id);
        }
    }

    public function deleteProduit()
    {
        $data = $this->request->getJSON();

        if (empty($data->idProd) || !is_numeric($data->idProd)) {
            return $this->respond("ID de produit invalide.", 400);
        }

        $response = $this->model->deleteProduit($data->idProd);

        if ($response)
            return $this->respond("Produit supprimé avec succès !", 201);
        else
            return $this->respond("Impossible de supprimer ce produit", 400);
    }

    public function getBestSellers()
    {
        $quantiteToDisplay = $this->request->getGet("quantiteToDisplay");

        if (!is_numeric($quantiteToDisplay) || $quantiteToDisplay < 1) {
            return $this->respond("La quantité à afficher doit être un nombre valide et supérieur à 0.", 400);
        }

        $bestSellers = $this->model->getBestSellers($quantiteToDisplay);

        return $this->respond($bestSellers);
    }

	public function produitsAll() {
		$produits = $this->model->getProduits();

		return $this->respond($produits);
	}

    public function produitsAllVente() {
        $produits = $this->model->getProduitsVente();

        return $this->respond($produits);
    }

    
    // ...
}
