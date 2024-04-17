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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputJSON = file_get_contents('php://input');
    $input = json_decode($inputJSON, TRUE);

    $correcto = true;
    $datosIncorrectos = " hacen falta campos";
    if (empty($input['email']) || empty($input['password'])) {
        $correcto = false;
    }

    if($correcto){

        $result = $datos->validarUsuario($input['email'],$input['password']);
        if(empty($result)){
            $response["mensaje"] = "Unauthorized";
            $response["code"] = 401;
            http_response_code(401);
            echo json_encode($response);
        }else{
            $response["code"] = 200;
            $response["mensaje"] = "Ok";
            $response["usuario"] = $result[0];
            http_response_code(200);
            echo json_encode($response);
        }

    } else {
        $response["mensaje"] = "Bad request";
        $response["resultado"] = $datosIncorrectos;
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