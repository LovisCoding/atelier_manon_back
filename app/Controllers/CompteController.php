<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\CompteModel;

class CompteController extends ResourceController
{
	protected $modelName = 'App\Models\CompteModel';
	protected $format    = 'json';


	public function getComptes() 
	{
		$clients = $this->model->getAccounts();
		return $this->respond($clients);
	}

	public function getCompte() 
	{
		$idCli = $this->request->getGet("idCli");

		if (empty($idCli)) {
			return $this->respond("L'ID du client est requis.", 400);
		}

		$client = $this->model->getAccountById($idCli);
		return $this->respond($client);
	}

	public function register()
	{
		$data = $this->request->getJSON();

		if (empty($data->email) || empty($data->mdp) || empty($data->nomCli) || empty($data->prenomCli)) {
			return $this->respond("Tous les champs sont requis.", 400);
		}

		$existingUser = $this->model->getAccountByEmail($data->email);

		if ($existingUser) {
			return $this->respond("Un compte avec cet email est déjà créé.", 400);
		}

		if (is_array($data->adresse)) {
			$adresse = '{' . implode(',', array_map(fn($item) => "\"$item\"", $data->adresse)) . '}';
		}

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
			return $this->respond("Erreur lors de l'envoi de l'email. Veuillez réessayer.", 500);
		}
	}



	public function confirmAccount()
	{
		$data = $this->request->getJSON();
		$token = $data->token;

		if (!session()->has("registration_$token")) {
			return $this->respond("Token invalide ou expiré.", 400);
		}

		$registrationData = session()->get("registration_$token");

		$this->model->createAccount($registrationData);

		session()->remove("registration_$token");

		return $this->respond("Votre compte a bien été créé.", 201);
	}


	public function forgotPassword()
	{
		$data = $this->request->getJSON();

		if (empty($data->email)) {
			return $this->respond("L'email est requis.", 400);
		}

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
				return $this->respond("Un email vous a été envoyé pour réinitialiser votre mot de passe.");
			} else {
				return $this->respond("Erreur lors de l'envoi de l'email de réinitialisation.", 500);
			}
		} else {
			return $this->respond("L'adresse email fournie est invalide.", 404);
		}
	}

	public function resetPassword()
	{
		$data = $this->request->getJSON();
		$token = $data->token;

		if (!session()->get("updatePassword_$token")) {
			return $this->respond("Token invalide ou expiré.", 400);
		}

		return $this->respond("Passage à la vue pour réinitialiser le mot de passe.");
	}


	public function updatePassword()
	{
		$data = $this->request->getJSON();

		if (empty($data->token) || empty($data->password) || empty($data->confirm_password)) {
			return $this->respond("Le token et les mots de passe sont requis.", 400);
		}

		if ($data->password !== $data->confirm_password) {
			return $this->respond("Les mots de passe ne correspondent pas.", 400);
		}

		$account = $this->model->getAccountByToken($data->token);

		if ($account) {
			$hashedPassword = password_hash($data->password, PASSWORD_DEFAULT);
			$this->model->updatePassword($hashedPassword, $account["idCli"]);
			return $this->respond("Mot de passe réinitialisé avec succès.", 201);
		} else {
			return $this->respond("Erreur lors de la réinitialisation du mot de passe. Token invalide.", 400);
		}
	}


	public function addNewsLetter()
	{
		$data = $this->request->getJSON();

		if (empty($data->idCli)) {
			return $this->respond("L'ID du client est requis.", 400);
		}

		$response = $this->model->addNewsLetter($data->idCli);

		if ($response) {
			return $this->respond("Client ajouté à la newsletter.", 201);
		} else {
			return $this->respond("Erreur lors de l'ajout à la newsletter.", 500);
		}
	}

	public function removeNewsLetter()
	{
		$data = $this->request->getJSON();

		if (empty($data->idCli)) {
			return $this->respond("L'ID du client est requis.", 400);
		}

		$response = $this->model->removeNewsLetter($data->idCli);

		if ($response) {
			return $this->respond("Client retiré de la newsletter.", 201);
		} else {
			return $this->respond("Erreur lors du retrait de la newsletter.", 500);
		}
	}


	public function sendNewsLetters()
	{
		$data = $this->request->getJSON();

		if (empty($data->subject) || empty($data->content)) {
			return $this->respond("Le sujet et le contenu sont requis.", 400);
		}

		$subject = $data->subject;
		$content = $data->content;

		$accounts = $this->model->getAccountNewsLetter();

		$errors = [];
		foreach ($accounts as $account) {
			$email = $account["email"];

			$emailService = \Config\Services::email();
			$emailService->setTo($email);
			$emailService->setFrom('mail.atelierdemanon@gmail.com', 'L\'Atelier de Manon');
			$emailService->setSubject($subject);
			$emailService->setMessage($content);

			if (!$emailService->send()) {
				$errors[] = $email; // Collecter les erreurs d'envoi
			}
		}

		if (empty($errors)) {
			return $this->respond("Les mails ont bien été envoyés.");
		} else {
			return $this->respond("Erreur lors de l'envoi des emails à : " . implode(", ", $errors), 500);
		}
	}



	public function contactMail()
	{
		$data = $this->request->getJSON();

		if (empty($data->objet) || empty($data->nom) || empty($data->mail) || empty($data->content)) {
			return $this->respond("Tous les champs sont requis.", 400);
		}

		$emailService = \Config\Services::email();
		$emailService->setTo('mail.atelierdemanon@gmail.com');
		$emailService->setFrom($data->mail, $data->nom);
		$emailService->setSubject($data->objet);
		$emailService->setMessage("
Message de la part de $data->nom, avec l'adresse mail $data->mail :
    
$data->content"
		);

		if (!$emailService->send()) {
			return $this->respond("Erreur lors de l'envoi de l'email de contact.", 500);
		}

		return $this->respond("L'email a bien été envoyé.");
	}
}
