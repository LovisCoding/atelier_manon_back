<?php

namespace App\Models;

use CodeIgniter\Model;

class FAQModel extends Model
{
    protected $table = 'faq';
    protected $primaryKey = 'idQuestion';

    protected $allowedFields = ['contenue', 'dateQuestion', 'reponse', 'idCli'];

    protected $useTimestamps = false;
    protected $returnType = 'array';
}
