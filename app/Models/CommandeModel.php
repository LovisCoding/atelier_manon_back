<?php

namespace App\Models;

use CodeIgniter\Model;

use function PHPUnit\Framework\isEmpty;

class CommandeModel extends Model
{
    protected $table = 'Commande';
    protected $primaryKey = 'idCommande';

    protected $allowedFields = [
        'idCli',
        'dateCommande',
        'comm',
        'estCadeau',
        'carte',
        'dateLivraison',
        'adresse',
        'etat'
    ];

    protected $useTimestamps = false;
    protected $returnType = 'array';


    public function getCommandes($idCli = -1)
    {
        if ($idCli == -1)
            return $this->findAll();
        return $this->where("idCli", $idCli)->findAll();
    }


    public function getProduitsFromCommande($idCommande)
    {
        $commandeProduitModel = new CommandeProduitModel();
        return $commandeProduitModel->getProduitsCommande($idCommande);
    }


    public function getCodesPromo($produit, $idCommande)
    {
        $utilisationCodeModel = new UtilisationCodeModel();
        $utilisationCodes = $utilisationCodeModel->getCodesPromoByCommande($idCommande);

        $promoProduitModel = new PromoProduitModel();

        $codesPromoProduit = [];

        foreach($utilisationCodes as $utilisationCode) {
            $produitsApplyPromo = $promoProduitModel->getProduitsByCode($utilisationCode);
            
            if (in_array($produit, $produitsApplyPromo))
            {
                $codePromoModel = new CodePromoModel();
                $codesPromoProduit[] = $codePromoModel->getCodePromo($utilisationCode);
            }

        }

        return $codesPromoProduit;
    }



    public function getCommande($idCommande)
    {
        $idCli = session()->get("data")["idCli"];

        $commande = $this->where("idCommande", $idCommande)
                         ->where("idCli", $idCli)
                         ->first();

        $prixTotal = 0;
        $prixTotalReduc = 0;
        $tempsLivraisonEstime = 0;

        $produitsCommande = $this->getProduitsFromCommande($idCommande);
        
        foreach ($produitsCommande as $produitCommande) {
            $produit = $produitCommande["produit"];
            $prix = $produit["prix"];
            $tempsRea = $produit["tempsRea"];

            $codesPromo = $this->getCodesPromo($produit, $idCommande);
            $prixReduc = $prix;
            foreach ($codesPromo as $codePromo) 
            {
                if ($codePromo["type"] == "P") {
                    $reduc = (100 - floatval($codePromo["reduc"])) *0.01;
                    $prixReduc *= $reduc;
                }       
                else {
                    $prixReduc -= floatval($codePromo["reduc"]);
                }       
            }

            $qa = intval($produitCommande["qa"]);

            $prixTotal += $prix * $qa;
            $prixTotalReduc += $prixReduc * $qa; 
            $tempsLivraisonEstime += $tempsRea * $qa;

        }

        $utilisationCodeModel = new UtilisationCodeModel();

        $codesPromo = $utilisationCodeModel->getCodesPromoByCommande($idCommande);
        $codesCommande = [];
        foreach ($codesPromo as $codePromo) {
            $codesCommande[] = $codePromo;
        }

        $commande["prixTotal"] = $prixTotal;
        $commande["prixTotalReduc"] = $prixTotalReduc;
        $commande["codesPromo"] = $codesCommande; 
        $commande["tempsLivraisonEstime"] = $tempsLivraisonEstime;

        return $commande;
    }

    public function getClient($idCommande)
    {
        $commande = $this->getCommande($idCommande);

        $compteModel = new CompteModel();
        return $compteModel->getAccountById($commande["idCli"]);
    }

    public function getCommandesByAccount($idCli)
    {
        return $this->where("idCli", $idCli)->findAll();
    }

    public function addCommande($idCli, $dateCommande, $comm, $estCadeau, $carte, $dateLivraison, $codesPromo = [])
    {
        $account = $this->getClient($idCli);

        $adresse = trim($account["adresse"], '{}');

        $this->insert([
            "idCli" => $idCli,
            "dateCommande" => $dateCommande,
            "comm" => $comm,
            "estCadeau" => $estCadeau,
            "carte" => $carte,
            "dateLivraison" => $dateLivraison,
            "adresse" => $adresse,
            "etat" => "Pas commencée"
        ]);

        $newId = $this->getInsertID();

        $panierModel = new PanierModel();
        $produitsPanier = $panierModel->getPaniersFromClient($idCli);

        $commandeProduitModel = new CommandeProduitModel();

        foreach ($produitsPanier as $produitPanier) {
            $commandeProduitModel->addProduitToCommande(
                $produitPanier["idProd"],
                $newId,
                $produitPanier["gravure"],
                $produitPanier["variante"],
                $produitPanier["qa"]
            );
        }

        $utilisationCodeModel = new UtilisationCodeModel();

        foreach ($codesPromo as $codePromo) {
            $utilisationCodeModel->addUtilisationCode($codePromo, $newId);
        }

        return $newId;
    }


    public function deleteCommande($idCommande)
    {
        $commande = $this->getCommande($idCommande);

        if ($commande) {
            $this->delete($idCommande);
            return true;
        }

        return false;
    }

    public function updateEtatCommande($idCommande, $etat) 
    {
        $commande = $this->getCommande($idCommande);

        if ($commande) {
            $this->update($idCommande, ["etat" => $etat]);
            return true;
        }

        return false;
    }
}
