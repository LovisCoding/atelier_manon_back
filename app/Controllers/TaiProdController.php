<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class TaiProdController extends ResourceController
{
	protected $modelName = 'App\Models\TaiProdModel';
	protected $format    = 'json';

	public function getTaillesProduit()
	{
		$idProd = $this->request->getGet("idProd");

		if (empty($idProd) || !is_numeric($idProd)) {
			return $this->respond("L'id du produit est invalide.", 400); 
		}

		$tailles = $this->model->getTaillesProduit($idProd);

		return $this->respond($tailles);
	}

	public function addTaiProd()
	{
		$data = $this->request->getJSON();

		if (empty($data->idProd) || !is_numeric($data->idProd)) {
			return $this->respond("L'id du produit est requis et doit être valide.", 400);
		}

		if (empty($data->libTaille)) {
			return $this->respond("Le libellé de la taille est requis.", 400);
		}

		$response = $this->model->addTaiProd($data->idProd, $data->libTaille);

		if ($response) {
			return $this->respond("La taille a été ajoutée au produit avec succès.", 201);
		} else {
			return $this->respond("Erreur lors de l'ajout de la taille au produit.", 500);
		}
	}

	public function addTaisProd()
	{
		$data = $this->request->getJSON();

		if (empty($data->idProd) || !is_numeric($data->idProd)) {
			return $this->respond("L'id du produit est requis et doit être valide.", 400);
		}

		if (!is_array($data->tabTailles)) {
			return $this->respond("Les tailles sont requises.", 400);
		}

		$response = $this->model->addTaisProd($data->idProd, $data->tabTailles);

		if ($response) {
			return $this->respond("Les tailles ont été modifiés au produit avec succès.", 201);
		} else {
			return $this->respond("Erreur lors de l'ajout des tailles au produit.", 500);
		}
	}

	public function deleteTaiProd()
	{
		$data = $this->request->getJSON();

		if (empty($data->idProd) || !is_numeric($data->idProd)) {
			return $this->respond("L'id du produit est requis et doit être valide.", 400);
		}

		if (empty($data->libTaille)) {
			return $this->respond("Le libellé de la taille est requise.", 400);
		}

		$response = $this->model->deleteTaiProd($data->idProd, $data->libTaille);

		if ($response) {
			return $this->respond("La taille a été supprimé du produit avec succès.", 201);
		} else {
			return $this->respond("Erreur lors de la suppression de la taille du produit.", 500);
		}
	}
}
