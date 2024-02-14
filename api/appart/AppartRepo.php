<?php
include_once "./config/Database.php";
require_once "./common/exception/repositoryException.php";

class AppartRepository {
    private $conn = null;

    public function __construct() {
        $this->conn = Database::getInstance();
    }

    public function getAvailableAppartments() {
        $query = "SELECT * FROM appartement WHERE disponibilite = 'disponible'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $appartments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $appartments;
    }

    public function addAppartment($appartmentData) {
        $query = "INSERT INTO appartement (superficie, personnes, adresse, disponibilite, prix, proprietaireid) 
                  VALUES (:superficie, :personnes, :adresse, :disponibilite, :prix, :proprietaireid)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':superficie', $appartmentData['superficie']);
        $stmt->bindParam(':personnes', $appartmentData['personnes']);
        $stmt->bindParam(':adresse', $appartmentData['adresse']);
        $stmt->bindParam(':disponibilite', $appartmentData['disponibilite']);
        $stmt->bindParam(':prix', $appartmentData['prix']);
        $stmt->bindParam(':proprietaireid', $appartmentData['proprietaireid']);
        $stmt->execute();
        return $this->conn->lastInsertId();
    }

    public function updateAppartment($appId, $updatedAppartmentData) {
        $query = "UPDATE appartement
                  SET superficie = :superficie, personnes = :personnes, adresse = :adresse, disponibilite = :disponibilite, prix = :prix 
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':superficie', $updatedAppartmentData['superficie']);
        $stmt->bindParam(':personnes', $updatedAppartmentData['personnes']);
        $stmt->bindParam(':adresse', $updatedAppartmentData['adresse']);
        $stmt->bindParam(':disponibilite', $updatedAppartmentData['disponibilite']);
        $stmt->bindParam(':prix', $updatedAppartmentData['prix']);
        $stmt->bindParam(':id', $appId);
        $stmt->execute();
    }

    public function deleteAppartment($appId) {
        $query = "DELETE FROM appartement WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $appId);
        $stmt->execute();
    }
}
?>
