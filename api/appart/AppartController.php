<?php
require_once 'AppartService.php';
require_once 'AppartModel.php';

class AppartController {
    private $service;

    function __construct() {
        $this->service = new AppartService();
    }

    function dispatch($req, $res) {
        switch ($req->method) {
            case "GET":
                // Récupérer la liste des appartements disponibles
                $this->getAvailableAppartments($req, $res);
                break;
    
            case "POST":
                // Ajouter un nouvel appartement
                $this->addAppartment($req, $res);
                break;
    
            case "PATCH":
                // Modifier les informations d'un appartement
                $this->updateAppartment($req, $res);
                break;
    
            case "DELETE":
                // Supprimer un appartement
                $this->deleteAppartment($req, $res);
                break;
    
            default:
                $res->statusCode = 405;
                $res->content = ['error' => 'Méthode non autorisée'];
                break;
        }
    }

    function getAvailableAppartments($req, $res) {
        // Logique pour récupérer la liste des appartements disponibles
        $availableAppartments = $this->service->getAvailableAppartments();
        $res->status = 200;
        $res->content = $availableAppartments;
    }

    function addAppartment($req, $res) {
        // Logique pour ajouter un nouvel appartement
        $appartmentData = $req->body;
        $newAppartment = $this->service->addAppartment($appartmentData);
        $res->status = 201;
        $res->content = $newAppartment;
    }

    function updateAppartment($req, $res) {
        // Logique pour modifier les informations d'un appartement
        $appId = $req->params['id'];
        $updatedAppartmentData = $req->body;
        $this->service->updateAppartment($appId, $updatedAppartmentData);
        $res->status = 200;
        $res->content = ['message' => 'Appartement mis à jour avec succès'];
    }

    function deleteAppartment($req, $res) {
        // Logique pour supprimer un appartement
        $appId = $req->params['id'];
        $this->service->deleteAppartment($appId);
        $res->status = 200;
        $res->content = ['message' => 'Appartement supprimé avec succès'];
    }
}
?>
