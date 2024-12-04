<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\FilProdModel;

class PierreController extends ResourceController
{
    protected $modelName = 'App\Models\PierreModel';
    protected $format    = 'json';


	public function addPierre() 
	{
		$data = $this->request->getJSON();

		$response = $this->model->addPierre($data->libPierre, $data->descriptionPierre);

		return $this->respond($response);
	}

	public function deletePierre() 
	{
		$data = $this->request->getJSON();

		$response = $this->model->deletePierre($data->libPierre);

		return $this->respond($response);
	}

	public function getPierres()
	{
		$pierres =  $this->model->getPierres();

		return $this->respond($pierres);
	}

}