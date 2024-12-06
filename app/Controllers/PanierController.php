<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class PanierController extends ResourceController
{
    protected $modelName = 'App\Models\PanierModel';
    protected $format    = 'json';


	public function addProductToPanier() 
	{
		$data = $this->request->getJSON();

		$idCli = session()->get("data")["idCli"];
	
		if (empty($data->idProd) || !is_numeric($data->idProd)) {
			return $this->respond("L'id du produit est requis et doit être valide.", 400);
		}
	
		if (empty($idCli) || !is_numeric($idCli)) {
			return $this->respond("L'id du client est requis et doit être valide.", 400);
		}
	
		$response = $this->model->addProductToPanier(
			$data->idProd,
			$data->gravure,
			$data->variante,
			$idCli
		);
	
		if ($response) {
			return $this->respond("Le produit a été ajouté au panier.", 201);
		} else {
			return $this->respond("Erreur lors de l'ajout du produit au panier.", 500);
		}
	}
	
	public function reduceProductFromPanier() 
	{
		$data = $this->request->getJSON();

		$idCli = session()->get("data")["idCli"];
	
		if (empty($data->idProd) || !is_numeric($data->idProd)) {
			return $this->respond("L'id du produit est requis et doit être valide.", 400);
		}
	
		if (empty($idCli) || !is_numeric($idCli)) {
			return $this->respond("L'id du client est requis et doit être valide.", 400);
		}
	
		$response = $this->model->reduceProductFromPanier(
			$data->idProd,
			$data->gravure,
			$data->variante,
			$idCli
		);
	
		if ($response) {
			return $this->respond("Le produit a été réduit dans le panier.", 201);
		} else {
			return $this->respond("Erreur lors de la réduction du produit dans le panier.", 500);
		}
	}
	
	public function deleteProductFromPanier() 
	{
		$data = $this->request->getJSON();
		$idCli = session()->get("data")["idCli"];
	
		if (empty($data->idProd) || !is_numeric($data->idProd)) {
			return $this->respond("L'id du produit est requis et doit être valide.", 400);
		}
	
		if (empty($idCli) || !is_numeric($idCli)) {
			return $this->respond("L'id du client est requis et doit être valide.", 400);
		}
	
		$response = $this->model->deleteProductFromPanier(
			$data->idProd,
			$data->gravure,
			$data->variante,
			$idCli
		);
	
		if ($response) {
			return $this->respond("Le produit a été supprimé du panier.", 201);
		} else {
			return $this->respond("Erreur lors de la suppression du produit du panier.", 500);
		}
	}
	
	public function deletePanierClient()
	{
		$data = $this->request->getJSON();
		$idCli = session()->get("data")["idCli"];

		if (empty($idCli) || !is_numeric($idCli)) {
			return $this->respond("L'id du client est requis et doit être valide.", 400);
		}
	
		$response = $this->model->deletePanierClient($idCli);
	
		if ($response) {
			return $this->respond("Le panier a été supprimé.", 201);
		} else {
			return $this->respond("Erreur lors de la suppression du panier du client.", 500);
		}
	}
	
	public function getPaniersFromClient()
	{
		$idCli = session()->get("data")["idCli"];
	
		if (empty($idCli) || !is_numeric($idCli)) {
			return $this->respond("L'id du client est requis et doit être valide.", 400);
		}
	
		$paniers = $this->model->getPaniersFromClient($idCli);
	
		return $this->respond($paniers);
	}
	

}