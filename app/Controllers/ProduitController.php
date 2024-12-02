<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\ProduitModel;

class ProduitController extends ResourceController
{
    protected $modelName = 'App\Models\ProduitModel';
    protected $format    = 'json';

    public function produits()
    {
        $page = $this->request->getGet('page');
        $nbDisplay = $this->request->getGet('nbDisplay');

        $produits = $this->model->getProduitsPage($page, $nbDisplay);

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

    // ...
}