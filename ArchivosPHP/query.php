<?php

class Query {
    private $pdo; // Variable para ldeclarar los objetos de conexion a base de datos.
    private $host = 'localhost'; // Varibale para la ruta de phpmyadin
    private $db = 'Biblioteca'; // Base de datos a la que vamos a entrar.
    private $user = 'root'; // Variable para almacenar nombre de usuario en PHPmyAdmin
    private $pass = 'root1'; // Variable para la contraseña del usuario en PHPmyAdmin

    public function __construct() {
        try {
            // Creación del objeto 
            $this->pdo = new PDO("mysql:host=$this->host;dbname=$this->db", $this->user, $this->pass);
            // Ponemos la siguiente sentencia por si salta un error
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    public function select($tabla, $condicion = '') {
        try {
            // Construye la consulta SQL base: "SELECT * FROM nombre_tabla"
            $query = "SELECT * FROM $tabla";
    
            // Si se proporciona una condición, se añade a la consulta SQL
            if ($condicion) {
                $query .= " WHERE $condicion"; // Ejemplo: "SELECT * FROM nombre_tabla WHERE columna = valor"
            }
    
            // Prepara la consulta SQL usando PDO para evitar inyecciones SQL
            $stmt = $this->pdo->prepare($query);
    
            // Ejecuta la consulta preparada
            $stmt->execute();
    
            // Retorna todos los resultados como un array asociativo
            // PDO::FETCH_ASSOC asegura que los datos se devuelvan como un array asociativo (clave-valor)
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Si ocurre un error durante la ejecución de la consulta, se captura la excepción
            // y se retorna `false` para indicar que algo salió mal
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

    /*
    
    @parametro string $nombre -> Nombre del usuario a modificar.
    @parametro string $clave -> clave de usuario
    @return bool -> Retorna true si el usuario es administrador y false si el usuario no es administrador.
    
    */ 

    public function adminUsuario($nombre,$clave){
        try{
            $query = new Query();

            $condicion="nombre=? and clave=?";
            $stmt = $this->pdo->prepare("select admin from usuarios where $condicion");
            $stmt = execute([$nombre,$clave]);
            $resultado=$stmt->fetch(PDO:FETCH_ASSOC);

            return $resultado ? (bool)$resultado['admin'] : false;

        }catch(PDOException $e) {
            return false;
        }
    }

    public function getPdo() {
        return $this->pdo;
    }
}
?>