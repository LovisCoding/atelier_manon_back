<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\PieProdModel;

class PieProdController extends ResourceController
{
    protected $modelName = 'App\Models\PieProdModel';
    protected $format    = 'json';


	public function getPierresProduit()
	{
		$idProd = $this->request->getGet("idProd");
	
		if (empty($idProd) || !is_numeric($idProd)) {
			return $this->respond("L'id du produit est requis et doit être valide.", 400);
		}
	
		$pierres = $this->model->getPierresProduit($idProd);
	
		return $this->respond($pierres);
	}
	
	public function addPieProd()
	{
		$data = $this->request->getJSON();
	
		if (empty($data->idProd) || !is_numeric($data->idProd)) {
			return $this->respond("L'id du produit est requis et doit être valide.", 400);
		}
	
		if (empty($data->libPierre)) {
			return $this->respond("Le libellé de la pierre est requis.", 400);
		}
	
		$response = $this->model->addPieProd($data->idProd, $data->libPierre);
	
		if ($response) {
			return $this->respond("La pierre a été ajoutée au produit.", 201);
		} else {
			return $this->respond("Erreur lors de l'ajout de la pierre au produit.", 500);
		}
	}
	
	public function deletePieProd()
	{
		$data = $this->request->getJSON();
	
		if (empty($data->idProd) || !is_numeric($data->idProd)) {
			return $this->respond("L'id du produit est requis et doit être valide.", 400);
		}
	
		if (empty($data->libPierre)) {
			return $this->respond("Le libellé de la pierre est requis.", 400);
		}
	
		$response = $this->model->deletePieProd($data->idProd, $data->libPierre);
	
		if ($response) {
			return $this->respond("La pierre a été supprimée du produit.", 201);
		} else {
			return $this->respond("Erreur lors de la suppression de la pierre du produit.", 500);
		}
	}
	



}