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
	
		if (empty($data->libCateg)) {
			return $this->fail("Le libellé de la catégorie est requis.", 400);
		}
	
		$response = $this->model->addCategorie($data->libCateg);
	
		if (!$response) {
			return $this->fail("Échec de l'ajout de la catégorie.", 400);
		}
	
		return $this->respond("Catégorie ajoutée avec succès", 201);
	}
	

	public function deleteCategorie() 
	{
		$data = $this->request->getJSON();
	
		if (empty($data->idCateg) || !is_numeric($data->idCateg)) {
			return $this->fail("L'ID de la catégorie est requis et doit être un nombre valide.", 400);
		}
	
		$isDeleted = $this->model->deleteCategorie(intval($data->idCateg));
	
		if (!$isDeleted) {
			return $this->fail("Échec de la suppression de la catégorie. Elle n'existe peut-être pas ou est utilisée ailleurs.", 500);
		}
	
		return $this->respond("Catégorie supprimée avec succès.",201);
	}
	


	public function updateImage() 
	{
		$data = $this->request->getJSON();
	
		if (empty($data->idCateg) || !is_numeric($data->idCateg)) {
			return $this->respond("L'ID de la catégorie est requis et doit être un nombre valide.", 400);
		}

		if (empty($data->libImage) || empty($data->image)) {
			return $this->respond("L'image et son nom sont requis.", 400);
		}

		$response = $this->model->updateImage($data->idCateg, $data->libImage, $data->image);

		if (!$response) {
            return $this->respond("Impossible d'enregistrer cette image. (mauvais format)", 400);
		}
		return $this->respond("Image modifiée avec succès.", 201);

		
	}

}