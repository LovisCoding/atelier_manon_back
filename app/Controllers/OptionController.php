<?php

namespace App\Controllers;

class OptionController extends BaseController
{
    public function index(): string
    {
        $response = service('response');

        $response->setHeader('Access-Control-Allow-Origin', getenv("FRONT_URL"))
            ->setHeader('Access-Control-Allow-Methods', 'GET, POST, DELETE, OPTIONS')
            ->setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization')
            ->setHeader('Access-Control-Allow-Credentials', 'true');

        return $response->setStatusCode(200);
    }
}
