<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\FilProdModel;

class FilProdController extends ResourceController
{
	protected $modelName = 'App\Models\FilProdModel';
	protected $format    = 'json';


	public function getFilsProduit()
	{
		$idProd = $this->request->getGet("idProd");

		if (empty($idProd) || !is_numeric($idProd)) {
			return $this->respond("L'ID du produit est requis et doit être valide.", 400);
		}

		$fils = $this->model->getFilsProduit($idProd);

		return $this->respond($fils);
	}


	public function addFilProd()
	{
		$data = $this->request->getJSON();

		if (empty($data->idProd) || empty($data->libCouleur)) {
			return $this->respond("Les champs 'idProd' et 'libCouleur' sont requis.", 400);
		}

		$idProd = intval($data->idProd);
		$libCouleur = $data->libCouleur;

		$response = $this->model->addFilProd($idProd, $libCouleur);

		if ($response) {
			return $this->respond("Le fil a été ajouté au produit avec succès.", 201);
		} else {
			return $this->respond("Erreur lors de l'ajout du fil au produit.", 500);
		}
	}

	public function addFilsProd()
	{
		$data = $this->request->getJSON();

		if (empty($data->idProd) || !is_array($data->tabFils)) {
			return $this->respond("Les champs 'idProd' et 'tabFils' sont requis.", 400);
		}

		$idProd = intval($data->idProd);
		$tabFils = $data->tabFils;

		$response = $this->model->addFilsProd($idProd, $tabFils);

		if ($response) {
			return $this->respond("Les fils ont été modifiés du produit avec succès.", 201);
		} else {
			return $this->respond("Erreur lors de l'ajout du fil au produit.", 500);
		}
	}

	public function deleteFilProd() 
	{
		$data = $this->request->getJSON();
	
		if (empty($data->idProd) || empty($data->libCouleur)) {
			return $this->respond("Les champs 'idProd' et 'libCouleur' sont requis.", 400);
		}
	
		$idProd = $data->idProd;
		$libCouleur = $data->libCouleur;
	
		$response = $this->model->deleteFilProd($idProd, $libCouleur);
	
		if ($response) {
			return $this->respond("Le fil a été supprimé du produit avec succès.", 201);
		} else {
			return $this->respond("Erreur lors de la suppression du fil du produit.", 500); 
		}
	}
}
