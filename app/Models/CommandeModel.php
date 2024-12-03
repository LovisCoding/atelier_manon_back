<?php

namespace App\Models;

use CodeIgniter\Model;

class CommandeModel extends Model
{
    protected $table = 'commande';
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
}
