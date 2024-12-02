<?php

namespace App\Models;

use CodeIgniter\Model;

class CompteModel extends Model
{
    protected $table = 'compte';
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

    protected $useTimestamps = false;
    protected $returnType = 'array';

    public function getAccountByEmail($email) 
    {
        return $this->where("email", $email)->first();
    }

    public function getAccountByToken($token) 
    {
        $account = $this->where('reset_token', $token)
            ->where('reset_token_expiration >=', date('Y-m-d H:i:s'))
            ->first();
    }


    public function createAccount($registrationData)
	{
        $this->insert(
            [
                "email" => $registrationData['email'],
                "mdp" => $registrationData['mdp'],
                "nomcli" => $registrationData['nomCli'],
                "precli" => $registrationData['prenomCli'],
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
