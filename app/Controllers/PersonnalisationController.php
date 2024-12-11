<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class PersonnalisationController extends ResourceController
{
    protected $modelName = 'App\Models\ProduitModel';
    protected $format    = 'json';


    public function getImage() 
    {
        $width = $this->request->getGet('width');
        $type = $this->request->getGet('type');

        if (empty($type)) {
            return $this->respond("Le type de l'image est requis.", 400);
        }

        if ($type === "home" || $type === "bijoux" || $type === "logo")
            $filePath = FCPATH . 'images/' . $type . "/" . $type . ".webp";
        else
            $filePath = FCPATH . 'images/categories/' . $type . ".webp";


        $produitController = new ProduitController();
        $response = $produitController->resizeAndGetImage($filePath, $width);
        if (!$response) {
            return $this->respond("Image non trouvé", 404);
        }
    }

    public function uploadImage()
    {
        $data = $this->request->getJSON();

        $image = $data->image;
        $type = $data->type;

        if (empty($image) || empty($type)) {
            return $this->respond("L'image et son type sont requis.", 400);
        }

        $filePath = FCPATH . 'images/' . $type . "/" . $type . ".webp";

        $photoBase64 = preg_replace('/^data:image\/\w+;base64,/', '', $image);

        $imageData = base64_decode($photoBase64);
        if ($imageData === false) {
            return $this->respond("Impossible de décoder l'image.", 400);
        }

        $newImage = imagecreatefromstring($imageData);
        if ($newImage === false) {
            return $this->respond("Impossible de recréer l'image.", 400);
        }

        imagewebp($newImage, $filePath, 80);
        imagedestroy($newImage);

        return $this->respond("Image enregistrée avec succès.", 201);
    }

    public function updateEvenement()
    {
        $data = $this->request->getJSON();

        $message = $data->message;
        
        if (empty($data->type))
            $filePath = FCPATH . 'evenement/message.data';
        else 
            $filePath = FCPATH . $data->type .'/message.data';

        if (file_put_contents($filePath, $message) === false) {
            return $this->respond("Impossible de mettre à jour le fichier", 500);
        }

        return $this->respond("Le message a été mis à jour avec succès", 201);
    }

    public function getEvenementMessage()
    {
        $type = $this->request->getGet('type');

        $filePath = FCPATH . $type .'/message.data';

        if (!file_exists($filePath)) {
            return $this->respond("Le fichier message.data n\'existe pas", 404);
        }

        $message = file_get_contents($filePath);

        if ($message === false) {
            return $this->respond('Impossible de lire le fichier message.data', 500);
        }

        return $this->respond($message);
    }

}
