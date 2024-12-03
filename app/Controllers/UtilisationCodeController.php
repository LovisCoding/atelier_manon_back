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
		$codesPromo =  $this->model->getCodesPromoByCommande($idCommande);

		return $this->respond($codesPromo);
	}

	public function addUtilisationCode() 
	{
		$data = $this->request->getJSON();

		$response = $this->model->addUtilisationCode(
			$data->code, 
			$data->idCommande,
		);

		return $this->respond($response);
	}

	public function deleteUtilisationCode() 
	{
		$data = $this->request->getJSON();

		$response = $this->model->deleteUtilisationCode($data->code, $data->idCommande);

		return $this->respond($response);
	}



}