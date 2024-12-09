<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class CodePromoController extends ResourceController
{
    protected $modelName = 'App\Models\CodePromoModel';
    protected $format    = 'json';

	public function getCodesPromo()
	{
		$codesPromo =  $this->model->getCodesPromo();

		return $this->respond($codesPromo);
	}

	public function addCodePromo() 
	{
		$data = $this->request->getJSON();
	
		if (empty($data->code) || !is_string($data->code)) {
			return $this->fail("Le code promo est requis et doit être une chaîne de caractères valide.", 400);
		}
		if (!isset($data->reduc) || !is_numeric($data->reduc) || $data->reduc <= 0) {
			return $this->fail("La réduction est requise et doit être un nombre positif.", 400);
		}
		if (empty($data->type) || !in_array($data->type, ['E', 'P'])) {
			return $this->fail("Le type de réduction est requis et doit être 'pourcentage' ou 'montant'.", 400);
		}
	
		$isAdded = $this->model->addCodePromo($data->code, $data->reduc, $data->type);
	
		if (!$isAdded) {
			return $this->fail("Échec de l'ajout du code promo. Il existe peut-être déjà.", 400);
		}
	
		return $this->respond("Code promo ajouté avec succès.",201);
	}
	

	public function deleteCodePromo() 
	{
		$data = $this->request->getJSON();
	
		if (empty($data->code) || !is_string($data->code)) {
			return $this->fail("Le code promo est requis et doit être une chaîne de caractères valide.", 400);
		}
	
		$isDeleted = $this->model->deleteCodePromo($data->code);
	
		if (!$isDeleted) {
			return $this->fail("Échec de la suppression du code promo. Il n'existe peut-être pas.", 400);
		}
	
		return $this->respond("Code promo supprimé avec succès.",201);
	}
	public function getCodesPromoWithUse()
	{
		$codesPromo =  $this->model->getCodesPromoWithUse();

		return $this->respond($codesPromo);
	}
	public function getCodePromo()
	{
		$code = $this->request->getGet("code");
		$codePromo =  $this->model->getCodePromo($code);

		return $this->respond($codePromo);
	}
	


}