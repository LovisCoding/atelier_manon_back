<?php

namespace App\Models;

use CodeIgniter\Model;

class CommandeProduitModel extends Model
{
    protected $table = 'commandeproduit';
    protected $primaryKey = null;

    protected $allowedFields = ['idProd', 'idCommande', 'gravure', 'variante', 'qa'];

    protected $useTimestamps = false;
    protected $returnType = 'array';
}
