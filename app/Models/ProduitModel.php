<?php

namespace App\Models;

use CodeIgniter\Model;

class ProduitModel extends Model
{
    protected $table = 'produit';
    protected $primaryKey = 'idprod';

    protected $allowedFields = [
        'libprod', 
        'descriptionprod', 
        'prix', 
        'estgravable', 
        'tabphoto', 
        'tempsrea', 
        'idcateg'
    ];

    protected $useTimestamps = false;
    protected $returnType = 'array';

    public function getProduit($idProd = -1)
    {    
        if ($idProd == -1)
            return null;
        return $this->where("idprod", $idProd)->first();;
    }
    

    public function getProduitsPage($page = 1, $nbDisplay = 9, $search = "", $category = null, $priceInf = 0, $priceSup = null)
    {
        // Calculer l'offset (début des éléments à afficher)
        $offset = ($page - 1) * $nbDisplay;
    
        $query = $this->asArray()->orderBy('idprod', 'ASC');
    
        if (!empty($search)) {
            $query->groupStart()
                  ->like('libprod', $search)
                  ->orLike('descriptionprod', $search)
                  ->groupEnd();
        }
    
        if (!empty($category)) {
            $query->where('idcateg', $category);
        }
    
        $query->where('prix >=', $priceInf);
        
        if (!is_null($priceSup)) {
            $query->where('prix <=', $priceSup);
        }
    
        $query->limit($nbDisplay, $offset);
        $produits = $query->findAll();

        return $produits;
    }
    

    public function updateProduit($idProd, $libProd, $descriptionProd, $prix, $estGravable, $tabPhoto, $tempsRea, $idCateg)
    {    
        $this->update($idProd, [
            "libprod" => $libProd,
            "descriptionprod" => $descriptionProd,
            "prix" => $prix,
            "estgravable" => $estGravable,
            "tabphoto" => $tabPhoto,
            "tempsrea" => $tempsRea,
            "idcated" => $idCateg
        ]);
    }


    public function createProduit($libProd, $descriptionProd, $prix, $estGravable, $tabPhoto, $tempsRea, $idCateg)
    {    
        $existProduct = $this->where("libprod", $libProd)->first();

        if ($existProduct) {
            return -1;
        }

        $this->insert([
            "libprod" => $libProd,
            "descriptionprod" => $descriptionProd,
            "prix" => $prix,
            "estgravable" => $estGravable,
            "tabphoto" => $tabPhoto,
            "tempsrea" => $tempsRea,
            "idcateg" => $idCateg
        ]);

        return $this->getInsertID();
    }
}
