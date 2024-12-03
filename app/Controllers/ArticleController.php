<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class ArticleController extends ResourceController
{
    protected $modelName = 'App\Models\ArticleModel';
    protected $format    = 'json';


	public function getArticles()
	{
		$articles =  $this->model->getArticles();

		return $this->respond($articles);
	}

	public function getArticle()
	{

		$idArticle = $this->request->getGet('idArticle');

		$article =  $this->model->getArticle($idArticle);

        return $this->respond($article);
	}
	
	public function addUpdateArticle() 
	{
		$data = $this->request->getJSON();

		if ($data->idArticle !== -1) 
		{
			$response = $this->model->updateArticle(
				$data->idArticle,
				$data->titreArticle,
				$data->contenu,
				$data->dateArticle
			);
		}
		else
		{
			$response = $this->model->addArticle(
				$data->titreArticle,
				$data->contenu,
				$data->dateArticle
			);
		}


		return $this->respond($response);
	}

	public function deleteArticle() 
	{
		$data = $this->request->getJSON();

		$response = $this->model->deleteArticle(
			intval($data->idArticle)
		);

		return $this->respond($response);
	}
}