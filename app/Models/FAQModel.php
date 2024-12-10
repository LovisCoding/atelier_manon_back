<?php

namespace App\Models;

use CodeIgniter\Model;

class FAQModel extends Model
{
    protected $table = 'FAQ';
    protected $primaryKey = 'idQuestion';

    protected $allowedFields = ['contenu', 'dateQuestion', 'reponse', 'idCli'];

    protected $useTimestamps = false;
    protected $returnType = 'array';

    public function getQuestions() 
    {
        return $this->where('reponse IS NOT NULL')
                    ->where('reponse !=', '')
                    ->findAll();
    }

    public function getQuestionsAdmin() 
    {
        return $this->findAll();
    }
    

    public function getQuestion($idQuestion = -1)
    {    
        if ($idQuestion == -1)
            return null;
        return $this->where("idQuestion", $idQuestion)->first();;
    }

    public function updateQuestion($idQuestion, $contenu, $reponse, $idCli)
    {    
        if ($this->update($idQuestion, [
            "contenu" => $contenu,
            "reponse" => $reponse,
            "idCli" => $idCli
        ])) {
            return true;
        }

        return false;
        
    }


    public function addQuestion($contenu, $dateQuestion, $reponse, $idCli)
    {    
        if($this->insert([
            "contenu" => $contenu,
            "dateQuestion" => $dateQuestion,
            "reponse" => $reponse,
            "idCli" => $idCli
        ])) {
            return $this->getInsertID();
        }
        return -1;
        
    }

    public function deleteQuestion($idQuestion)
    {    
        $question = $this->getQuestion($idQuestion);
        if ($question) 
        {
            $this->delete($idQuestion);
            return true;
        }
        return false;

    }
}
