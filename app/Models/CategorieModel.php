<?php

namespace App\Models;

use CodeIgniter\Model;

class CategorieModel extends Model
{
    protected $table = 'categorie';
    protected $primaryKey = 'idcateg';

    protected $allowedFields = ['libcateg'];

    protected $useTimestamps = false;
    protected $returnType = 'array';

    public function getCategorie($idCateg) {
        return $this->where("idcateg", $idCateg)->first();
    }
    
    public function getCategorieByLib($libCateg) {
        return $this->where("libcateg", $libCateg)->first();
    }
    
    public function getCategories() {
        return $this->findAll();
    }

    public function addCategorie($libCateg) {
        $categorie = $this->getCategorieByLib($libCateg);

        if (!$categorie && $this->insert(["libcateg" => $libCateg])) {
            return "Catégorie ajouté avec succès.";
        }
        return "Impossible d'ajouter ce catégorie.";
    }

    public function deleteCategorie($idCateg) {
        $categorie = $this->getCategorie($idCateg);

        if ($categorie) {
            $this->delete($idCateg);
            return "Catégorie supprimée avec succès.";
        }

        return "Impossible de supprimer cette catégorie.";
    }
}
