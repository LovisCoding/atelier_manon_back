<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use DateTime;

class ArticleController extends ResourceController
{
    protected $modelName = 'App\Models\ArticleModel';
    protected $format    = 'json';


	public function getArticles()
	{
		$articles = $this->model->getArticles();

		return $this->respond($articles);
	}

	public function getArticle()
	{
		$idArticle = $this->request->getGet('idArticle');
	
		if (!$idArticle || !is_numeric($idArticle))
			return $this->fail("Le paramètre idArticle est invalide ou manquant.", 400);
	
		$article = $this->model->getArticle($idArticle);
	
		return $this->respond($article);
	}
	
	
	public function addUpdateArticle() 
	{
		$data = $this->request->getJSON();
	
		if (empty($data->titreArticle) || empty($data->contenu)) {
			return $this->fail("Champs obligatoires manquants : 'titreArticle' ou 'contenu'", 400);
		}
	
		if (!isset($data->idArticle)) {
			return $this->fail("Paramètre 'idArticle' manquant", 400);
		}
	
		if ($data->idArticle !== -1) 
		{
			$response = $this->model->updateArticle(
				$data->idArticle,
				$data->titreArticle,
				$data->contenu
			);
	
			if (!$response) {
				return $this->failNotFound("Article avec l'ID {$data->idArticle} introuvable");
			}
			else {
				return $this->respond("Article modifié avec succès.", 201);
			}
		} 
		else 
		{
			$dateArticle = (new DateTime())->format("Y-m-d");
	
			$response = $this->model->addArticle(
				$data->titreArticle,
				$data->contenu,
				$dateArticle
			);
	
			if (!$response) {
				return $this->fail("Échec de l'ajout de l'article", 500);
			}
			else {
				return $this->respond("Article créé avec succès.", 201);
			}
		}
	
	}
	

	public function deleteArticle() 
	{
		$data = $this->request->getJSON();

		if (!isset($data->idArticle))
			return $this->fail("Le paramètre idArticle est invalide ou manquant.", 400);

		$response = $this->model->deleteArticle(
			intval($data->idArticle)
		);

		if ($response)
			$this->respond("Article supprimé avec succès", 201);
		else 
			$this->respond("Impossible de supprimer cet article", 400);

	}
}