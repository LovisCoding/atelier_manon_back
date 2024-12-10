<?php

namespace App\Controllers;

use App\Models\CompteModel;
use CodeIgniter\RESTful\ResourceController;
use DateTime;

class FAQController extends ResourceController
{
	protected $modelName = 'App\Models\FAQModel';
	protected $format    = 'json';


	public function getQuestions()
	{
		$questions =  $this->model->getQuestions();

		return $this->respond($questions);
	}

	public function getQuestionsAdmin()
	{
		$questions =  $this->model->getQuestionsAdmin();

		return $this->respond($questions);
	}

	public function getQuestion()
	{
		$idQuestion = $this->request->getGet('idQuestion');

		if (!$idQuestion || !is_numeric($idQuestion)) {
			return $this->respond("ID de question invalide.", 400);
		}

		$question =  $this->model->getQuestion($idQuestion);

		if (!$question) {
			return $this->respond("Aucune question trouvée avec cet ID.", 404);
		}

		return $this->respond($question);
	}

	public function addUpdateQuestion()
	{
		$data = $this->request->getJSON();

		$idCli = session()->get("data")["idCli"];

		$compteModel = new CompteModel();
		$account = $compteModel->getAccountById($idCli);

		if ($account) {

			if ($account["estAdmin"] === "f" && !empty($data->reponse)) {
				return $this->respond("Impossible d'ajouter une réponse si vous n'êtes pas admin.", 400);
			}

			if (empty($data->contenu) || empty($idCli)) {
				return $this->respond("Les champs 'contenu', et 'idCli' sont requis.", 400);
			}

			if ($data->idQuestion !== -1) {
				$response = $this->model->updateQuestion(
					$data->idQuestion,
					$data->contenu,
					$data->reponse,
					$idCli
				);

				if ($response) {
					return $this->respond("Question mise à jour avec succès.", 201);
				} else {
					return $this->respond("Erreur lors de la mise à jour de la question.", 500);
				}
			} else {
				$dateQuestion = (new DateTime())->format("d-m-Y");

				$response = $this->model->addQuestion(
					$data->contenu,
					$dateQuestion,
					$data->reponse,
					$idCli
				);

				if ($response !== -1) {
					return $this->respond($response, 201);
				} else {
					return $this->respond("Erreur lors de l'ajout de la question.", 500);
				}
			}
		}

		return $this->respond("Erreur, pas de compte connecté.", 500);

	}

	public function deleteQuestion()
	{
		$data = $this->request->getJSON();

		if (empty($data->idQuestion)) {
			return $this->respond("L'ID de la question est requis pour la suppression.", 400);
		}

		$response = $this->model->deleteQuestion(intval($data->idQuestion));

		if ($response) {
			return $this->respond("Question supprimée avec succès.", 201);
		} else {
			return $this->respond("Erreur lors de la suppression de la question.", 500);
		}
	}
}
