<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\FilProdModel;

class FilProdController extends ResourceController
{
    protected $modelName = 'App\Models\FilProdModel';
    protected $format    = 'json';


	public function addFilProd() 
	{
		$data = $this->request->getJSON();

		$idProd = intval($data->idProd);
		$libCouleur = $data->libCouleur;

		$response = $this->model->addFilProd($idProd, $libCouleur);

		return $this->respond($response);
	}

	public function deleteFilProd() 
	{
		$data = $this->request->getJSON();

		$idProd = $data->idProd;
		$libCouleur = $data->libCouleur;

		$response = $this->model->deleteFilProd($idProd, $libCouleur);

		return $this->respond($response);
	}

	public function getFilsProduit()
	{
		$idProd = $this->request->getGet("idProd");

		$fils =  $this->model->getFilsProduit($idProd);

		return $this->respond($fils);

	}

}