<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\FilModel;

class FilController extends ResourceController
{
    protected $modelName = 'App\Models\FilModel';
    protected $format    = 'json';

	public function getFils()
	{
		$fils =  $this->model->getFils();

		return $this->respond($fils);
	}

	public function addFil() 
	{
		$data = $this->request->getJSON();
	
		if (empty($data->libCouleur)) {
			return $this->respond("Le champ 'libCouleur' est requis.", 400);
		}
	
		$response = $this->model->addFil($data->libCouleur);
	
		if ($response) {
			return $this->respond("Fil ajouté avec succès.", 201);
		} else {
			return $this->respond("Erreur lors de l'ajout du fil.", 500);
		}
	}
	
	public function deleteFil() 
	{
		$data = $this->request->getJSON();
	
		if (empty($data->libFil)) {
			return $this->respond("Le champ 'libFil' est requis.", 400);
		}
	
		$response = $this->model->deleteFil($data->libFil);
	
		if ($response) {
			return $this->respond("Fil supprimé avec succès.", 201);
		} else {
			return $this->respond("Erreur lors de la suppression du fil.", 500);
		}
	}
	


}