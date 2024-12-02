<?php

namespace App\Models;

use CodeIgniter\Model;

class PromoProduitModel extends Model
{
    protected $table = 'promoproduit';
    protected $primaryKey = null;

    protected $allowedFields = ['code', 'idProd'];

    protected $useTimestamps = false;
    protected $returnType = 'array';
}
