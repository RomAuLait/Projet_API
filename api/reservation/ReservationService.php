<?php
include_once "ReservationRepo.php";

class ReservationService {
    private $repository;

    function __construct() {
        $this->repository = new ReservationRepository();
    }

    function getAppartmentReservations($appId) {
        return $this->repository->getAppartmentReservations($appId);
    }

    function makeReservation($reservationData) {
        return $this->repository->makeReservation($reservationData);
    }

    function updateReservation($reservationId, $updatedData) {
        $this->repository->updateReservation($reservationId, $updatedData);
    }

    function cancelReservation($reservationId) {
        $this->repository->cancelReservation($reservationId);
    }
}
?>
