<?php

namespace App\Models;

use CodeIgniter\Model;

class CategorieModel extends Model
{
    protected $table = 'Categorie';
    protected $primaryKey = 'idCateg';

    protected $allowedFields = ['libCateg', 'image'];

    protected $useTimestamps = false;
    protected $returnType = 'array';

    public function getCategorie($idCateg)
    {
        return $this->where("idCateg", $idCateg)->first();
    }

    public function getCategorieByLib($libCateg)
    {
        return $this->where("libCateg", $libCateg)->first();
    }

    public function getCategories()
    {
        return $this->findAll();
    }

    public function addCategorie($libCateg)
    {
        $categorie = $this->getCategorieByLib($libCateg);

        if (!$categorie && $this->insert(["libCateg" => $libCateg])) {
            return true;
        }
        return false;
    }

    public function deleteCategorie($idCateg)
    {
        $categorie = $this->getCategorie($idCateg);

        if ($categorie) {
            $this->delete($idCateg);
            return true;
        }

        return false;
    }




    public function updateImage($idCateg, $libImage, $image)
    {
        $categorie = $this->getCategorie($idCateg);

        if ($categorie) 
        {
            $fileName = $libImage . ".webp";
            $filePath = FCPATH . 'images/categories/' . $fileName;

            $photoBase64 = preg_replace('/^data:image\/\w+;base64,/', '', $image);

            $imageData = base64_decode($photoBase64);
            if ($imageData === false) {
                return false;
            }

            $newImage = imagecreatefromstring($imageData);
            if ($newImage === false) {
                return false;
            }

            imagewebp($newImage, $filePath, 80);
            imagedestroy($newImage);

            $this->update($idCateg, [
                "image" => $fileName
            ]);

            return true;
        }
        return false;
    }
}
