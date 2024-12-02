<?php

namespace App\Models;

use CodeIgniter\Model;

class CodePromoModel extends Model
{
    protected $table = 'codepromo';
    protected $primaryKey = 'code';

    protected $allowedFields = ['code', 'reduc', 'type'];

    protected $useTimestamps = false;
    protected $returnType = 'array';
}
