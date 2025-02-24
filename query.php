<?php

class Query {
    private $pdo;
    private $host = 'localhost';
    private $db = 'Libreria';
    private $user = 'root';
    private $pass = 'root';

    public function __construct() {
        try {
            $this->pdo = new PDO("mysql:host=$this->host;dbname=$this->db", $this->user, $this->pass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }
    public function select($tabla, $condicion = '') {
        try {
            $query = "SELECT * FROM $tabla";
            if ($condicion) {
                $query .= " WHERE $condicion";
            }
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }
    public function insert($tabla, $datos) {
        try {
            $columnas = implode(', ', array_keys($datos));
            $valores = implode(', ', array_fill(0, count($datos), '?'));
            $query = "INSERT INTO $tabla ($columnas) VALUES ($valores)";
            
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(array_values($datos));
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getPdo() {
        return $this->pdo;
    }
}
?>