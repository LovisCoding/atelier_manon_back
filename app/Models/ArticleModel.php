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
        return $this->findAll();
    }

    public function getArticle($idArticle = -1)
    {    
        if ($idArticle == -1)
            return null;
        return $this->where("idArticle", $idArticle)->first();;
    }

    public function updateArticle($idArticle, $titreArticle, $contenu, $dateArticle)
    {    

        if ($this->update($idArticle, [
            "titreArticle" => $titreArticle,
            "contenu" => $contenu,
            "dateArticle" => $dateArticle
        ])) {
            return "Article modifié avec succès";
        }

        return "Impossible de modifier cet article";
        
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
            return "Article supprimé avec succès.";
        }
        return "Impossible de supprimer cet article.";

    }
}
