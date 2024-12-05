<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\FilModel;

class MateriauController extends ResourceController
{
	protected $modelName = 'App\Models\MateriauModel';
	protected $format    = 'json';

	public function getMateriaux()
	{
		$materiaux =  $this->model->getMateriaux();

		return $this->respond($materiaux);
	}

	public function addMateriau()
	{
		$data = $this->request->getJSON();

		if (empty($data->libMateriau)) {
			return $this->respond("Le champ 'libMateriau' est requis.", 400); // Erreur 400 si le champ est manquant
		}

		$response = $this->model->addMateriau($data->libMateriau);

		if ($response) {
			return $this->respond("Le matériau a été ajouté avec succès.", 201);
		} else {
			return $this->respond("Erreur lors de l'ajout du matériau.", 500);
		}
	}

	public function deleteMateriau() 
	{
		$data = $this->request->getJSON();
	
		if (empty($data->libMateriau)) {
			return $this->respond("Le champ 'libMateriau' est requis.", 400);
		}
	
		$response = $this->model->deleteMateriau($data->libMateriau);
	
		if ($response) {
			return $this->respond("Le matériau a été supprimé avec succès.", 201);
		} else {
			return $this->respond("Erreur lors de la suppression du matériau.", 500);
		}
	}
}
