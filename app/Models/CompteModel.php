<?php

namespace App\Models;

use CodeIgniter\Model;

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
        'news'
    ];

    public function getAccountNewsLetter() 
    {
        return $this->where("news", true)->findAll();
    }


    public function getAccountById($idCli) 
    {
        return $this->where("idCli", $idCli)->first();
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
}
