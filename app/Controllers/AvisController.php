<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use DateTime;

class AvisController extends ResourceController
{
    protected $modelName = 'App\Models\AvisModel';
    protected $format    = 'json';


	public function getAllAvis()
	{
		$avis =  $this->model->getAvis();

		return $this->respond($avis);
	}

	public function getAvis()
	{
		$idAvis = $this->request->getGet('idAvis');

		$avis =  $this->model->getAvis($idAvis);

        return $this->respond($avis);
	}
	
	public function addAvis()
	{
		$data = $this->request->getJSON();

		$dateAvis = (new DateTime())->format("d-m-Y");

		$response = $this->model->addAvis(
			$data->contenu,
			$dateAvis,
			$data->note,
			$data->idCli
		);

		return $this->respond($response);
	}

	public function deleteAvis() 
	{
		$data = $this->request->getJSON();

		$response = $this->model->deleteAvis(
			intval($data->idAvis)
		);

		return $this->respond($response);
	}
}