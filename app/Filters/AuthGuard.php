<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthGuard implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {

        // Ajouter les en-têtes CORS
        service('response')
            ->setHeader('Access-Control-Allow-Origin', '*')
            ->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')
            ->setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization')
            ->setHeader('Access-Control-Allow-Credentials', 'true');
        
        // Si la méthode de requête est OPTIONS, renvoyer une réponse vide (200 OK) sans traitement ultérieur
        if ($request->getMethod() === 'options') {
            service('response')->setStatusCode(200);
            return service('response');
        }

        // return true; // test push

        // $uri = $request->getUri()->getPath();

        // if (strpos($uri, '/client') !== false) {
        //     if (!session()->get('isLoggedIn')) {
        //         return service('response')
        //             ->setStatusCode(403)
        //             ->setHeader('Content-Type', 'application/json')
        //             ->setBody(json_encode([
        //                 'status' => 403,
        //                 'error' => true,
        //                 'message' => 'Forbidden: Access denied',
        //             ]));
        //     }
        // }

        // if (strpos($uri, '/admin') !== false) {
        //     if (!session()->get('isLoggedIn') || !session()->get('isAdmin')) {
        //         return service('response')
        //             ->setStatusCode(403)
        //             ->setHeader('Content-Type', 'application/json')
        //             ->setBody(json_encode([
        //                 'status' => 403,
        //                 'error' => true,
        //                 'message' => 'Forbidden: Admin access required',
        //             ]));
        //     }
        // }

        // return null;
    }


    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}
