<?php

/**
 * Summary of SeccionesDao
 */
class UsuariosDao
{
    private $bd;
    static $_instance;

    private function __construct()
    {
        require '../db/Db.class.php';
        $this->bd = Db::getInstance(1);
    }

    public static function getInstance()
    {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Summary of getSecciones
     * @return boolean
     */
    public function registrarUsuario($nombre, $apellido, $email, $password, $fecha_nacimiento, $sexo, $telefono, $direccion, $ciudad, $estado, $codigo_postal, $pais, $foto_perfil, $estatus)
    {
        $insert = "INSERT INTO usuarios(id, ";
        $values = "VALUES(NULL,";

        if (!empty($nombre)) {
            $insert .= "nombre, ";
            $values .= "'$nombre', ";
        }

        if (!empty($apellido)) {
            $insert .= "apellido, ";
            $values .= "'$apellido', ";
        }

        if (!empty($email)) {
            $insert .= "email, ";
            $values .= "'$email', ";
        }

        if (!empty($password)) {
            $insert .= "password, ";
            $values .= "'$password', ";
        }

        if (!empty($fecha_nacimiento)) {
            $insert .= "fecha_nacimiento, ";
            $values .= "'$fecha_nacimiento', ";
        }

        if (!empty($sexo)) {
            $insert .= "sexo, ";
            $values .= "'$sexo', ";
        }

        if (!empty($telefono)) {
            $insert .= "telefono, ";
            $values .= "'$telefono', ";
        }

        if (!empty($direccion)) {
            $insert .= "direccion, ";
            $values .= "'$direccion', ";
        }

        if (!empty($ciudad)) {
            $insert .= "ciudad, ";
            $values .= "'$ciudad', ";
        }

        if (!empty($estado)) {
            $insert .= "estado, ";
            $values .= "'$estado', ";
        }

        if (!empty($codigo_postal)) {
            $insert .= "codigo_postal, ";
            $values .= "'$codigo_postal', ";
        }

        if (!empty($pais)) {
            $insert .= "pais, ";
            $values .= "'$pais', ";
        }

        if (!empty($foto_perfil)) {
            $insert .= "foto_perfil, ";
            $values .= "'$foto_perfil', ";
        }

        if (!empty($estatus)) {
            $insert .= "estatus, ";
            $values .= "'$estatus', ";
        }

        $insert .= "fecha_registro)  ";
        $values .= "(NOW()))";
        $sentence = $insert . $values;
        $usr = $this->bd->ejecutarPlus($sentence);
        if($usr){
            $insertRoles = "INSERT INTO usuarios_roles(id, id_usuario, id_rol) VALUES(null, $usr,1)";
            return $this->bd->ejecutar($insertRoles);
        }else{
            return false;
        }
    }

    public function actualizarUsuario($id, $nombre, $apellido, $email, $password, $fecha_nacimiento, $sexo, $telefono, $direccion, $ciudad, $estado, $codigo_postal, $pais, $foto_perfil, $estatus)
    {
        $update = "UPDATE usuarios SET ";

        if (!empty($nombre)) {
            $update .= "nombre = '$nombre', ";
        }

        if (!empty($apellido)) {
            $update .= "apellido='$apellido', ";
        }

        if (!empty($email)) {
            $update .= "email='$email', ";
        }

        if (!empty($password)) {
            $update .= "password='$password', ";
        }

        if (!empty($fecha_nacimiento)) {
            $update .= "fecha_nacimiento='$fecha_nacimiento', ";
        }

        if (!empty($sexo)) {
            $update .= "sexo='$sexo', ";
        }

        if (!empty($telefono)) {
            $update .= "telefono='$telefono', ";
        }

        if (!empty($direccion)) {
            $update .= "direccion='$direccion', ";
        }

        if (!empty($ciudad)) {
            $update .= "ciudad='$ciudad', ";
        }

        if (!empty($estado)) {
            $update .= "estado='$estado', ";
        }

        if (!empty($codigo_postal)) {
            $update .= "codigo_postal='$codigo_postal', ";
        }

        if (!empty($pais)) {
            $update .= "pais='$pais', ";
        }

        if (!empty($foto_perfil)) {
            $update .= "foto_perfil'$foto_perfil', ";
        }

        if (!empty($estatus)) {
            $update .= "estatus='$estatus', ";
        }

        $update .= " fecha_updated=NOW()";
        $update .= " WHERE id=$id";
        return $this->bd->ejecutar($update);
    }

    public function getAllUsuarios()
    {
        $que = "SELECT * FROM usuarios";
        return $this->bd->ObtenerConsulta($que);
    }

    public function getUsuarioById($id)
    {
        $que = "SELECT * FROM usuarios WHERE id='$id' ";
        return $this->bd->ObtenerConsulta($que);
    }
    public function getUsuarioByEmail($email)
    {
        $que = "SELECT * FROM usuarios WHERE email='$email' ";
        return $this->bd->ObtenerConsulta($que);
    }

    public function validarUsuario($email, $password){
        $que = "SELECT id,nombre, apellido, email FROM usuarios WHERE email='$email' AND password='$password'";
        $user = $this->bd->ObtenerConsulta($que);
        if(!empty($user)){

            $roles = $this->obtenerRolesById($user[0]["id"]);
            $user[0]["roles"]= $roles;

            $user[0]["token"] = $this->crearToken();
            $this->saveToken($user[0]["id"],$user[0]["token"]);
        }
        return $user;
    }

    public function saveToken($id, $token){
        $this->deleteToken($id);
        $que="INSERT INTO token(id,token,expires,created) 
            VALUES ('$id','$token',now(),now())";
        $this->bd->ejecutar($que);
    }

    public function deleteToken($id){
        $delete = "DELETE FROM token WHERE id=$id";
        return $this->bd->ejecutar($delete);
    }

    public function crearToken(){
        return bin2hex(random_bytes(16));
    }

    public function validarToken($id, $token){
        $que ="SELECT * FROM token WHERE id='$id' AND token='$token'";        
        $array = $this->bd->ObtenerConsulta($que);        
        return !empty($array);
    }

    public function obtenerRolesById($id)
    {
        $que = "SELECT UR.id_rol, R.nombre FROM usuarios_roles AS UR 
        INNER JOIN roles AS R ON UR.id_rol=R.id 
        WHERE id_usuario='$id' ";
        return $this->bd->ObtenerConsulta($que);
    }

    public function validarRolUsuario($id_usuario,$id_rol)
    {
        $que = "SELECT id FROM usuarios_roles
        WHERE id_usuario='$id_usuario' AND id_rol=$id_rol";
        return empty($this->bd->ObtenerConsulta($que));
    }

    public function obtenerRolesByEmail($email)
    {
        $que = "SELECT UR.id_rol, R.nombre FROM usuarios_roles AS UR 
        INNER JOIN roles AS R ON UR.id_rol=R.id
        INNER JOIN usuarios AS U ON UR.id_usuario=U.id 
        WHERE email='$email' ";
        return $this->bd->ObtenerConsulta($que);
    }

    public function obtenerRolesByToken($token)
    {
        $que = "SELECT UR.id_rol, R.nombre FROM usuarios_roles AS UR 
        INNER JOIN roles AS R ON UR.id_rol=R.id
        INNER JOIN token AS T ON UR.id_usuario=T.id
        WHERE token = '$token'";
        return $this->bd->ObtenerConsulta($que);
    }

    public function agregarRol($id_usuario, $id_rol){
        $insert = "INSERT INTO usuarios_roles(id, id_usuario, id_rol)"
        ."VALUES(null,$id_usuario, $id_rol)";
        return $this->bd->ejecutar($insert);
    }

    public function quitarRol($id_usuario, $id_rol){
        $insert = "DELETE FROM usuarios_roles WHERE id_usuario=$id_usuario AND id_rol=$id_rol";
        return $this->bd->ejecutar($insert);
    }

    public function obtenerAllRoles()
    {
        $que = "SELECT * FROM roles";
        return $this->bd->ObtenerConsulta($que);
    }

}

?>