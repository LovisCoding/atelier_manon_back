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
$routes->delete('/api/produit/delete-produit', 'ProduitController::deleteProduit');
$routes->get('/api/produit/get-bestsellers', 'ProduitController::getBestSellers');

$routes->get('/api/img/(:any)', 'ProduitController::getImage/$1');

$routes->post('/api/matprod/add-matprod', 'MatProdController::addMatProd');
$routes->delete('/api/matprod/delete-matprod', 'MatProdController::deleteMatProd');
$routes->get('/api/matprod/get-materiaux-produit', 'MatProdController::getMateriauxProduit');

$routes->post('/api/pieprod/add-pieprod', 'PieProdController::addPieProd');
$routes->delete('/api/pieprod/delete-pieprod', 'PieProdController::deletePieProd');
$routes->get('/api/pieprod/get-pierres-produit', 'PieProdController::getPierresProduit');

$routes->post('/api/filprod/add-filprod', 'FilProdController::addFilProd');
$routes->delete('/api/filprod/delete-filprod', 'FilProdController::deleteFilProd');
$routes->get('/api/filprod/get-fils-produit', 'FilProdController::getFilsProduit');

$routes->post('/api/materiau/add-materiau', 'MateriauController::addMateriau');
$routes->delete('/api/materiau/delete-materiau', 'MateriauController::deleteMateriau');
$routes->get('/api/materiau/get-materiaux', 'MateriauController::getMateriaux');

$routes->post('/api/pierre/add-pierre', 'PierreController::addPierre');
$routes->delete('/api/pierre/delete-pierre', 'PierreController::deletePierre');
$routes->get('/api/pierre/get-pierres', 'PierreController::getPierres');

$routes->post('/api/fil/add-fil', 'FilController::addFil');
$routes->delete('/api/fil/delete-fil', 'FilController::deleteFil');
$routes->get('/api/fil/get-fils', 'FilController::getFils');

$routes->post('/api/categorie/add-categorie', 'CategorieController::addCategorie');
$routes->delete('/api/categorie/delete-categorie', 'CategorieController::deleteCategorie');
$routes->get('/api/categorie/get-categories', 'CategorieController::getCategories');

$routes->post('/api/article/add-update-article', 'ArticleController::addUpdateArticle');
$routes->delete('/api/article/delete-article', 'ArticleController::deleteArticle');
$routes->get('/api/article/get-articles', 'ArticleController::getArticles');
$routes->get('/api/article/get-article', 'ArticleController::getArticle');

$routes->post('/api/question/add-update-question', 'FAQController::addUpdateQuestion');
$routes->delete('/api/question/delete-question', 'FAQController::deleteQuestion');
$routes->get('/api/question/get-questions', 'FAQController::getQuestions');
$routes->get('/api/question/get-question', 'FAQController::getQuestion');

$routes->post('/api/avis/add-avis', 'AvisController::addAvis');
$routes->delete('/api/avis/delete-avis', 'AvisController::deleteAvis');
$routes->get('/api/avis/get-all-avis', 'AvisController::getAllAvis');
$routes->get('/api/avis/get-avis', 'AvisController::getAvis');

$routes->post('/api/promoproduit/add-promoproduit', 'PromoProduitController::addPromoProduit');
$routes->delete('/api/promoproduit/delete-promoproduit', 'PromoProduitController::deletePromoProduit');
$routes->get('/api/promoproduit/get-produits-promo', 'PromoProduitController::getProduitsByCode');

$routes->post('/api/codepromo/add-codepromo', 'CodePromoController::addCodePromo');
$routes->delete('/api/codepromo/delete-codepromo', 'CodePromoController::deleteCodePromo');
$routes->get('/api/codepromo/get-codespromo', 'CodePromoController::getCodesPromo');
$routes->get('/api/codepromo/get-codespromo-use', 'CodePromoController::getCodesPromoWithUse');

$routes->post('/api/utilisationcode/add-utilisationcode', 'UtilisationCodeController::addUtilisationCode');
$routes->delete('/api/utilisationcode/delete-utilisationcode', 'UtilisationCodeController::deleteUtilisationCode');
$routes->get('/api/utilisationcode/get-codes-commande', 'UtilisationCodeController::getCodesPromoByCommande');

$routes->post('/api/commande/add-commande', 'CommandeController::addCommande');
$routes->delete('/api/commande/delete-commande', 'CommandeController::deleteCommande');
$routes->post('/api/commande/update-etat-commande', 'CommandeController::updateEtatCommande');
$routes->get('/api/commande/get-commande', 'CommandeController::getCommande');
$routes->get('/api/commande/get-commandes', 'CommandeController::getCommandes');

$routes->post('/api/panier/add-product-panier', 'PanierController::addProductToPanier');
$routes->post('/api/panier/reduce-product-panier', 'PanierController::reduceProductFromPanier');
$routes->delete('/api/panier/delete-panier-client', 'PanierController::deletePanierClient');
$routes->delete('/api/panier/delete-product-panier', 'PanierController::deleteProductFromPanier');
$routes->get('/api/panier/get-panier-client', 'PanierController::getPaniersFromClient');

$routes->get('/api/commandeproduit/get-produits-commande', 'CommandeProduitController::getProduitsCommande');

$routes->post('/api/account/register', 'CompteController::register');
$routes->post('/api/account/confirmAccount', 'CompteController::confirmAccount');
$routes->post('/api/account/login', 'CompteController::login');
$routes->post('/api/account/forgot-password', 'CompteController::forgotPassword');
$routes->post('/api/account/reset-password', 'CompteController::resetPassword');
$routes->post('/api/account/update-password', 'CompteController::updatePassword');

