<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class PromoProduitController extends ResourceController
{
    protected $modelName = 'App\Models\PromoProduitModel';
    protected $format    = 'json';


	public function getProduitsByCode()
	{
		$code = $this->request->getGet("code");
	
		if (empty($code)) {
			return $this->respond("Le code de promotion est requis.", 400);
		}
	
		$produits = $this->model->getProduitsByCode($code);

		return $this->respond($produits);
	}
	
	public function addPromoProduit() 
	{
		$data = $this->request->getJSON();
	
		if (empty($data->idProd) || empty($data->code)) {
			return $this->respond("Les paramètres idProd et code sont requis.", 400);
		}
	
		if (!is_numeric($data->idProd) || !is_string($data->code)) {
			return $this->respond("L'ID du produit et le code promotionnel doivent être valides.", 400);
		}
	
		$response = $this->model->addPromoProduit($data->code, $data->idProd);
	
		if (!$response) {
			return $this->respond("Impossible d'ajouter la promotion pour ce produit.", 500); 
		}
	
		return $this->respond("Promotion ajoutée au produit avec succès.", 201);
	}

	public function addPromoProduits() 
	{
		$data = $this->request->getJSON();
	
		if (empty($data->tabProd) || empty($data->code)) {
			return $this->respond("Les paramètres tabProd et code sont requis.", 400);
		}
	
		if (!is_array($data->tabProd) || !is_string($data->code)) {
			return $this->respond("Le tableau de produit et le code promotionnel doivent être valides.", 400);
		}

		foreach($data->tabProd as $idProd) {
			$response = $this->model->addPromoProduit($data->code, $idProd);
	
			if (!$response) {
				return $this->respond("Impossible d'ajouter la promotion pour ces produits.", 500); 
			}
		}
	

	
		return $this->respond("Promotion ajoutée aux produits avec succès.", 201);
	}
	
	public function deletePromoProduit() 
	{
		$data = $this->request->getJSON();
	
		if (empty($data->idProd) || empty($data->code)) {
			return $this->respond("Les paramètres idProd et code sont requis.", 400);
		}
	
		if (!is_numeric($data->idProd) || !is_string($data->code)) {
			return $this->respond("L'ID du produit et le code promotionnel doivent être valides.", 400);
		}
	
		$response = $this->model->deletePromoProduit($data->code, $data->idProd);
	
		if (!$response) {
			return $this->respond("Impossible de supprimer la promotion pour ce produit.", 500);
		}
	
		return $this->respond("Promotion supprimée du produit avec succès.", 201);
	}
	
	public function deletePromoProduits() 
	{
		$data = $this->request->getJSON();
	
		if (empty($data->tabProd) || empty($data->code)) {
			return $this->respond("Les paramètres tabProd et code sont requis.", 400);
		}
	
		if (!is_array($data->tabProd) || !is_string($data->code)) {
			return $this->respond("Le tableau de produit et le code promotionnel doivent être valides.", 400);
		}

		foreach($data->tabProd as $idProd) {
			$response = $this->model->deletePromoProduit($data->code, $idProd);
	
			if (!$response) {
				return $this->respond("Impossible de supprimer la promotion pour ces produits.", 500); 
			}
		}

	
		return $this->respond("Promotion supprimée des produits avec succès.", 201);
	}


}