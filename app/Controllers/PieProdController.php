<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\PieProdModel;

class PieProdController extends ResourceController
{
    protected $modelName = 'App\Models\PieProdModel';
    protected $format    = 'json';


	public function addPieProd() 
	{
		$data = $this->request->getJSON();

		$idProd = $data->idProd;
		$libPierre = $data->libPierre;

		$response = $this->model->addPieProd($idProd, $libPierre);

		return $this->respond($response);
	}

	public function deletePieProd() 
	{
		$data = $this->request->getJSON();

		$idProd = $data->idProd;
		$libPierre = $data->libPierre;

		$response = $this->model->deletePieProd($idProd, $libPierre);

		return $this->respond($response);
	}

	public function getPierresProduit()
	{
		$idProd = $this->request->getGet("idProd");

		$pierres =  $this->model->getPierresProduit($idProd);

		return $this->respond($pierres);
	}

}