<?php
include_once "./config/Database.php";
require_once "./common/exception/repositoryException.php";

class UserRepository {
    private $conn = null;

    public function __construct() {
        $this->conn = Database::getInstance();
    }

    public function createUser($userObject) : void {
        try {
            $query = "INSERT INTO utilisateurs (nom, password, role) VALUES (:nom, :password, :role)";
    
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':nom', $userObject->nom);
            $stmt->bindParam(':password', $userObject->motdePasse);
            $stmt->bindParam(':role', $userObject->role);
    
            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la création de l'utilisateur: " . $e->getMessage());
        }
    }

    public function getUsers(): array {
        $query = "SELECT * FROM utilisateurs"; 
    
        $stmt = $this->conn->prepare($query); 

        $stmt->execute();
        $users = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $users[] = $row; 
        }
        return $users;
    }

    function getUser($id) {
        $query = "SELECT * FROM utilisateurs WHERE id = :id";
    
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
    
        $stmt->execute();
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (!$user) {
            throw new BddNotFoundException("Cet utilisateur n'existe pas");
        }
    
        return $user;
    }

    public function deleteUser($id): void {
        $query = "DELETE FROM utilisateurs WHERE id = :id";
    
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        $stmt->execute();
    
        $rows = $stmt->rowCount();
        if ($rows === 0) {
            throw new Exception("Aucun utilisateur trouvé avec l'ID spécifié.");
        }
    }

    public function updateUser($id, $valuesToUpdate): void {
        $columnsToUpdate = array();
        $params = array(':id' => $id);
    
        foreach ($valuesToUpdate as $key => $value) {
            $columnsToUpdate[] = "$key = :$key";
            $params[":$key"] = $value;
        }
    
        $setClause = implode(", ", $columnsToUpdate);
    
        $query = "UPDATE utilisateurs SET $setClause WHERE id = :id";
    
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
    }
    
    public function authenticateUser($username, $password) {
        $query = "SELECT * FROM utilisateurs WHERE nom = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
    
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (!$user) {
            throw new BddNotFoundException("Utilisateur non trouvé");
        }
    
        if (password_verify($password, $user['password'])) {
            return $user;
        } else {
            throw new Exception("Mot de passe incorrect.");
        }
    }
    
}
?>