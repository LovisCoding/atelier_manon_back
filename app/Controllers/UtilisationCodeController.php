<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class UtilisationCodeController extends ResourceController
{
	protected $modelName = 'App\Models\UtilisationCodeModel';
	protected $format    = 'json';

	public function getCodesPromoByCommande()
	{
		$idCommande = $this->request->getGet("idCommande");

		if (empty($idCommande) || !is_numeric($idCommande)) {
			return $this->respond("L'ID de la commande est requis et doit être un nombre valide.", 400);
		}

		$codesPromo = $this->model->getCodesPromoByCommande($idCommande);

		return $this->respond($codesPromo);
	}

	public function addUtilisationCode()
	{
		$data = $this->request->getJSON();

		if (empty($data->code) || empty($data->idCommande)) {
			return $this->respond("Le code et l'ID de la commande sont requis.", 400);
		}

		if (!is_string($data->code) || !is_numeric($data->idCommande)) {
			return $this->respond("Le code doit être une chaîne de caractères et l'ID de la commande doit être un nombre.", 400);
		}

		$response = $this->model->addUtilisationCode($data->code, $data->idCommande);

		if (!$response) {
			return $this->respond("Impossible d'ajouter l'utilisation du code pour cette commande.", 500);
		}

		return $this->respond("Utilisation du code ajoutée avec succès.", 201);
	}

	public function deleteUtilisationCode()
	{
		$data = $this->request->getJSON();

		if (empty($data->code) || empty($data->idCommande)) {
			return $this->respond("Le code et l'ID de la commande sont requis.", 400);
		}

		if (!is_string($data->code) || !is_numeric($data->idCommande)) {
			return $this->respond("Le code doit être une chaîne de caractères et l'ID de la commande doit être un nombre.", 400);
		}

		$response = $this->model->deleteUtilisationCode($data->code, $data->idCommande);

		if (!$response) {
			return $this->respond("Impossible de supprimer l'utilisation du code pour cette commande.", 500); 
		}

		return $this->respond("Utilisation du code supprimée avec succès.", 201);
	}
}
