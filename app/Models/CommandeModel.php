<?php

namespace App\Models;

use CodeIgniter\Model;

use function PHPUnit\Framework\isEmpty;
use DateTime;

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
        if ($idCli == -1) {
            $commandes =  $this->orderBy("idCommande")->findAll();
            $newCommandes = [];

            foreach ($commandes as $commande) {
                $newCommandes[] = $this->calculCommande($commande);
            }

            return $newCommandes;
        }

        return $this->where("idCli", $idCli)->orderBy("idCommande", "DESC")->findAll();
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

        foreach ($utilisationCodes as $utilisationCode) {
            $produitsApplyPromo = $promoProduitModel->getProduitsByCode($utilisationCode);

            foreach ($produitsApplyPromo as $produitApplyPromo) {
                if (intval($produitApplyPromo["idProd"]) == $produit) {
                    $codePromoModel = new CodePromoModel();
                    $codesPromoProduit[] = $codePromoModel->getCodePromo($utilisationCode);
                }
            }
        }

        return $codesPromoProduit;
    }

    public function calculCommande($commande)
    {
        if ($commande == null)
            return $commande;

        $prixTotal = 0;
        $prixTotalReduc = 0;
        $tempsLivraisonEstime = 0;

        $produitsCommande = $this->getProduitsFromCommande($commande["idCommande"]);

        foreach ($produitsCommande as $produitCommande) {
            $produit = $produitCommande["produit"];
            $prix = $produit["prix"];
            $tempsRea = $produit["tempsRea"];

            $codesPromo = $this->getCodesPromo(intval($produit["idProd"]), intval($commande["idCommande"]));
            $prixReduc = $prix;
            foreach ($codesPromo as $codePromo) {
                if ($codePromo["type"] == "P") {
                    $reduc = (100 - floatval($codePromo["reduc"])) * 0.01;
                    $prixReduc *= $reduc;
                    $commande["reduc"] = $reduc;

                } else {
                    $prixReduc -= floatval($codePromo["reduc"]);
                    $commande["reduc"] = $codePromo["reduc"];
                }
            }
            $qa = intval($produitCommande["qa"]);

            $prixTotal += $prix * $qa;
            $prixTotalReduc += $prixReduc * $qa;
            $tempsLivraisonEstime += $tempsRea * $qa;
        }

        $utilisationCodeModel = new UtilisationCodeModel();

        $codesPromo = $utilisationCodeModel->getCodesPromoByCommande($commande["idCommande"]);
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

    public function getCommande($idCommande)
    {
        $idCli = session()->get("data")["idCli"];

        $compteModel = new CompteModel();
        $account = $compteModel->getAccountById($idCli);

        if ($account) {
            $estAdmin = $account["estAdmin"] == "t";

            if ($estAdmin) {
                $commande = $this->where("idCommande", $idCommande)
                    ->first();
            } else {
                $commande = $this->where("idCommande", $idCommande)
                    ->where("idCli", $idCli)
                    ->first();
            }

            return $this->calculCommande($commande);
        }

        return null;
    }

    public function getClient($idCommande)
    {
        $commande = $this->getCommande($idCommande);

        $compteModel = new CompteModel();
        return $compteModel->getAccountById($commande["idCli"]);
    }


    public function addCommande($idCli, $dateCommande, $comm, $estCadeau, $carte, $codesPromo = [])
    {
        $accountModel = new CompteModel();
        $account = $accountModel->getAccountById($idCli);

        $adresse = trim($account["adresse"], '{}');

        $this->insert([
            "idCli" => $idCli,
            "dateCommande" => $dateCommande,
            "comm" => $comm,
            "estCadeau" => $estCadeau,
            "dateLivraison" => $dateCommande,
            "carte" => $carte,
            "adresse" => $adresse,
            "etat" => "pas commencée"
        ]);

        $newId = $this->getInsertID();

        $panierModel = new PanierModel();
        $produitsPanier = $panierModel->getPaniersFromClient($idCli);

        $commandeProduitModel = new CommandeProduitModel();
        $tempsRea = 0;

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

        $newCommande = $this->getCommande($newId);
        $tempsRea = intval($newCommande["tempsLivraisonEstime"])
        ;
        $dateLivraison = (new DateTime())->modify("+$tempsRea days")->format("d-m-Y");

        $this->update($newId, [
            "dateLivraison" => $dateLivraison
        ]);

        $panierModel = new PanierModel();
        $panierModel->deletePanierClient($idCli);

        return $newId;
    }

    public function addSingleProductCommande($idCli, $dateCommande, $idProd, $variante, $gravure)
    {

        $accountModel = new CompteModel();
        $account = $accountModel->getAccountById($idCli);

        $adresse = trim($account["adresse"], '{}');

        $produitModel = new ProduitModel();
        $produit = $produitModel->getProduit($idProd);

        if ($produit) {
            $tempsRea = intval($produit["tempsRea"]) + 2;
            $dateLivraison = (new DateTime())->modify("+$tempsRea days")->format("d-m-Y");

            $response = $this->insert([
                "idCli" => $idCli,
                "dateCommande" => $dateCommande,
                "dateLivraison" => $dateLivraison,
                "adresse" => $adresse,
                "etat" => "pas commencée"
            ]);
            if (!$response) {
                return false;
            }

            $newId =  $this->getInsertID();


            $commandeProduitModel = new CommandeProduitModel();
            $response = $commandeProduitModel->addProduitToCommande($idProd, $newId, $gravure, $variante, 1);

            if (!$response) {
                return false;
            }

            return $newId;
        }
        return false;
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
