<?php
require_once 'UserService.php';
require_once 'UserModel.php';
require_once "./common/token.php";
require 'vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class UserController {
    private $service;

    function __construct() {
        $this->service = new UserService();
    }

    function dispatch($req, $res) {
        switch ($req->method) {
            case "GET":
                if (isset($req->uri[3]) && $req->uri[3] === 'logout') {
                    $this->logout($req, $res);
                } else if (isset($req->uri[3])) {     
                    $this->getUser($req, $res);
                } else {
                    $this->getUsers($req, $res);
                }
                break;
    
            case "POST":
                if (isset($req->uri[3])) {
                    switch ($req->uri[3]) {
                        case 'signin':
                            $this->createUser($req, $res);
                            break;
                        case 'login':
                            $this->login($req, $res);
                            break;
                        default:
                            $res->statusCode = 404;
                            $res->content = ['error' => 'Route non trouvée'];
                            break;
                    }
                } else {
                    $res->statusCode = 400;
                    $res->content = ['error' => 'Route manquante'];
                }
                break;
    
            case "PATCH":
                $this->updateUser($req, $res);
                break;
    
            case "DELETE":
                $this->deleteUser($req, $res);
                break;
    
            default:
                $res->statusCode = 405;
                $res->content = ['error' => 'Methode non autorisee'];
                break;
        }
    }    

    function getUser($req, $res) {
        $user = $this->service->getUser($req->uri[3]);
        
        $res->status = 200;
        $res->content = $user;
    }

    function getUsers($req, $res) {
        $users = $this->service->getUsers();
        $res->status = 200;
        $res->content = $users;
    }

    function createUser($req, $res) {
        if (empty($req->body->nom) || empty($req->body->password) || empty($req->body->role)) {
            $res->status = 400;
            $res->content = json_encode(['error' => 'valeurs manquantes.']);
            return;
        }
    
        $userObject = new Utilisateur($req->body->nom, $req->body->password, $req->body->role);
    
        try {
            $newUser = $this->service->createUser($userObject);
            $res->status = 201; 
            $res->content = json_encode(['message' => 'Utilisateur cree avec succes.']);
        } catch (Exception $e) {
            $res->status = 500;
            $res->content = json_encode(['error' => 'Erreur lors de la création de l utilisateur.']);
        }
    }

    function updateUser($req, $res) {
        $decodedToken = decodeToken($req->headers['Authorization']);
        
        if(!$decodedToken) {
            $res->status = 401;
            $res->content = json_encode(['error' => 'Token invalide.']);
            return;
        }
    
        if ($decodedToken->role !== 'admin') {
            $res->status = 403;
            $res->content = json_encode(['error' => 'Acces non autorise']);
            return;
        }

        if(!isset($req->uri[3])) {
            $res->status = 400;
            $res->content = json_encode(['error' => 'Aucun utilisateur spécifié.']);
            return;
        }
        $valuesToUpdate = array();
    
        if (isset($req->body->nom)) {
            $valuesToUpdate['nom'] = $req->body->nom;
        }
    
        if (isset($req->body->role)) {
            $valuesToUpdate['role'] = $req->body->role;
        }
    
        $this->service->updateUser($req->uri[3], $valuesToUpdate);
    }
    
    

    function deleteUser($req, $res) {
        if (!isset($req->headers['Authorization'])) {
            $res->status = 401;
            $res->content = json_encode(['error' => 'Acces non autorise. Token manquant.']);
            return;
        }
    
        $decodedToken = decodeToken($req->headers['Authorization']);
        
        if(!$decodedToken) {
            $res->status = 401;
            $res->content = json_encode(['error' => 'Token invalide.']);
            return;
        }
    
        if ($decodedToken->role !== 'admin') {
            $res->status = 403;
            $res->content = json_encode(['error' => 'Acces non autorise']);
            return;
        }
    
        $this->service->deleteUser($req->uri[3]);
    
        $res->status = 200;
        $res->content = json_encode(['message' => 'Utilisateur supprime avec succes']);
    }
    

    function login($req, $res) {
        if (empty($req->body->nom) || empty($req->body->password)) {
            $res->status = 400;
            $res->content = json_encode(['error' => 'Valeurs manquates pour la connexion.']);
            return;
        }
    
        $user = $this->service->authenticateUser($req->body->nom, $req->body->password);
    
        if ($user) {
            $token = generateToken($user['id'], $user['nom'], $user['role']);
    
            $res->status = 200;
            $res->content = json_encode(['token' => $token]);
        } else {
            $res->status = 401;
            $res->content = json_encode(['error' => 'Identifiants incorrects.']);
        }
    }
    
    // function generateToken($userId, $username, $role) {
    //     $token = JWT::encode([
    //         'userId' => $userId,
    //         'username' => $username,
    //         'role' => $role,
    //         'exp' => time() + (60 * 60) 
    //     ], 'cleSuperSecrete');
    
    //     return $token;
    // }

    // function decodeToken($token) {
    //     $secretKey = 'cleSuperSecrete';

    //     $decoded = JWT::decode($token, new Key($secretKey, 'HS256'));
    // }
    
    function logout($req, $res) {
        session_destroy();
        phpinfo();
        $res->status = 200;
        $res->content = json_encode(['message' => 'Vous etes deconnectes']);
    }
    
}

?>