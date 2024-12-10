<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class PendentifController extends ResourceController
{
	protected $modelName = 'App\Models\PendentifModel';
	protected $format    = 'json';

	public function getPendentifs()
	{
		$pendentifs =  $this->model->getPendentifs();

		return $this->respond($pendentifs);
	}

	public function addPendentif()
	{
		$data = $this->request->getJSON();

		if (empty($data->libPendentif)) {
			return $this->respond("Le champ 'libPendentif' est requis.", 400);
		}

		$response = $this->model->addPendentif($data->libPendentif);

		if ($response) {
			return $this->respond("Le pendentif a été ajouté avec succès.", 201);
		} else {
			return $this->respond("Erreur lors de l'ajout de la pendentif.", 400);
		}
	}

	public function deletePendentif() 
	{
		$data = $this->request->getJSON();
	
		if (empty($data->libPendentif)) {
			return $this->respond("Le champ 'libPendentif' est requis.", 400);
		}
	
		$response = $this->model->deletePendentif($data->libPendentif);
	
		if ($response) {
			return $this->respond("Le pendentif a été supprimé avec succès.", 201);
		} else {
			return $this->respond("Erreur lors de la suppression du pendentif.", 400);
		}
	}
}
