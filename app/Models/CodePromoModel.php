<?php

namespace App\Models;

use CodeIgniter\Model;

class CodePromoModel extends Model
{
    protected $table = 'CodePromo';
    protected $primaryKey = 'code';
    protected $useAutoIncrement = false;

    protected $allowedFields = ['code', 'reduc', 'type'];

    protected $useTimestamps = false;
    protected $returnType = 'array';

    public function getCodePromo($code)
    {
        return $this->where("code", $code)->first();
    }

    public function getCodesPromo()
    {
        return $this->findAll();
    }

    public function addCodePromo($code, $reduc, $type)
    {
        $codePromo = $this->getCodePromo($code);

        if (!$codePromo && $this->insert([
                "code" => $code, 
                "reduc" => $reduc,
                "type" => $type
            ])) 
        {
            return "Code promo ajouté avec succès.";
        }
        return "Impossible d'ajouter ce code promo.";
    }

    public function deleteCodePromo($code)
    {
        $codePromo = $this->getCodePromo($code);

        if ($codePromo) {
            $this->delete($code);
            return "Code promo supprimé avec succès.";
        }

        return "Impossible de supprimer ce code promo.";
    }
}
