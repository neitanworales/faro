<?php
header("Access-Control-Allow-Origin: *");   
header("Content-Type: application/json; charset=UTF-8");    
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE OPTIONS");    
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
    $datosIncorrectos = " Hace falta: nombre, apellido, email, password";
    if (empty($input['nombre']) || empty($input['apellido']) || empty($input['email']) || empty($input['password'])) {
        $correcto = false;
    }

    if ($correcto) {

        $nombre = !empty($input['nombre']) ? $input['nombre'] : null;
        $email = !empty($input['email']) ? $input['email'] : null;
        $password = !empty($input['password']) ? $input['password'] : null;
        $apellido = !empty($input['apellido']) ? $input['apellido'] : null;
        $fecha_nacimiento = !empty($input['fecha_nacimiento']) ? $input['fecha_nacimiento'] : null;
        $sexo = !empty($input['sexo']) ? $input['sexo'] : null;
        $telefono = !empty($input['telefono']) ? $input['telefono'] : null;
        $direccion = !empty($input['direccion']) ? $input['direccion'] : null;
        $ciudad = !empty($input['ciudad']) ? $input['ciudad'] : null;
        $estado = !empty($input['estado']) ? $input['estado'] : null;
        $codigo_postal = !empty($input['codigo_postal']) ? $input['codigo_postal'] : null;
        $pais = !empty($input['pais']) ? $input['pais'] : null;
        $foto_perfil = !empty($input['foto_perfil']) ? $input['foto_perfil'] : null;
        $estatus = 'R';

        $busqueda = $datos->getUsuarioByEmail($email);
        if (!empty($busqueda)) {
            $response["mensaje"] = "Ya existe un usuario con ese correo";
            $response["code"] = 400;
            http_response_code(400);
            echo json_encode($response);
        } else {
            if ($datos->registrarUsuario($nombre, $apellido, $email, $password, $fecha_nacimiento, $sexo, $telefono, $direccion, $ciudad, $estado, $codigo_postal, $pais, $foto_perfil, $estatus)) {
                $response["mensaje"] = "Guardado correctamente";
                $response["code"] = 201;
                http_response_code(201);
                echo json_encode($response);
            } else {
                $response["mensaje"] = "Ocurrió algún error al guardar";
                $response["code"] = 500;
                http_response_code(500);
                echo json_encode($response);
            }
        }
    } else {
        $response["mensaje"] = "Bad request";
        $response["resultado"] = $datosIncorrectos;
        $response["code"] = 400;
        http_response_code(400);
        echo json_encode($response);
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {

    $inputJSON = file_get_contents('php://input');
    $input = json_decode($inputJSON, TRUE);

    $correcto = true;
    $datosIncorrectos = " Hace falta: id";

    if (empty($_GET['id'])) {
        $correcto = false;
    }

    if ($correcto) {
        $id=$_GET['id'];
        $nombre = !empty($input['nombre']) ? $input['nombre'] : null;
        $email = !empty($input['email']) ? $input['email'] : null;
        $password = !empty($input['password']) ? $input['password'] : null;
        $apellido = !empty($input['apellido']) ? $input['apellido'] : null;
        $fecha_nacimiento = !empty($input['fecha_nacimiento']) ? $input['fecha_nacimiento'] : null;
        $sexo = !empty($input['sexo']) ? $input['sexo'] : null;
        $telefono = !empty($input['telefono']) ? $input['telefono'] : null;
        $direccion = !empty($input['direccion']) ? $input['direccion'] : null;
        $ciudad = !empty($input['ciudad']) ? $input['ciudad'] : null;
        $estado = !empty($input['estado']) ? $input['estado'] : null;
        $codigo_postal = !empty($input['codigo_postal']) ? $input['codigo_postal'] : null;
        $pais = !empty($input['pais']) ? $input['pais'] : null;
        $foto_perfil = !empty($input['foto_perfil']) ? $input['foto_perfil'] : null;
        $estatus = !empty($input['estatus']) ? $input['estatus'] : null;

        if ($datos->actualizarUsuario($id, $nombre, $apellido, $email, $password, $fecha_nacimiento, $sexo, $telefono, $direccion, $ciudad, $estado, $codigo_postal, $pais, $foto_perfil, $estatus)) {
            $response["mensaje"] = "Guardado correctamente";
            $response["code"] = 200;
            http_response_code(200);
            echo json_encode($response);
        } else {
            $response["mensaje"] = "Ocurrió algún error al actualizar";
            $response["code"] = 500;
            http_response_code(500);
            echo json_encode($response);
        }

    } else {
        $response["mensaje"] = "Bad request";
        $response["resultado"] = $datosIncorrectos;
        $response["code"] = 400;
        http_response_code(400);
        echo json_encode($response);
    }

}
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = $_GET['id'];
    if (empty($id)) {
        $response['resultado'] = $datos->getAllUsuarios();
        $response["mensaje"] = "Ok";
        $response["code"] = 200;
        http_response_code(200);
        echo json_encode($response);
    } else {
        $response['resultado'] = $datos->getUsuarioById($id);
        $response["mensaje"] = "Ok";
        $response["code"] = 200;
        http_response_code(200);
        echo json_encode($response);
    }

}
?>