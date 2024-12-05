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

		$response = $this->model->addCodePromo(
			$data->code, 
			$data->reduc,
			$data->type
		);

		return $this->respond($response);
	}

	public function deleteCodePromo() 
	{
		$data = $this->request->getJSON();

		$response = $this->model->deleteCodePromo($data->code);

		return $this->respond($response);
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