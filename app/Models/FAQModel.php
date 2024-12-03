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
            return "Question modifiée avec succès";
        }

        return "Impossible de modifier cette question";
        
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
            return "Question supprimée avec succès.";
        }
        return "Impossible de supprimer cette question.";

    }
}
