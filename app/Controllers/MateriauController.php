<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\FilModel;

class MateriauController extends ResourceController
{
    protected $modelName = 'App\Models\MateriauModel';
    protected $format    = 'json';


	public function addMateriau() 
	{
		$data = $this->request->getJSON();

		$response = $this->model->addMateriau($data->libMateriau);

		return $this->respond($response);
	}

	public function deleteMateriau() 
	{
		$data = $this->request->getJSON();

		$response = $this->model->deleteMateriau($data->libMateriau);

		return $this->respond($response);
	}

	public function getMateriaux()
	{
		$materiaux =  $this->model->getMateriaux();

		return $this->respond($materiaux);
	}

}