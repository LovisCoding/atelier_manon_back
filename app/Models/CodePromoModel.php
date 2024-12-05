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
            return true;
        }
        return false;
    }

    public function deleteCodePromo($code)
    {
        $codePromo = $this->getCodePromo($code);

        if ($codePromo) {
            $this->delete($code);
            return true;
        }

        return false;
    }
	public function getCodesPromoWithUse() {
		$codesPromo = $this->findAll();
		$utilisationCodeModel = new UtilisationCodeModel();
		$utilisationCodes = $utilisationCodeModel->findAll();
		$codesPromoWithUse = [];
		foreach($codesPromo as $codePromo) {
			$use = 0;
			foreach($utilisationCodes as $utilisationCode) {
				if ($utilisationCode["code"] == $codePromo["code"]) {
					$use++;
				}
			}
			$codePromo["use"] = $use;
			$codesPromoWithUse[] = $codePromo;
		}
		
		return $codesPromoWithUse;
	}
}
