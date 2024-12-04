<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class PromoProduitController extends ResourceController
{
    protected $modelName = 'App\Models\PromoProduitModel';
    protected $format    = 'json';


	public function addPromoProduit() 
	{
		$data = $this->request->getJSON();

		$idProd = $data->idProd;
		$code = $data->code;

		$response = $this->model->addPromoProduit($code, $idProd);

		return $this->respond($response);
	}

	public function deletePromoProduit() 
	{
		$data = $this->request->getJSON();

		$idProd = $data->idProd;
		$code = $data->code;

		$response = $this->model->deletePromoProduit($code, $idProd);

		return $this->respond($response);
	}

	public function getProduitsByCode()
	{
		$code = $this->request->getGet("code");

		$produits =  $this->model->getProduitsByCode($code);

		return $this->respond($produits);
	}

}