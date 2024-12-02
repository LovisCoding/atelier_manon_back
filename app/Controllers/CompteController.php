<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\CompteModel;

class CompteController extends ResourceController
{
	protected $modelName = 'App\Models\CompteModel';
	protected $format    = 'json';

	public function register()
	{
		$email = $this->request->getPost('email');
		$mdp = $this->request->getPost('mdp');

		$nomCli = $this->request->getPost('nomCli');
		$prenomCli = $this->request->getPost('prenomCli');

		$adresse = $this->request->getPost('adresse');

		$existingUser = $this->model->getAccountByEmail($email);

		if ($existingUser)
			return $this->respond("Un compte avec cet email est déjà créé.");


		$token = bin2hex(random_bytes(16));

		session()->set("registration_$token", [
			'email' => $email,
			'mdp' => password_hash($mdp, PASSWORD_BCRYPT),
			'nomCli' => $nomCli,
			'prenomCli' => $prenomCli,
			'adresse' => $adresse
		]);

		$confirmAccountLink = site_url("email/confirmAccount/$token");

		$emailService = \Config\Services::email();
		$emailService->setTo($email);
		$emailService->setFrom('mail.atelierdemanon@gmail.com', 'L\'Atelier de Manon');
		$emailService->setSubject('Création de votre compte TaskMate !');
		$emailService->setMessage("
			Bonjour $prenomCli $nomCli,
			
			Merci de vous être inscrit à l'Atelier de Manon.
			Pour activer votre compte, cliquez sur le lien suivant :
			$confirmAccountLink
			
			Si vous n'avez pas créé de compte, ignorez cet email.
			
			Cordialement,
			l'Atelier de Manon
		");

		if ($emailService->send()) {
			return $this->respond("Un email a été envoyé pour confirmer votre adresse email.");
		} else {
			return $this->respond("Erreur lors de l'envoi de l'email.");
		}


	}

	public function confirmAccount()
	{
		$token = $this->request->getPost("token");
		$registrationData = session()->get("registration_$token");

		if (!$registrationData) {
			$this->respond("Token invalide.");
		}

		// Enregistrer les données dans la base de données
		$this->model->createAccount($registrationData);

		session()->remove("registration_$token");

		return $this->respond("Votre compte a bien été crée.");
	}

	public function connection()
	{
		$session = session();
		$email = $this->request->getPost('email');
		$mdpInput = $this->request->getPost('mdp');
		$account = $this->model->getAccountByEmail($email);

		if ($account) {
			$mdp = $account['mdp'];
			$authenticatePassword = password_verify($mdpInput, $mdp);

			if ($authenticatePassword) {
				$ses_data = [
					'idCli' => $account['idCli'],
					'isLoggedIn' => true,
				];
				$session->set($ses_data);

				return $this->respond("Connexion au compte...");
			} else {
				return $this->respond("Mot de passe incorect.");
			}
		} else {
			return $this->respond("Email incorect.");
		}
	}

	public function forgotPassword() 
	{
		$email = $this->request->getPost('email');
		$account = $this->model->getAccountByEmail($email);

		if ($account) {

			$token = bin2hex(random_bytes(16));
			session()->set("updatePassword_$token", $token);

			$expiration = date('Y-m-d H:i:s', strtotime('+1 hour'));

			$account->set('token', $token)
				->set('token_expiration', $expiration)
				->update($account['id']);

			$resetLink = site_url("/forgot-password/reset-password/$token");
			$message = "Cliquez sur le lien suivant pour réinitialiser votre mot de passe: $resetLink";

			$emailService = \Config\Services::email();


			$emailService->setTo($email);
			$emailService->setFrom('mail.atelierdemanon@gmail.com', 'L\'Atelier de Manon');
			$emailService->setSubject('Réinitialisation de mot de passe');
			$emailService->setMessage($message);

			if ($emailService->send()) {
				return $this->respond("Un mail vient de vous être envoyé. Veuillez accéder au lien fourni.");
			} else {
				return $this->respond("Échec de l\'envoi de l\'email. Veuillez réessayer.");
			}
		} else {
			return $this->respond("L\'adresse email fournie est invalide.");
		}
	}

	public function resetPassword()
	{
		$token = $this->request->getPost("token");

		if (!session()->get("updatePassword_$token")) {
			return $this->respond("Token invalide.");
		}
	
		return $this->respond("Passage à la vue pour reset");
	}

	public function updatePassword()
	{
		$token = $this->request->getPost('token');
	
		$password = $this->request->getPost('password');
		$confirmPassword = $this->request->getPost('confirm_password');
	
		$account = $this->model->getAccountByToken($token);
	
		if ($account && $password === $confirmPassword) 
		{
			$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
			$this->model->updatePassword($hashedPassword, $account["idCli"]);
			return $this->respond("Mot de passe réinitialisé avec succès.");
		} 
		else 
		{
			return $this->respond("Erreur lors de la réinitialisation du mot de passe.");
		}
	}

}
