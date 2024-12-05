<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use DateTime;

class CommandeController extends ResourceController
{
    protected $modelName = 'App\Models\CommandeModel';
    protected $format    = 'json';

    public function getCommandes()
    {
        $commandes =  $this->model->getCommandes();

		return $this->respond($commandes);
    }

    public function getCommandesByClient()
    {
        $idCli = $this->request->getGet("idCli");
        $commandes =  $this->model->getCommandes($idCli);

		return $this->respond($commandes);
    }


    public function getCommande()
    {
        $idCommande = $this->request->getGet('idCommande');

        $commande = $this->model->getCommande($idCommande);

        return $this->respond($commande);
    }

    public function addCommande()
    {
        $data = $this->request->getJSON();

		$dateCommande = (new DateTime())->format("d-m-Y");

        $dateLivraison = (new DateTime())->modify("+7 days")->format("d-m-Y");

        $response = $this->model->addCommande(
            $data->idCli,
            $dateCommande,
            $data->comm,
            boolval($data->estCadeau),
            $data->carte,
            $dateLivraison
        );

        return $this->respond($response);

    }

    public function deleteCommande() 
    {
        $data = $this->request->getJSON();

        $response = $this->model->deleteCommande($data->idCommande);

        return $this->respond($response);

    }


    public function updateEtatCommande() 
    {
        $data = $this->request->getJSON();

        $idCommande = $data->idCommande;
        $etat = $data->etat;

        $response = $this->model->updateEtatCommande($idCommande, $etat);

        return $this->respond($response);

    }

}
