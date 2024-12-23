<?php

namespace App\Models;

use CodeIgniter\Model;

class PromoProduitModel extends Model
{
    protected $table = 'PromoProduit';
    protected $primaryKey = null;

    protected $allowedFields = ['code', 'idProd'];

    protected $useTimestamps = false;
    protected $returnType = 'array';

    public function existsPromoProduit($code, $idProd)
    {
        $produitModel = new ProduitModel();
        $existProd = $produitModel->getProduit($idProd);

        $codePromoModel = new CodePromoModel();
        $existCodePromo = $codePromoModel->getCodePromo($code);

        if (!$existProd || !$existCodePromo) {
            return false;
        }

        return true;
    }


    public function addPromoProduit($code, $idProd)
    {
        $promoProduit = $this->where("idProd", $idProd)
            ->where("code", $code)
            ->first();

        if ($this->existsPromoProduit($code, $idProd) && !$promoProduit) {

            $this->db->table($this->table)->insert([
                "idProd" => $idProd,
                "code" => $code
            ]);

            return true;
        }

        return false;
    }


    public function deletePromoProduit($code, $idProd)
    {
        $promoProduit = $this->where("idProd", $idProd)
            ->where("code", $code)
            ->first();

        if ($this->existsPromoProduit($code, $idProd) && $promoProduit) {
            $this->where("idProd", $idProd)
                ->where("code", $code)
                ->delete();

            return true;
        }

        return false;
    }

    public function getProduitsByCode($code) {
        $result = $this->where("code", $code)
                       ->select("idProd")
                       ->get()
                       ->getResultArray();
    
        $produits = []; 
    
        if (!empty($result)) {
            $produitModel = new ProduitModel();
    
            foreach ($result as $row) {
                $idProd = $row['idProd'];
                $produit = $produitModel->getProduit($idProd);
                
                if ($produit) {
                    $produits[] = $produit;
                }
            }
        }
    
        return $produits;
    }
    
}
