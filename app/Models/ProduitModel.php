<?php

namespace App\Models;

use CodeIgniter\Model;

class ProduitModel extends Model
{
    protected $table = 'produit';
    protected $primaryKey = 'idProd';

    protected $allowedFields = [
        'libProd', 
        'descriptionProd', 
        'prix', 
        'estGravable', 
        'tabPhoto', 
        'tempsRea', 
        'idCateg'
    ];

    protected $useTimestamps = false;
    protected $returnType = 'array';

    public function getProduit($idProd = -1)
    {    
        if ($idProd == -1)
            return null;
        return $this->where("idProd", $idProd)->first();;
    }
    

    public function getProduitsPage($page = 1, $nbDisplay = 9)
    {
        // Calculer l'offset (début des éléments à afficher)
        $offset = ($page - 1) * $nbDisplay;
    
        // Récupérer les produits avec limite et décalage
        $produits = $this->asArray()
                         ->orderBy('idProd', 'ASC')
                         ->findAll($nbDisplay, $offset);
    
        return $produits;
    }


}
