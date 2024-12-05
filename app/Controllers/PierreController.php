<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\FilProdModel;

class PierreController extends ResourceController
{
	protected $modelName = 'App\Models\PierreModel';
	protected $format    = 'json';

	public function getPierres()
	{
		$pierres =  $this->model->getPierres();

		return $this->respond($pierres);
	}

	public function addPierre()
	{
		$data = $this->request->getJSON();

		if (empty($data->libPierre)) {
			return $this->respond("Le libellé de la pierre est requis.", 400);
		}

		if (empty($data->descriptionPierre)) {
			return $this->respond("La description de la pierre est requise.", 400);
		}

		$response = $this->model->addPierre($data->libPierre, $data->descriptionPierre);

		if ($response) {
			return $this->respond("La pierre a été ajoutée avec succès.", 201);
		} else {
			return $this->respond("Erreur lors de l'ajout de la pierre.", 500);
		}
	}

	public function deletePierre()
	{
		$data = $this->request->getJSON();

		if (empty($data->libPierre)) {
			return $this->respond("Le libellé de la pierre est requis.", 400);
		}

		$response = $this->model->deletePierre($data->libPierre);

		if ($response) {
			return $this->respond("La pierre a été supprimée avec succès.", 201);
		} else {
			return $this->respond("Erreur lors de la suppression de la pierre. Assurez-vous que la pierre existe.", 500);
		}
	}
}
