<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\FilModel;

class FilController extends ResourceController
{
    protected $modelName = 'App\Models\FilModel';
    protected $format    = 'json';


	public function addFil() 
	{
		$data = $this->request->getJSON();

		$response = $this->model->addFil($data->libCouleur);

		return $this->respond($response);
	}

	public function deleteFil() 
	{
		$data = $this->request->getJSON();

		$response = $this->model->deleteFil($data->libCouleur);

		return $this->respond($response);
	}

	public function getFils()
	{
		$fils =  $this->model->getFils();

		return $this->respond($fils);
	}

}