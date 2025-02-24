<?php

include_once("query.php");

class Usuario extends Query {
    public $estado;
    public $nombre;
    public $clave;
    public $id;

    public function __construct($estado,$nombre,$clave,$id){
        $this->id=$id;
        $this->nombre=$nombre;
        $this->clave=$clave;
        $this->estado=$estado;
    }
    public function getUsuario(){
        return $this->nombre;
    }
    public function registrarUsuario($estado, $nombre, $clave, $id){
        $datos = [
            'estado' => $estado,
            'nombre' => $nombre,
            'clave' => $clave,
            'id' => $id
        ];
        return parent::insert('usuarios', $datos);
    }
    public function listarUsuarios() {
        try {
            $query = "SELECT u.*, p.estado FROM usuarios u INNER JOIN prestamos p ON u.id = p.id_usuario";
            $stmt = parent::getPdo()->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }
    public function modificarUsuario( $nombre, $clave, $id) {
        try {
            $query = "UPDATE usuarios SET id = ?, nombre = ? WHERE clave = ?";
            $stmt = parent::getPdo()->prepare($query);
            $stmt->execute([ $nombre, $clave, $id]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
    public function actualizarEstado(  $id, $estado ) {
        try {
            $query = "UPDATE prestamos SET estado = ? WHERE id = ?";
            $stmt = parent::getPdo()->prepare($query);
            $stmt->execute([$estado, $id]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
    public function actualizarClave( $nombre, $id, $clave){
        try {
            $query = "UPDATE usuarios SET clave = ? WHERE nombre = ? AND id = ?";
            $stmt = parent::getPdo()->prepare($query);
            $stmt->execute([$nombre, $id, $clave]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
    
}