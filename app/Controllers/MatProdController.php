<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\MatProdModel;

class MatProdController extends ResourceController
{
	protected $modelName = 'App\Models\MatProdModel';
	protected $format    = 'json';

	public function getMateriauxProduit()
	{
		$idProd = $this->request->getGet("idProd");

		if (empty($idProd) || !is_numeric($idProd)) {
			return $this->respond("L'id du produit est invalide.", 400); 
		}

		$materiaux = $this->model->getMateriauxProduit($idProd);

		return $this->respond($materiaux);
	}

	public function addMatProd()
	{
		$data = $this->request->getJSON();

		if (empty($data->idProd) || !is_numeric($data->idProd)) {
			return $this->respond("L'id du produit est requis et doit être valide.", 400);
		}

		if (empty($data->libMat)) {
			return $this->respond("Le libellé du matériau est requis.", 400);
		}

		$response = $this->model->addMatProd($data->idProd, $data->libMat);

		if ($response) {
			return $this->respond("Le matériau a été ajouté au produit avec succès.", 201);
		} else {
			return $this->respond("Erreur lors de l'ajout du matériau au produit.", 500);
		}
	}

	public function addMatsProd()
	{
		$data = $this->request->getJSON();

		if (empty($data->idProd) || !is_numeric($data->idProd)) {
			return $this->respond("L'id du produit est requis et doit être valide.", 400);
		}

		if (!is_array($data->tabMateriaux)) {
			return $this->respond("Les matériaux sont requis.", 400);
		}

		$response = $this->model->addMatsProd($data->idProd, $data->tabMateriaux);

		if ($response) {
			return $this->respond("Les matériaux ont été modifiés au produit avec succès.", 201);
		} else {
			return $this->respond("Erreur lors de l'ajout du matériau au produit.", 500);
		}
	}

	public function deleteMatProd()
	{
		$data = $this->request->getJSON();

		if (empty($data->idProd) || !is_numeric($data->idProd)) {
			return $this->respond("L'id du produit est requis et doit être valide.", 400);
		}

		if (empty($data->libMat)) {
			return $this->respond("Le libellé du matériau est requis.", 400);
		}

		$response = $this->model->deleteMatProd($data->idProd, $data->libMat);

		if ($response) {
			return $this->respond("Le matériau a été supprimé du produit avec succès.", 201);
		} else {
			return $this->respond("Erreur lors de la suppression du matériau du produit.", 500);
		}
	}
}
