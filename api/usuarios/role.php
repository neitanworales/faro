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
$id_rol = $_GET['id_rol'];
$email = $_GET['email'];
$token = $_GET['token'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $correcto = true;
    $datosIncorrectos = " Hace falta: id_usuario o id_rol";
    if (empty($id_usuario) || empty($id_rol)) {
        $correcto = false;
    }

    if ($correcto) {
        if (!$datos->validarRolUsuario($id_usuario, $id_rol)) {
            $response["mensaje"] = "Este usuario ya tiene asignado el rol solicitado";
            $response["code"] = 400;
            http_response_code(400);
            echo json_encode($response);
        } else {
            $response['success'] = $datos->agregarRol($id_usuario, $id_rol);
            $response["mensaje"] = "Ok";
            $response["code"] = 200;
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
} else if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $correcto = true;
    $datosIncorrectos = " Hace falta: id_usuario o id_rol";
    if (empty($id_usuario) || empty($id_rol)) {
        $correcto = false;
    }

    if ($correcto) {
        $id = $_GET['id'];
        $response['success'] = $datos->quitarRol($id_usuario, $id_rol);
        $response["mensaje"] = "Ok";
        $response["code"] = 200;
        http_response_code(200);
        echo json_encode($response);
    } else {
        $response["mensaje"] = "Bad request";
        $response["resultado"] = $datosIncorrectos;
        $response["code"] = 400;
        http_response_code(400);
        echo json_encode($response);
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!empty($token)) {
        $roles =  $datos->obtenerRolesByToken($token);
        $response['roles'] = $roles;
        $response['success'] = !empty($roles);
        $response["mensaje"] = "Ok";
        $response["code"] = 200;
        http_response_code(200);
        echo json_encode($response);
    } else if (!empty($email)) {
        $roles = $datos->obtenerRolesByEmail($email);
        $response['roles'] = $roles;
        $response['success'] = !empty($roles);
        $response["mensaje"] = "Ok";
        $response["code"] = 200;
        http_response_code(200);
        echo json_encode($response);
    } else {
        $response['roles'] = $datos->obtenerAllRoles();
        $response["mensaje"] = "Ok";
        $response["code"] = 200;
        http_response_code(200);
        echo json_encode($response);
    }
} else {
    $response["mensaje"] = "Method Not Allowed";
    $response["code"] = 405;
    http_response_code(405);
    echo json_encode($response);
}


?>