<?php

namespace App\Models;

use CodeIgniter\Model;

class PanierModel extends Model
{
    protected $table = 'panier';
    protected $primaryKey = null;

    protected $allowedFields = ['idProd', 'idCli', 'gravure', 'variante', 'qa'];

    protected $useTimestamps = false;
    protected $returnType = 'array';
}
