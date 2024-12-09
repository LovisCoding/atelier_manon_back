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
        $idCli = session()->get("data")["idCli"];
    
        if (empty($idCli) || !is_numeric($idCli)) {
            return $this->fail("L'identifiant du client est requis et doit être un entier valide.", 400);
        }
    
        $commandes = $this->model->getCommandes($idCli);
    
        if (empty($commandes)) {
            return $this->failNotFound("Aucune commande trouvée pour le client spécifié.");
        }
    
        return $this->respond($commandes);
    }
    


    public function getCommande()
    {
        $idCommande = $this->request->getGet('idCommande');
    
        if (empty($idCommande) || !is_numeric($idCommande)) {
            return $this->fail("L'identifiant de la commande est requis et doit être un entier valide.", 400);
        }
    
        $commande = $this->model->getCommande($idCommande);
    
        if (empty($commande)) {
            return $this->failNotFound("La commande spécifiée est introuvable.");
        }
    
        return $this->respond($commande);
    }

    
    public function addCommande()
    {
        $data = $this->request->getJSON();

        $idCli = session()->get("data")["idCli"];
    
        if (empty($idCli) || !is_numeric($idCli)) {
            return $this->fail("L'identifiant du client est requis et doit être un entier valide.", 400);
        }
        if (!is_string($data->comm)) {
            return $this->fail("Le commentaire doit être une chaîne de caractères valide.", 400);
        }
        if (!isset($data->estCadeau) || !is_bool($data->estCadeau)) {
            return $this->fail("Le statut de cadeau est requis et doit être un booléen.", 400);
        }

    
        $dateCommande = (new DateTime())->format("d-m-Y");
        $dateLivraison = (new DateTime())->modify("+7 days")->format("d-m-Y");
    
        $response = $this->model->addCommande(
            $idCli,
            $dateCommande,
            $data->comm,
            boolval($data->estCadeau),
            $data->carte,
            $dateLivraison,
            $data->codesPromo ?? []
        );
    
        if (!$response) {
            return $this->fail("Échec de l'ajout de la commande.", 400);
        }
    
        return $this->respond($response, 201);
    }

    public function addSingleProductCommande()
    {
        $data = $this->request->getJSON();

        $idCli = session()->get("data")["idCli"];
    
        if (empty($idCli) || !is_numeric($idCli)) {
            return $this->fail("L'identifiant du client est requis et doit être un entier valide.", 400);
        }

        if (empty($data->variante)) {
            return $this->fail("La variante est requise.", 400);
        }

        if (empty($idProd) || !is_numeric($idProd)) {
            return $this->fail("L'identifiant du produit est requis et doit être un entier valide.", 400);
        }

        $dateCommande = (new DateTime())->format("d-m-Y");
    
        $response = $this->model->addSingleProductCommande(
            $idCli,
            $dateCommande,
            $data->idProd,
            $data->variante
        );

        if (!$response) {
            return $this->respond("Impossible de créer une commande avec ce produit.", 400);
        }
        return $this->respond($response);
    }

    

    public function deleteCommande()
    {
        $data = $this->request->getJSON();
    
        if (empty($data->idCommande) || !is_numeric($data->idCommande)) {
            return $this->fail("L'identifiant de la commande est requis et doit être un entier valide.", 400);
        }
    
        $response = $this->model->deleteCommande($data->idCommande);
    
        if (!$response) {
            return $this->fail("Échec de la suppression de la commande. Elle n'existe peut-être pas.", 400);
        }
    
        return $this->respond("Commande supprimée avec succès.", 201);
    }
    

    public function updateEtatCommande()
    {
        $data = $this->request->getJSON();
    
        if (empty($data->idCommande) || !is_numeric($data->idCommande)) {
            return $this->fail("L'identifiant de la commande est requis et doit être un entier valide.", 400);
        }
        if (empty($data->etat) || !in_array($data->etat, ['en cours', 'expédiée', 'terminée', 'annulée'])) {
            return $this->fail("L'état spécifié est invalide. Les états valides sont : 'en cours', 'expédiée', 'livrée', 'annulée'.", 400);
        }
    
        $response = $this->model->updateEtatCommande($data->idCommande, $data->etat);
    
        if (!$response) {
            return $this->fail("Échec de la mise à jour de l'état de la commande. Elle n'existe peut-être pas.", 400);
        }
    
        return $this->respond("État de la commande mis à jour avec succès.", 201);
    }
    
}
