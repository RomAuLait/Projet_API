<?php
include_once "UserRepo.php";

class UserService {
    private $repository;

    function __construct() {
        $this->repository = new UserRepository();
    }

    function createUser($userObject) {
        $userObject->motdePasse = password_hash($userObject->motdePasse, PASSWORD_DEFAULT);
        return $this->repository->createUser($userObject);
    }

    function getUsers() {
        return $this->repository->getUsers();
    }

    function getUser($id) {
        return $this->repository->getUser($id);
    }

    function deleteUser($id) {
        return $this->repository->deleteUser($id);
    }

    function updateUser($id, $userObject) {
        return $this->repository->updateUser($id, $userObject);
    }

    function authenticateUser($username, $password) {
        return $this->repository->authenticateUser($username, $password);
    }
}
?>