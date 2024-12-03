<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class CategorieController extends ResourceController
{
    protected $modelName = 'App\Models\CategorieModel';
    protected $format    = 'json';

	public function getCategories()
	{
		$categories =  $this->model->getCategories();

		return $this->respond($categories);
	}
	
	public function addCategorie() 
	{
		$data = $this->request->getJSON();

		$response = $this->model->addCategorie($data->libCateg);

		return $this->respond($response);
	}

	public function deleteCategorie() 
	{
		$data = $this->request->getJSON();

		$response = $this->model->deleteCategorie(intval($data->idCateg));

		return $this->respond($response);
	}



}