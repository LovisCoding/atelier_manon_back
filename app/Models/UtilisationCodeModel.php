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
            $allCommandes = $commandeModel->getCommandesByAccount(intval($account["idCli"]));

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
            return "Ce code a déjà été utilisé par ce compte.";
        }
        
        $utilisateCode = $this->where("idCommande", $idCommande)
            ->where("code", $code)
            ->first();

        if ($this->existsUtilisationCode($code, $idCommande) && !$utilisateCode) {

            $this->db->table($this->table)->insert([
                "idCommande" => $idCommande,
                "code" => $code
            ]);

            return "Code ajouté à la commande avec succès !";
        }

        return "Impossible d'ajouter ce code à cette commande ! (déjà utilisé)";
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

            return "Code supprimé de la commande avec succès !";
        }

        return "Impossible de supprimer ce code de cette commande !";
    }

    public function getCodesPromoByCommande($idCommande)
    {
        $result = $this->where("idCommande", $idCommande)
                       ->select("code")
                       ->get()
                       ->getResultArray();
    
        return array_column($result, 'code');
    }
}
