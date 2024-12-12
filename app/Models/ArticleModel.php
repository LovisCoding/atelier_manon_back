<?php

namespace App\Models;

use CodeIgniter\Model;

class ArticleModel extends Model
{
    protected $table = 'Article';
    protected $primaryKey = 'idArticle';

    protected $allowedFields = ['titreArticle', 'contenu', 'dateArticle'];

    protected $useTimestamps = false;
    protected $returnType = 'array';


    public function getArticles() 
    {
        return $this->orderBy("dateArticle", "DESC")->findAll();
    }

    public function getArticle($idArticle = -1)
    {    
        if ($idArticle == -1)
            return null;
        return $this->where("idArticle", $idArticle)->first();;
    }

    public function updateArticle($idArticle, $titreArticle, $contenu)
    {    

        if ($this->update($idArticle, [
            "titreArticle" => $titreArticle,
            "contenu" => $contenu
        ])) {
            return true;
        }

        return false;
        
    }


    public function addArticle($titreArticle, $contenu, $dateArticle)
    {    
        if($this->insert([
            "titreArticle" => $titreArticle,
            "contenu" => $contenu,
            "dateArticle" => $dateArticle
        ])) {
            return $this->getInsertID();
        }
        return -1;

        
    }

    public function deleteArticle($idArticle)
    {    
        $article = $this->getArticle($idArticle);
        if ($article) 
        {
            $this->delete($idArticle);
            return true;
        }
        return false;

    }
}
