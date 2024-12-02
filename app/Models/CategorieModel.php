<?php

namespace App\Models;

use CodeIgniter\Model;

class CategorieModel extends Model
{
    protected $table = 'categorie';
    protected $primaryKey = 'idCateg';

    protected $allowedFields = ['libCateg'];

    protected $useTimestamps = false;
    protected $returnType = 'array';
}
