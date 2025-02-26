<?php
require_once 'query.php'; // Importamos la conexión a la BD

class Usuario {
    private $db;

    public function __construct() {
        $this->db = new Query(); // Usamos la clase Query para la conexión
    }

    public function buscarUsuario($nombre) {
        // Buscamos el usuario en la base de datos
        $resultado = $this->db->select("Usuarios", "Nombre = '$nombre'");
        
        return $resultado ? $resultado[0] : null; // Retornamos el primer usuario encontrado o null
    }
}
