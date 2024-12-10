<?php

namespace App\Models;

use CodeIgniter\Model;

class AvisModel extends Model
{
    protected $table = 'Avis';
    protected $primaryKey = 'idAvis';

    protected $allowedFields = ['contenu', 'dateAvis', 'note', 'idCli', 'estAffiche'];

    protected $useTimestamps = false;
    protected $returnType = 'array';

    public function getAccount($idCli = -1) 
    {
        if ($idCli == -1)
            return null;

        $accountModel = new CompteModel();
        return $accountModel->getAccountById($idCli);

    }

    public function updateAvisDisplay($estAffiche, $idAvis) 
    {
        $avis = $this->getAvis($idAvis);
        if ($avis) 
        {
            $this->update($idAvis, [
                "estAffiche" => $estAffiche
            ]);
            return true;
        }
        return false;
    }

    public function getAllAvisToDisplay() 
    {
        return $this->where("estAffiche", "t")->findAll();
    }

    public function getAvis($idAvis = -1) 
    {
        if ($idAvis == -1) 
        {
            $allAvis = $this->findAll();
            $newAllAvis = [];
            foreach ($allAvis as $avis) {
                $avis["compte"] = $this->getAccount($avis["idCli"]);
                $avis["estAffiche"] = $avis["estAffiche"] == "t";
                $newAllAvis[] = $avis;
            }
            return $newAllAvis;

        }

        $avis = $this->where("idAvis", $idAvis)->first();
        $avis["estAffiche"] = $avis["estAffiche"] == "t";
        return $avis;
    }

    public function getAvisByClient($idCli) 
    {
        return $this->where("idCli", $idCli)->first();
    }



    public function addAvis($contenu, $dateAvis, $note, $idCli)
    {    
        $account = $this->getAccount($idCli);
        if (!$account) 
            return false;
        
        $avis = $this->getAvisByClient($idCli);

        if ($avis)
            return false;

        if($this->insert([
            "contenu" => $contenu,
            "dateAvis" => $dateAvis,
            "note" => $note,
            "idCli" => $idCli,
            'estAffiche' => false
        ])) {
            return $this->getInsertID();
        }
        return -1;
        
    }

    public function deleteAvis($idAvis)
    {    
        $avis = $this->getAvis($idAvis);
        if ($avis) 
        {
            $this->delete($idAvis);
            return true;
        }
        return false;

    }
}
