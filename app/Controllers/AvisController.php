<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use DateTime;

class AvisController extends ResourceController
{
    protected $modelName = 'App\Models\AvisModel';
    protected $format    = 'json';


	public function getAllAvis()
	{
		$avis =  $this->model->getAvis();

		return $this->respond($avis);
	}

	public function getAvis()
	{
		$idAvis = $this->request->getGet('idAvis');

		if (!$idAvis || !is_numeric($idAvis))
			return $this->fail("Le paramètre idAvis est invalide ou manquant.", 400);

		$avis = $this->model->getAvis($idAvis);

        return $this->respond($avis);
	}

	public function addAvis()
	{
		$data = $this->request->getJSON();
	
		if (empty($data->contenu) || !isset($data->note) || !isset($data->idCli)) {
			return $this->fail("Champs obligatoires manquants : 'contenu', 'note' ou 'idCli'", 400);
		}
	
		if ($data->note < 0 || $data->note > 5) {
			return $this->fail("La note doit être comprise entre 0 et 5", 400);
		}
	
		$dateAvis = (new DateTime())->format("d-m-Y");
	
		$response = $this->model->addAvis(
			$data->contenu,
			$dateAvis,
			$data->note,
			$data->idCli
		);
	
		if (!$response || $response == -1) {
			return $this->fail("Impossible d'ajouter cet avis (client inconnu ou avis déjà posté).", 400);
		}
	
		return $this->respond($response, 201);
	}
	

	public function deleteAvis()
	{
		$data = $this->request->getJSON();
	
		if (empty($data->idAvis)) {
			return $this->fail("L'identifiant de l'avis est requis.", 400);
		}
	
		$response = $this->model->deleteAvis(intval($data->idAvis));
	
		if (!$response) {
			return $this->fail("Échec de la suppression de l'avis ou avis introuvable.", 404);
		}
	
		return $this->respond("Avis supprimé avec succès.", 201);
	}
	
}