<?php
include_once "./common/request.php";
include_once "./common/response.php";
include_once "./user/UserController.php";
include_once "./appart/AppartController.php";
include_once "./reservation/ReservationController.php";

header("Content-Type: application/json; charset=utf8");
header("Access-Control-Allow-Origin: *");

$response = new Response();

$request = new Request();

$request->headers = getallheaders();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$request->uri = explode( '/', $uri );

$request->method = $_SERVER['REQUEST_METHOD'];

$body = file_get_contents("php://input");
$request->body = json_decode($body);

function router($req, $res) {
    if (!isset($req->uri[2]) || $req->uri[2] == "") { 
        $res->status = 200;
        $res->content = json_encode(['message' => "Welcome to Appart-API"]);
        return;
    }

    switch ($req->uri[2]) {
        case "user":
            $controller = new UserController();
            $controller->dispatch($req, $res);
            break;

        case "appart":
            $controller = new AppartController();
            $controller->dispatch($req, $res);
            break;

        case "reservation":
            $controller = new ReservationController();
            $controller->dispatch($req, $res);
            break;

        default:
            $res->status = 404;
            $res->content = '{"message": "Url not found"}';
    }
}
try {
    router($request, $response);
} catch (Exception $e) {
    $response->status = 404;
    $response->content = '{"message":"'.$e->getMessage().'"}';
}

$response->send();

?>