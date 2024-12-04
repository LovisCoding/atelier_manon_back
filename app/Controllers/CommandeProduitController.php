<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class CommandeProduitController extends ResourceController
{
    protected $modelName = 'App\Models\CommandeProduitModel';
    protected $format    = 'json';


	public function getProduitsCommande()
	{
		$idCommande = $this->request->getGet("idCommande");

		$produits =  $this->model->getProduitsCommande($idCommande);

		return $this->respond($produits);
	}

}