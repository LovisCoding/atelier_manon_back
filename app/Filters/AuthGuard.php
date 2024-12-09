<?php

namespace App\Filters;

use App\Models\CompteModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthGuard implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $response = service('response');

        // Ajout des headers pour CORS
        $response->setHeader('Access-Control-Allow-Origin', '*')
            ->setHeader('Access-Control-Allow-Methods', 'GET, POST, DELETE, OPTIONS')
            ->setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization')
            ->setHeader('Access-Control-Allow-Credentials', 'true');

        // Gestion des requêtes OPTIONS
        if ($request->getMethod() === 'options') {
            return $response->setStatusCode(200);
        }

        $uri = $request->getUri()->getPath();


        if (strpos($uri, '/admin') !== false || strpos($uri, '/client') !== false) {
            // Récupération de l'URI et de la session
            $session = session();

            // Vérification si l'utilisateur est connecté
            if (!$session->has("data") || empty($session->get("data")["idCli"])) {
                return $this->jsonResponse(403, 'Forbidden: User not logged in');
            }

            // Récupération des données utilisateur
            $data = $session->get("data");
            $idCli = $data["idCli"];
            $compteModel = new CompteModel();
            $account = $compteModel->getAccountById($idCli);

            if (!$account || $account["dateSup"] != null) {
                return $this->jsonResponse(403, 'Forbidden: Invalid user account');
            }

            // Vérification des permissions pour /client et /admin
            if (strpos($uri, '/admin') !== false && $account["estAdmin"] == "f") {
                return $this->jsonResponse(403, 'Forbidden: Admin access required');
            }
        }

        return null;
    }



    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}



    /**
     * Helper pour créer une réponse JSON.
     *
     * @param int    $statusCode Le code HTTP.
     * @param string $message    Le message de la réponse.
     * @return ResponseInterface
     */
    private function jsonResponse(int $statusCode, string $message): ResponseInterface
    {
        return service('response')
            ->setStatusCode($statusCode)
            ->setHeader('Content-Type', 'application/json')
            ->setBody(json_encode([
                'status' => $statusCode,
                'error' => true,
                'message' => $message,
            ]));
    }
}
