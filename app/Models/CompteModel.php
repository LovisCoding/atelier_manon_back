<?php

namespace App\Models;

use CodeIgniter\Model;
use DateTime;

class CompteModel extends Model
{
    protected $table = 'Compte';
    protected $primaryKey = 'idCli';

    protected $allowedFields = [
        'email', 
        'mdp', 
        'nomCli', 
        'preCli', 
        'adresse', 
        'token', 
        'token_expiration', 
        'estAdmin', 
        'news',
        'dateSup'
    ];

    public function convertBool($account) {
        $account['estAdmin'] = $account['estAdmin'] == "t";
        $account['news'] = $account['news'] == "t";
        $account['adresseFormat'] = trim($account["adresse"], '{}');
        return $account;
    }

    public function getAccountNewsLetter() 
    {
        return $this->where("news", true)->findAll();
    }

    public function getAccounts() 
    {
        return $this->findAll();
    }


    public function getAccountById($idCli) 
    {
        return $this->convertBool($this->where("idCli", $idCli)->first());
    }

    public function getAccountByEmail($email) 
    {
        return $this->where("email", $email)->first();
    }

    public function getAccountByToken($token) 
    {
        return $this->where('token', $token)
            ->where('token_expiration >=', date('Y-m-d H:i:s'))
            ->first();
    }


    public function createAccount($registrationData)
	{
        $this->insert(
            [
                "email" => $registrationData['email'],
                "mdp" => $registrationData['mdp'],
                "nomCli" => $registrationData['nomCli'],
                "preCli" => $registrationData['prenomCli'],
                "adresse" => $registrationData['adresse'],
            ]
        );

	}

    public function updatePassword($password, $id) 
    {
        $this->update($id, [
            "mdp" => $password,
            "token" => null,
            "token_expiration" => null
        ]);
    }


    public function addNewsLetter($email) {
        $account = $this->getAccountByEmail($email);

        if ($account) {
            $this->update($account["idCli"], [
                "news" => true
            ]);     
            return true;
        }
        return false;
    }

    public function removeNewsLetter($idCli) {
        $account = $this->getAccountById($idCli);

        if ($account) {
            $this->update($idCli, [
                "news" => false
            ]);     
            return true;
        }
        return false;
    }

    public function disableAccount($idCli) {
        $account = $this->getAccountById($idCli);

        if ($account) {
            $this->update($idCli, [
                "dateSup" => (new DateTime())->format("Y-m-d")
            ]);     
            return true;
        }
        return false;
    }

    public function updateNomPrenom($idCli, $nom, $prenom, $adresse) {
        $account = $this->getAccountById($idCli);

        if ($account) {
            $this->update($idCli, [
                "nomCli" => $nom,
                "preCli" => $prenom,
                "adresse" => $adresse
            ]);     
            return true;
        }
        return false;
    }

}
