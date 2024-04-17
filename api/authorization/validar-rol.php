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

$id_usuario = $_GET['id_usuario'];
$token = $_GET['token'];
$expected = $_GET['expected'];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $correcto = true;
    if (empty($_GET['id']) || empty($_GET['token']) || empty($_GET['expected'])) {
        $correcto = false;
    }

    if($correcto){
        $valido = $datos->validarToken($_GET['id'],$_GET['token']);
        if($valido){
            $roles = $datos->obtenerRolesByToken($token);

            $porciones = explode(",", $expected);

            $found = false;
            foreach ($roles as $val) {
                foreach($porciones as $co){
                    if($val["nombre"]===$co){
                        $found = true;
                    }
                }
            }
            
            $response["success"] = $found;
            $response["code"] = 200;
            $response["mensaje"] = "Ok";
            http_response_code(200);
            echo json_encode($response);

        } else {
            $response["mensaje"] = "Unauthorized";
            $response["code"] = 401;
            http_response_code(401);
            echo json_encode($response);
        }

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