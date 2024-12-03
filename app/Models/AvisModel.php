<?php

namespace App\Models;

use CodeIgniter\Model;

class AvisModel extends Model
{
    protected $table = 'avis';
    protected $primaryKey = 'idAvis';

    protected $allowedFields = ['contenue', 'dateAvis', 'note', 'idCli'];

    protected $useTimestamps = false;
    protected $returnType = 'array';
}
