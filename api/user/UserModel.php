<?php
class Utilisateur {
    public $nom;
    public $motdePasse;
    public $role;
    private $conn;

    public function __construct($nom, $motdePasse, $role) {
        $this->nom = $nom;
        $this->motdePasse = $motdePasse;
        $this->role = $role;
    }
}
?>