<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\MatProdModel;

class MatProdController extends ResourceController
{
    protected $modelName = 'App\Models\MatProdModel';
    protected $format    = 'json';


	public function addMatProd() 
	{
		$data = $this->request->getJSON();

		$idProd = $data->idProd;
		$libMat = $data->libMat;

		$response = $this->model->addMatProd($idProd, $libMat);

		return $this->respond($response);
	}

	public function deleteMatProd() 
	{
		$data = $this->request->getJSON();

		$idProd = $data->idProd;
		$libMat = $data->libMat;

		$response = $this->model->deleteMatProd($idProd, $libMat);

		return $this->respond($response);
	}

	public function getMateriauxProduit() 
	{
		$idProd = $this->request->getGet("idProd");

		$materiaux =  $this->model->getMateriauxProduit($idProd);

		return $this->respond($materiaux);
	}


}