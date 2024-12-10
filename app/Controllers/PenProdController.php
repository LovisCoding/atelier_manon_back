<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class PenProdController extends ResourceController
{
	protected $modelName = 'App\Models\PenProdModel';
	protected $format    = 'json';

	public function getPendentifsProduit()
	{
		$idProd = $this->request->getGet("idProd");

		if (empty($idProd) || !is_numeric($idProd)) {
			return $this->respond("L'id du produit est invalide.", 400); 
		}

		$pendentifs = $this->model->getPendentifsProduit($idProd);

		return $this->respond($pendentifs);
	}

	public function addPenProd()
	{
		$data = $this->request->getJSON();

		if (empty($data->idProd) || !is_numeric($data->idProd)) {
			return $this->respond("L'id du produit est requis et doit être valide.", 400);
		}

		if (empty($data->libPendentif)) {
			return $this->respond("Le libellé de la taille est requis.", 400);
		}

		$response = $this->model->addPenProd($data->idProd, $data->libPendentif);

		if ($response) {
			return $this->respond("Le pendentif a été ajouté au produit avec succès.", 201);
		} else {
			return $this->respond("Erreur lors de l'ajout du pendentif au produit.", 500);
		}
	}

	public function addPensProd()
	{
		$data = $this->request->getJSON();

		if (empty($data->idProd) || !is_numeric($data->idProd)) {
			return $this->respond("L'id du produit est requis et doit être valide.", 400);
		}

		if (!is_array($data->tabPendentifs)) {
			return $this->respond("Les pendentifs sont requis.", 400);
		}

		$response = $this->model->addPensProd($data->idProd, $data->tabPendentifs);

		if ($response) {
			return $this->respond("Les pendentifs ont été modifiés au produit avec succès.", 201);
		} else {
			return $this->respond("Erreur lors de l'ajout des pendentifs au produit.", 500);
		}
	}

	public function deletePenProd()
	{
		$data = $this->request->getJSON();

		if (empty($data->idProd) || !is_numeric($data->idProd)) {
			return $this->respond("L'id du produit est requis et doit être valide.", 400);
		}

		if (empty($data->libPendentif)) {
			return $this->respond("Le libellé du pendentif est requis.", 400);
		}

		$response = $this->model->deletePenProd($data->idProd, $data->libPendentif);

		if ($response) {
			return $this->respond("Le pendentif a été supprimé du produit avec succès.", 201);
		} else {
			return $this->respond("Erreur lors de la suppression du pendentif du produit.", 500);
		}
	}
}
