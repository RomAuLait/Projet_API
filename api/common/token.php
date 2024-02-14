<?php
require 'vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function decodeToken($authHeader) {
    if (!$authHeader) {
        return false; 
    }

    $token = str_replace('Bearer ', '', $authHeader);
    $decodedToken = Firebase\JWT\JWT::decode($token, new Key('cleSuperSecrete', 'HS256'));

    return $decodedToken;
}

function generateToken($userId, $username, $role) {
    if (empty($userId) || empty($username) || empty($role)) {
        throw new Exception('Les paramètres userId, username et role sont requis pour générer un token.', 400);
    }

    $data = [
        'userId' => $userId,
        'username' => $username,
        'role' => $role,
        'exp' => time() + (60 * 60) 
    ];

    $token = Firebase\JWT\JWT::encode($data, 'cleSuperSecrete', 'HS256');

    if(!$token) {
        throw new Exception('Erreur lors de la création du token.', 400);
    }

    return $token;
}
?>