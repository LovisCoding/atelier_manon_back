<?php

namespace App\Models;

use CodeIgniter\Model;

class PierreModel extends Model
{
    protected $table = 'pierre';
    protected $primaryKey = 'libpierre';

    protected $allowedFields = ['libpierre', 'descriptionpierre'];

    protected $useTimestamps = false;
    protected $returnType = 'array';

    public function getPierre($libPierre) {
        return $this->where("libpierre", $libPierre)->first();
    }
}
