<?php

namespace App\Models;

use CodeIgniter\Model;

class UtilisationCodeModel extends Model
{
    protected $table = 'utilisationcode';
    protected $primaryKey = null;

    protected $allowedFields = ['code', 'idCli'];

    protected $useTimestamps = false;
    protected $returnType = 'array';
}
