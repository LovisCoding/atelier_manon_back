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
	
		if (empty($idCommande) || !is_numeric($idCommande)) {
			return $this->fail("L'identifiant de la commande est requis et doit être un entier valide.", 400);
		}
	
		$produits = $this->model->getProduitsCommande($idCommande);
	
		return $this->respond($produits);
	}
	
}