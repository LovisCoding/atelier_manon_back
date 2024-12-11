<?php

namespace App\Models;

use CodeIgniter\Model;

class UtilisationCodeModel extends Model
{
    protected $table = 'UtilisationCode';
    protected $primaryKey = null;

    protected $allowedFields = ['code', 'idCommande'];

    protected $useTimestamps = false;
    protected $returnType = 'array';

    public function existsUtilisationCode($code, $idCommande)
    {
        $codePromoModel = new CodePromoModel();
        $existCodePromo = $codePromoModel->getCodePromo($code);

        $commandeModel = new CommandeModel();
        $existCommande = $commandeModel->getCommande($idCommande);

        if (!$existCodePromo || !$existCommande) {
            return false;
        }

        return true;
        
    }

    public function codeUseInAnotherCommande($code, $idCommande) 
    {
        $commandeModel = new CommandeModel();

        $account = $commandeModel->getClient($idCommande);

        if ($account) {
            $allCommandes = $commandeModel->getCommandes(intval($account["idCli"]));

            foreach($allCommandes as $commande) {
                $codesPromo = $this->getCodesPromoByCommande(intval($commande["idCommande"]));
                if (in_array($code, $codesPromo)) {
                    return true;
                }
            }
        }

        return false;

    }


    public function addUtilisationCode($code, $idCommande)
    {
        $codeUseInAnotherCommand = $this->codeUseInAnotherCommande($code, $idCommande);

        if ($codeUseInAnotherCommand) {
            return false;
        }
        
        $utilisateCode = $this->where("idCommande", $idCommande)
            ->where("code", $code)
            ->first();

        if ($this->existsUtilisationCode($code, $idCommande) && !$utilisateCode) {

            $this->db->table($this->table)->insert([
                "idCommande" => $idCommande,
                "code" => $code
            ]);

            return true;
        }

        return false;
    }


    public function deleteUtilisationCode($code, $idCommande)
    {
        $utilisateCode = $this->where("idCommande", $idCommande)
            ->where("code", $code)
            ->first();

        if ($this->existsUtilisationCode($code, $idCommande) && $utilisateCode) {
            $this->where("idCommande", $idCommande)
                ->where("code", $code)
                ->delete();

            return true;
        }

        return false;
    }

    public function getCodesPromoByCommande($idCommande)
    {
        $result = $this->where("idCommande", $idCommande)
                       ->select("code")
                       ->get()
                       ->getResultArray();
    
        return array_column($result, 'code');
    }
	public function getCountUtilisationCode($code)
	{
		$utilisateCode = $this->where("code", $code)->countAllResults();
		return $utilisateCode;
	}
}
