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
        $type = $this->request->getGet('width');

        $filePath = FCPATH . 'images/' . $type . "/" . $type . ".webp";

        $produitController = new ProduitController();
        $produitController->resizeAndGetImage($filePath, $width);
    }

    public function uploadImage()
    {
        $data = $this->request->getJSON();

        $image = $data->image;
        $type = $data->type;

        $filePath = FCPATH . 'images/' . $type . "/" . $type . ".webp";

        $photoBase64 = preg_replace('/^data:image\/\w+;base64,/', '', $image);

        $imageData = base64_decode($photoBase64);
        if ($imageData === false) {
            return $this->respond("Impossible de décoder l'image.", 400);
        }

        $image = imagecreatefromstring($imageData);
        if ($image === false) {
            return $this->respond("Impossible de recréer l'image.", 400);
        }

        imagewebp($image, $filePath, 80);
        imagedestroy($image);

        return $this->respond("Image enregistrée avec succès.", 201);
    }

    public function updateEvenement()
    {
        $data = $this->request->getJSON();

        $message = $data->message;

        $filePath = FCPATH . 'evenement/message.data';

        if (file_put_contents($filePath, $message) === false) {
            return $this->respond("Impossible de mettre à jour le fichier", 500);
        }

        return $this->respond("Le message a été mis à jour avec succès", 201);
    }

    public function getEvenementMessage()
    {
        $filePath = FCPATH . 'evenement/message.data';

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
