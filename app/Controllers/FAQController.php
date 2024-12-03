<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class FAQController extends ResourceController
{
    protected $modelName = 'App\Models\FAQModel';
    protected $format    = 'json';


	public function getQuestions()
	{
		$questions =  $this->model->getQuestions();

		return $this->respond($questions);
	}

	public function getQuestion()
	{
		$idQuestion = $this->request->getGet('idQuestion');

		$question =  $this->model->getQuestion($idQuestion);

        return $this->respond($question);
	}
	
	public function addUpdateQuestion() 
	{
		$data = $this->request->getJSON();

		if ($data->idQuestion !== -1) 
		{
			$response = $this->model->updateQuestion(
				$data->idQuestion,
				$data->contenu,
				$data->dateQuestion,
				$data->reponse,
				$data->idCli
			);
		}
		else
		{
			$response = $this->model->addQuestion(
				$data->contenu,
				$data->dateQuestion,
				$data->reponse,
				$data->idCli
			);
		}


		return $this->respond($response);
	}

	public function deleteQuestion() 
	{
		$data = $this->request->getJSON();

		$response = $this->model->deleteQuestion(
			intval($data->idQuestion)
		);

		return $this->respond($response);
	}
}