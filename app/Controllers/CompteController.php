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
		$data = $this->request->getJSON();

		$existingUser = $this->model->getAccountByEmail($data->email);

		if (is_array($data->adresse)) {
			$adresse = '{' . implode(',', array_map(fn($item) => "\"$item\"", $data->adresse)) . '}';
		}

		if ($existingUser)
			return $this->respond("Un compte avec cet email est déjà créé.");


		$token = bin2hex(random_bytes(16));

		session()->set("registration_$token", [
			'email' => $data->email,
			'mdp' => password_hash($data->mdp, PASSWORD_BCRYPT),
			'nomCli' => $data->nomCli,
			'prenomCli' => $data->prenomCli,
			'adresse' => $adresse
		]);

		$confirmAccountLink = site_url("email/confirmAccount/$token");

		$emailService = \Config\Services::email();
		$emailService->setTo($data->email);
		$emailService->setFrom('mail.atelierdemanon@gmail.com', 'L\'Atelier de Manon');
		$emailService->setSubject('Création de votre compte sur L\'Atelier de Manon!');
		$emailService->setMessage("
			Bonjour $data->prenomCli $data->nomCli,
			
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
		$data = $this->request->getJSON();
		$token = $data->token;

		if (!session()->has("registration_$token")) {
			return $this->respond("Token invalide.");
		}

		$registrationData = session()->get("registration_$token");

		// Enregistrer les données dans la base de données
		$this->model->createAccount($registrationData);

		session()->remove("registration_$token");

		return $this->respond("Votre compte a bien été crée.");
	}

	public function login()
	{
		$session = session();
		$data = $this->request->getJSON();
		$account = $this->model->getAccountByEmail($data->email);

		if ($account) {
			$mdp = $account['mdp'];
			$authenticatePassword = password_verify($data->mdp, $mdp);

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
		$data = $this->request->getJSON();
		$account = $this->model->getAccountByEmail($data->email);

		if ($account) {

			$token = bin2hex(random_bytes(16));
			session()->set("updatePassword_$token", $token);

			$expiration = date('Y-m-d H:i:s', strtotime('+1 hour'));


			$this->model->update($account['idCli'], [
				'token' => $token,
				'token_expiration' => $expiration
			]);

			$resetLink = site_url("/forgot-password/reset-password/$token");
			$message = "Cliquez sur le lien suivant pour réinitialiser votre mot de passe: $resetLink";

			$emailService = \Config\Services::email();


			$emailService->setTo($data->email);
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
		$data = $this->request->getJSON();
		$token = $data->token;

		if (!session()->get("updatePassword_$token")) {
			return $this->respond("Token invalide.");
		}

		return $this->respond("Passage à la vue pour reset");
	}

	public function updatePassword()
	{
		$data = $this->request->getJSON();

		$account = $this->model->getAccountByToken($data->token);

		if ($account && $data->password === $data->confirm_password) {
			$hashedPassword = password_hash($data->password, PASSWORD_DEFAULT);
			$this->model->updatePassword($hashedPassword, $account["idCli"]);
			return $this->respond("Mot de passe réinitialisé avec succès.");
		} else {
			return $this->respond("Erreur lors de la réinitialisation du mot de passe.");
		}
	}


	public function addNewsLetter() 
	{
		$data = $this->request->getJSON();

		$response = $this->model->addNewsLetter($data->idCli);

		return $this->respond($response);
	}


	public function removeNewsLetter() 
	{
		$data = $this->request->getJSON();

		$response = $this->model->removeNewsLetter($data->idCli);

		return $this->respond($response);
	}

	public function sendNewsLetters()
	{
		$data = $this->request->getJSON();

		$subject = $data->subject;
		$content = $data->content;

		$accounts = $this->model->getAccountNewsLetter();

		foreach ($accounts as $account) {
			$email = $account["email"];

			$emailService = \Config\Services::email();
			$emailService->setTo($email);
			$emailService->setFrom('mail.atelierdemanon@gmail.com', 'L\'Atelier de Manon');
			$emailService->setSubject($subject);
			$emailService->setMessage($content);

			if (!$emailService->send()) {
				return $this->respond("Erreur lors de l'envoi de l'email.");
			}

			return $this->respond("Les mails ont bien été envoyés.");
		}
	}

	public function contactMail()
    {
        $data = $this->request->getJSON();

		$emailService = \Config\Services::email();
		$emailService->setTo('mail.atelierdemanon@gmail.com');
		$emailService->setFrom('mail.atelierdemanon@gmail.com', 'L\'Atelier de Manon');
		$emailService->setSubject($data->objet);
		$emailService->setMessage("
			Message de la part de $data->nom, avec l'adresse mail $data->mail :
			
			$data->content"
		);

		if (!$emailService->send()) {
			return $this->respond("Erreur lors de l'envoi de l'email.");
		}

		return $this->respond("Les mails ont bien été envoyés.");
    }
}
