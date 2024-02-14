<?php
require_once 'ReservationService.php';
require_once 'ReservationModel.php';

class ReservationController {
    private $service;

    function __construct() {
        $this->service = new ReservationService();
    }

    function dispatch($req, $res) {
        switch ($req->method) {
            case "POST":
                // Effectuer une réservation
                $this->makeReservation($req, $res);
                break;
    
            case "GET":
                // Obtenir les réservations pour un appartement particulier
                $this->getAppartmentReservations($req, $res);
                break;
    
            case "PATCH":
                // Mettre à jour une réservation
                $this->updateReservation($req, $res);
                break;
    
            case "DELETE":
                // Annuler une réservation
                $this->cancelReservation($req, $res);
                break;
    
            default:
                $res->statusCode = 405;
                $res->content = ['error' => 'Méthode non autorisée'];
                break;
        }
    }

    function makeReservation($req, $res) {
        // Logique pour effectuer une réservation
        $reservationData = $req->body;
        $newReservation = $this->service->makeReservation($reservationData);
        $res->status = 201;
        $res->content = $newReservation;
    }

    function getAppartmentReservations($req, $res) {
        // Logique pour obtenir les réservations pour un appartement donné
        $appId = $req->params['id'];
        $reservations = $this->service->getAppartmentReservations($appId);
        $res->status = 200;
        $res->content = $reservations;
    }

    function updateReservation($req, $res) {
        // Logique pour mettre à jour une réservation
        $reservationId = $req->params['id'];
        $updatedData = $req->body;
        $this->service->updateReservation($reservationId, $updatedData);
        $res->status = 200;
        $res->content = ['message' => 'Réservation mise à jour avec succès'];
    }

    function cancelReservation($req, $res) {
        // Logique pour annuler une réservation
        $reservationId = $req->params['id'];
        $this->service->cancelReservation($reservationId);
        $res->status = 200;
        $res->content = ['message' => 'Réservation annulée avec succès'];
    }
}
?>
