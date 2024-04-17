<?php
header("Access-Control-Allow-Origin: *");   
header("Content-Type: application/json; charset=UTF-8");    
header("Access-Control-Allow-Methods: POST, DELETE, OPTIONS");    
header("Access-Control-Max-Age: 3600");    
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With"); 

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {    
    return 0;    
}  

require '../dao/UsuariosDao.class.php';
$datos = UsuariosDao::getInstance();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    
    $correcto = true;
    if (empty($_GET['id']) || empty($_GET['token'])) {
        $correcto = false;
    }

    if($correcto){
        $valido = $datos->validarToken($_GET['id'],$_GET['token']);
        $response["success"] = $valido;
        $response["code"] = $valido ? 200 : 401;
        $response["mensaje"] = $valido ? "Ok" : "Unauthorized";
        http_response_code(200);
        echo json_encode($response);
    } else {
        $response["mensaje"] = "Bad request";
        $response["code"] = 400;
        http_response_code(400);
        echo json_encode($response);
    }
} else {
    $response["mensaje"] = "Method Not Allowed";
    $response["code"] = 405;
    http_response_code(405);
    echo json_encode($response);
}
?>