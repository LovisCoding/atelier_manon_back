<?php

namespace App\Models;

use CodeIgniter\Model;

class CommandeModel extends Model
{
    protected $table = 'Commande';
    protected $primaryKey = 'idCommande';

    protected $allowedFields = [
        'idCli',
        'dateCommande',
        'comm',
        'estCadeau',
        'carte',
        'dateLivraison',
        'adresse',
        'etat'
    ];

    protected $useTimestamps = false;
    protected $returnType = 'array';


    public function getCommandes()
    {
        return $this->findAll();
    }

    public function getCommande($idCommande)
    {
        return $this->where("idCommande", $idCommande)->first();
    }

    public function getClient($idCommande)
    {
        $commande = $this->getCommande($idCommande);

        $compteModel = new CompteModel();
        return $compteModel->getAccountById($commande["idCli"]);
    }

    public function getCommandesByAccount($idCli)
    {
        return $this->where("idCli", $idCli)->findAll();
    }

    public function addCommande($idCli, $dateCommande, $comm, $estCadeau, $carte, $dateLivraison)
    {
        $account = $this->getClient($idCli);

        $adresse = trim($account["adresse"], '{}');

        $this->insert([
            "idCli" => $idCli,
            "dateCommande" => $dateCommande,
            "comm" => $comm,
            "estCadeau" => $estCadeau,
            "carte" => $carte,
            "dateLivraison" => $dateLivraison,
            "adresse" => $adresse,
            "etat" => "Pas commencée"
        ]);

        return $this->getInsertID();
    }


    public function deleteCommande($idCommande)
    {
        $commande = $this->getCommande($idCommande);

        if ($commande) {
            $this->delete($idCommande);
            return "Commande supprimée avec succès";
        }

        return "Impossible de supprimer cette commande";
    }

    public function updateEtatCommande($idCommande, $etat) 
    {
        $commande = $this->getCommande($idCommande);

        if ($commande) {
            $this->update($idCommande, ["etat" => $etat]);
            return "L'état de la commande a été modifiée avec succès";
        }

        return "Impossible de modifier l'état de cette commande";
    }
}
