<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\FilModel;

class TailleController extends ResourceController
{
	protected $modelName = 'App\Models\TailleModel';
	protected $format    = 'json';

	public function getTailles()
	{
		$tailles =  $this->model->getTailles();

		return $this->respond($tailles);
	}

	public function addTaille()
	{
		$data = $this->request->getJSON();

		if (empty($data->libTaille)) {
			return $this->respond("Le champ 'libTaille' est requis.", 400);
		}

		$response = $this->model->addTaille($data->libTaille);

		if ($response) {
			return $this->respond("La taille a été ajouté avec succès.", 201);
		} else {
			return $this->respond("Erreur lors de l'ajout de la taille.", 400);
		}
	}

	public function deleteTaille() 
	{
		$data = $this->request->getJSON();
	
		if (empty($data->libTaille)) {
			return $this->respond("Le champ 'libTaille' est requis.", 400);
		}
	
		$response = $this->model->deleteTaille($data->libTaille);
	
		if ($response) {
			return $this->respond("La taille a été supprimée avec succès.", 201);
		} else {
			return $this->respond("Erreur lors de la suppression de la taille.", 400);
		}
	}
}
