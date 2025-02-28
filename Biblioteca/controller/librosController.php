<?php
require_once "../models/database.php"; // Asegúrate de que la ruta sea correcta

class LibrosController {
    private $conexion;

    public function __construct() {
        $baseDatos = new Database(); // Obtener la conexión
        $this->conexion = $baseDatos->getConnection(); // Cambié a obtenerConexion()
    }

    public function mostrarTablaLibros() {
        try {
            // Obtener los nombres de las columnas de la tabla Libros de forma dinámica
            $sqlColumnas = "SHOW COLUMNS FROM Libros";
            $stmtColumnas = $this->conexion->prepare($sqlColumnas);
            $stmtColumnas->execute();
            $columnas = $stmtColumnas->fetchAll(PDO::FETCH_COLUMN);

            // Realiza la consulta para obtener los libros
            $sql = "SELECT * FROM Libros";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute();
            $libros = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Si no hay libros, muestra un mensaje
            if (empty($libros)) {
                return "No se encontraron libros.";
            }

            // Generar la tabla HTML de forma dinámica
            $tabla = "<table><thead><tr>";

            // Crear los encabezados (th) usando los nombres de las columnas
            foreach ($columnas as $columna) {
                $tabla .= "<th>" . htmlspecialchars($columna) . "</th>";
            }

            $tabla .= "</tr></thead><tbody>";

            // Crear las filas (tr) con los datos de los libros
            foreach ($libros as $libro) {
                $tabla .= "<tr>";
                foreach ($columnas as $columna) {
                    $tabla .= "<td>" . htmlspecialchars($libro[$columna]) . "</td>";
                }
                $tabla .= "</tr>";
            }

            $tabla .= "</tbody></table>";

            return $tabla;

        } catch (PDOException $e) {
            // Captura el error si ocurre algún problema en la consulta
            return "Error al obtener los libros: " . $e->getMessage();
        }
    }
}
?>
