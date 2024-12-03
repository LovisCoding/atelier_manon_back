<?php

namespace App\Models;

use CodeIgniter\Model;

class CommandeModel extends Model
{
    protected $table = 'commande';
    protected $primaryKey = 'idcommande';

    protected $allowedFields = [
        'idcli',
        'datecommande',
        'comm',
        'estcadeau',
        'carte',
        'datecivraison',
        'adresse',
        'etat'
    ];

    protected $useTimestamps = false;
    protected $returnType = 'array';

    public function getCommande($idCommande)
    {
        return $this->where("idcommande", $idCommande)->first();
    }

    public function getClient($idCommande)
    {
        $commande = $this->getCommande($idCommande);

        $compteModel = new CompteModel();
        return $compteModel->getAccountById($commande["idcli"]);
    }

    public function getCommandesByAccount($idCli) 
    {
        return $this->where("idcli", $idCli)->findAll();
    }
}
