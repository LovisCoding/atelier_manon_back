<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/api/test', 'TestController::api');

$routes->get('/api/produit/get-produits', 'ProduitController::produits');
$routes->get('/api/produit/get-produit', 'ProduitController::produit');
$routes->post('/api/produit/update-produit', 'ProduitController::updateProduit');


$routes->post('/api/account/register', 'CompteController::register');
$routes->post('/api/account/confirmAccount', 'CompteController::confirmAccount');
$routes->post('/api/account/login', 'CompteController::login');
$routes->post('/api/account/forgot-password', 'CompteController::forgotPassword');
$routes->post('/api/account/reset-password', 'CompteController::resetPassword');
$routes->post('/api/account/update-password', 'CompteController::updatePassword');

