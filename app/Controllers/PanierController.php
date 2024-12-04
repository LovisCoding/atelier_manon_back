<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class PanierController extends ResourceController
{
    protected $modelName = 'App\Models\PanierModel';
    protected $format    = 'json';


	public function addProductToPanier() 
	{
		$data = $this->request->getJSON();

		$response = $this->model->addProductToPanier(
			$data->idProd,
			$data->gravure,
			$data->variante,
			$data->idCli
		);

		return $this->respond($response);
	}

	public function reduceProductFromPanier() 
	{
		$data = $this->request->getJSON();

		$response = $this->model->reduceProductFromPanier(
			$data->idProd,
			$data->gravure,
			$data->variante,
			$data->idCli
		);

		return $this->respond($response);
	}


	public function deleteProductFromPanier() 
	{
		$data = $this->request->getJSON();

		$response = $this->model->deleteProductFromPanier(
			$data->idProd,
			$data->gravure,
			$data->variante,
			$data->idCli
		);

		return $this->respond($response);
	}

	public function deletePanierClient()
	{
		$data = $this->request->getJSON();

		$paniers =  $this->model->deletePanierClient($data->idCli);

		return $this->respond($paniers);
	}

	public function getPaniersFromClient()
	{
		$idCli = $this->request->getGet("idCli");

		$paniers =  $this->model->getPaniersFromClient($idCli);

		return $this->respond($paniers);
	}

}