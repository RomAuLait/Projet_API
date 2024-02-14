<?php
include_once "AppartRepo.php";

class AppartService {
    private $repository;

    function __construct() {
        $this->repository = new AppartRepository();
    }

    function getAvailableAppartments() {
        // Logique pour récupérer la liste des appartements disponibles
        return $this->repository->getAvailableAppartments();
    }

    function addAppartment($appartmentData) {
        // Logique pour ajouter un nouvel appartement
        return $this->repository->addAppartment($appartmentData);
    }

    function updateAppartment($appId, $updatedAppartmentData) {
        // Logique pour modifier les informations d'un appartement
        $this->repository->updateAppartment($appId, $updatedAppartmentData);
    }

    function deleteAppartment($appId) {
        // Logique pour supprimer un appartement
        $this->repository->deleteAppartment($appId);
    }
}
?>
