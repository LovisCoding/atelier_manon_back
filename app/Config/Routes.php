<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/api/test', 'TestController::api');

/*********************/
/* ACCESSIBLE A TOUS */
/*********************/

$routes->get('/api/produit/get-produits', 'ProduitController::produits');
$routes->get('/api/produit/get-all-produits', 'ProduitController::produitsAll');
$routes->get('/api/produit/get-produit', 'ProduitController::produit');
$routes->get('/api/produit/get-bestsellers', 'ProduitController::getBestSellers');

$routes->get('/api/img/(:any)', 'ProduitController::getImage/$1');

$routes->get('/api/matprod/get-materiaux-produit', 'MatProdController::getMateriauxProduit');

$routes->get('/api/pieprod/get-pierres-produit', 'PieProdController::getPierresProduit');

$routes->get('/api/filprod/get-fils-produit', 'FilProdController::getFilsProduit');

$routes->get('/api/materiau/get-materiaux', 'MateriauController::getMateriaux');

$routes->get('/api/pierre/get-pierres', 'PierreController::getPierres');

$routes->get('/api/fil/get-fils', 'FilController::getFils');

$routes->get('/api/categorie/get-categories', 'CategorieController::getCategories');

$routes->get('/api/article/get-articles', 'ArticleController::getArticles');
$routes->get('/api/article/get-article', 'ArticleController::getArticle');

$routes->get('/api/question/get-questions', 'FAQController::getQuestions');
$routes->get('/api/question/get-question', 'FAQController::getQuestion');

$routes->get('/api/avis/get-all-avis', 'AvisController::getAllAvis');
$routes->get('/api/avis/get-avis', 'AvisController::getAvis');

$routes->post('/api/promoproduit/add-promoproduit', 'PromoProduitController::addPromoProduit');
$routes->delete('/api/promoproduit/delete-promoproduit', 'PromoProduitController::deletePromoProduit');
$routes->get('/api/promoproduit/get-produits-promo', 'PromoProduitController::getProduitsByCode');

$routes->post('/api/codepromo/add-codepromo', 'CodePromoController::addCodePromo');
$routes->delete('/api/codepromo/delete-codepromo', 'CodePromoController::deleteCodePromo');
$routes->get('/api/codepromo/get-codespromo', 'CodePromoController::getCodesPromo');
$routes->get('/api/codepromo/get-codespromo-use', 'CodePromoController::getCodesPromoWithUse');
$routes->get('/api/codepromo/get-codespromo-id', 'CodePromoController::getCodePromo');

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

$routes->post('/api/account/register', 'CompteController::register');
$routes->post('/api/account/confirmAccount', 'CompteController::confirmAccount');
$routes->post('/api/account/login', 'CompteController::login');
$routes->post('/api/account/forgot-password', 'CompteController::forgotPassword');
$routes->post('/api/account/reset-password', 'CompteController::resetPassword');
$routes->post('/api/account/update-password', 'CompteController::updatePassword');
$routes->post('/api/account/send-mail', 'CompteController::contactMail');

/**********/
/* CLIENT */
/**********/

$routes->post('/api/client/question/add-update-question', 'FAQController::addUpdateQuestion');

$routes->post('/api/client/avis/add-avis', 'AvisController::addAvis');

$routes->post('/api/client/utilisationcode/add-utilisationcode', 'UtilisationCodeController::addUtilisationCode');
$routes->delete('/api/client/utilisationcode/delete-utilisationcode', 'UtilisationCodeController::deleteUtilisationCode');
$routes->get('/api/client/utilisationcode/get-codes-commande', 'UtilisationCodeController::getCodesPromoByCommande');

$routes->post('/api/client/commande/add-commande', 'CommandeController::addCommande');

$routes->get('/api/client/commande/get-commande', 'CommandeController::getCommande');
$routes->get('/api/client/commande/get-commandes-client', 'CommandeController::getCommandesByClient');

$routes->post('/api/client/panier/add-product-panier', 'PanierController::addProductToPanier');
$routes->post('/api/client/panier/reduce-product-panier', 'PanierController::reduceProductFromPanier');
$routes->delete('/api/client/panier/delete-panier-client', 'PanierController::deletePanierClient');
$routes->delete('/api/client/panier/delete-product-panier', 'PanierController::deleteProductFromPanier');
$routes->get('/api/client/panier/get-panier-client', 'PanierController::getPaniersFromClient');

$routes->get('/api/client/commandeproduit/get-produits-commande', 'CommandeProduitController::getProduitsCommande');

$routes->post('/api/client/account/add-newsletter', 'CompteController::addNewsLetter');
$routes->post('/api/client/account/remove-newsletter', 'CompteController::removeNewsLetter');
$routes->get('/api/client/account/get-compte', 'CompteController::getCompte');
$routes->post('/api/client/account/logout', 'CompteController::logout');



/*********/
/* ADMIN */
/*********/

$routes->post('/api/produit/update-produit', 'ProduitController::updateProduit'); // TODO
$routes->delete('/api/admin/produit/delete-produit', 'ProduitController::deleteProduit');
$routes->get('/api/produit/produits-vente', 'ProduitController::produitsAllVente'); // TODO
$routes->post('/api/produit/add-image', 'ProduitController::addImage'); // TODO
$routes->post('/api/produit/delete-image', 'ProduitController::deleteImage'); // TODO


$routes->post('/api/admin/matprod/add-matprod', 'MatProdController::addMatProd');
$routes->delete('/api/admin/matprod/delete-matprod', 'MatProdController::deleteMatProd');

$routes->post('/api/admin/pieprod/add-pieprod', 'PieProdController::addPieProd');
$routes->delete('/api/admin/pieprod/delete-pieprod', 'PieProdController::deletePieProd');

$routes->post('/api/admin/filprod/add-filprod', 'FilProdController::addFilProd');
$routes->delete('/api/admin/filprod/delete-filprod', 'FilProdController::deleteFilProd');

$routes->post('/api/admin/materiau/add-materiau', 'MateriauController::addMateriau');
$routes->delete('/api/admin/materiau/delete-materiau', 'MateriauController::deleteMateriau');

$routes->post('/api/admin/pierre/add-pierre', 'PierreController::addPierre');
$routes->delete('/api/admin/pierre/delete-pierre', 'PierreController::deletePierre');

$routes->post('/api/admin/fil/add-fil', 'FilController::addFil');
$routes->delete('/api/admin/fil/delete-fil', 'FilController::deleteFil');

$routes->post('/api/admin/categorie/add-categorie', 'CategorieController::addCategorie');
$routes->delete('/api/admin/categorie/delete-categorie', 'CategorieController::deleteCategorie');

$routes->post('/api/admin/article/add-update-article', 'ArticleController::addUpdateArticle');
$routes->delete('/api/admin/article/delete-article', 'ArticleController::deleteArticle');

$routes->delete('/api/admin/question/delete-question', 'FAQController::deleteQuestion');

$routes->delete('/api/admin/avis/delete-avis', 'AvisController::deleteAvis');

$routes->post('/api/admin/promoproduit/add-promoproduit', 'PromoProduitController::addPromoProduit');
$routes->post('/api/admin/promoproduit/add-promoproduits', 'PromoProduitController::addPromoProduits');
$routes->delete('/api/admin/promoproduit/delete-promoproduit', 'PromoProduitController::deletePromoProduit');
$routes->delete('/api/admin/promoproduit/delete-promoproduits', 'PromoProduitController::deletePromoProduits');
$routes->get('/api/admin/promoproduit/get-produits-promo', 'PromoProduitController::getProduitsByCode');

$routes->post('/api/admin/codepromo/add-codepromo', 'CodePromoController::addCodePromo');
$routes->delete('/api/admin/codepromo/delete-codepromo', 'CodePromoController::deleteCodePromo');
$routes->get('/api/admin/codepromo/get-codespromo', 'CodePromoController::getCodesPromo');


$routes->delete('/api/admin/commande/delete-commande', 'CommandeController::deleteCommande');
$routes->post('/api/admin/commande/update-etat-commande', 'CommandeController::updateEtatCommande');

$routes->get('/api/commande/get-commandes', 'CommandeController::getCommandes');// TODO

$routes->post('/api/admin/account/send-newsletter', 'CompteController::sendNewsLetters');
$routes->get('/api/admin/account/get-comptes', 'CompteController::getComptes');
$routes->get('/api/admin/account/get-compte-admin', 'CompteController::getCompteByAdmin');

$routes->post('/api/admin/personnalisation/upload-image', 'PersonnalisatinController::uploadImage');






