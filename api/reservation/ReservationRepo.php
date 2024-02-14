<?php
include_once "./config/Database.php";
require_once "./common/exception/repositoryException.php";

class ReservationRepository {
    private $conn = null;

    public function __construct() {
        $this->conn = Database::getInstance();
    }

    public function getAppartmentReservations($appId) {
        // Logique pour obtenir les réservations pour un appartement donné
        $query = "SELECT * FROM reservations WHERE appartementId = :appId";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':appId', $appId);
        $stmt->execute();
        $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $reservations;
    }

    public function makeReservation($reservationData) {
        // Logique pour effectuer une réservation
        $query = "INSERT INTO reservations (appartementId, dateDebut, dateFin, clientid, prix) 
                  VALUES (:appartementId, :dateDebut, :dateFin, :clientid, :prix)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':appartementId', $reservationData['appartementId']);
        $stmt->bindParam(':dateDebut', $reservationData['dateDebut']);
        $stmt->bindParam(':dateFin', $reservationData['dateFin']);
        $stmt->bindParam(':clientid', $reservationData['clientid']);
        $stmt->bindParam(':prix', $reservationData['prix']);
        $stmt->execute();
        return $this->conn->lastInsertId();
    }

    public function updateReservation($reservationId, $updatedData) {
        // Logique pour mettre à jour une réservation
        $query = "UPDATE reservations 
                  SET dateDebut = :dateDebut, dateFin = :dateFin, clientid = :clientid, prix = :prix 
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':dateDebut', $updatedData['dateDebut']);
        $stmt->bindParam(':dateFin', $updatedData['dateFin']);
        $stmt->bindParam(':clientid', $updatedData['clientid']);
        $stmt->bindParam(':prix', $updatedData['prix']);
        $stmt->bindParam(':id', $reservationId);
        $stmt->execute();
    }

    public function cancelReservation($reservationId) {
        // Logique pour annuler une réservation
        $query = "DELETE FROM reservations WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $reservationId);
        $stmt->execute();
    }

    public function addReservation($reservationData) {
        $query = "INSERT INTO reservations (date_debut, date_fin, client_associé, prix) 
                  VALUES (:date_debut, :date_fin, :client_associe, :prix)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':date_debut', $reservationData['date_debut']);
        $stmt->bindParam(':date_fin', $reservationData['date_fin']);
        $stmt->bindParam(':client_associe', $reservationData['client_associe']);
        $stmt->bindParam(':prix', $reservationData['prix']);
        $stmt->execute();
        return $this->conn->lastInsertId();
    }
}
?>
