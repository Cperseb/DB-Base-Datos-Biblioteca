<?php
require_once "../models/database.php";

class LibrosController {
    private $conexion;

    public function __construct() {
        $baseDatos = new Database();
        $this->conexion = $baseDatos->getConnection();
    }

    public function mostrarTablaLibros($filtro = "") {
        try {
            // Obtener los nombres de las columnas de forma dinámica
            $sqlColumnas = "SHOW COLUMNS FROM Libros";
            $stmtColumnas = $this->conexion->prepare($sqlColumnas);
            $stmtColumnas->execute();
            $columnas = $stmtColumnas->fetchAll(PDO::FETCH_COLUMN);

            // Base de la consulta para obtener los libros
            $sql = "SELECT l.ISBN, d.Titulo, d.ListaAutores, d.FechaPublicacion, d.Descripcion, d.Materia, d.NumEjemplares 
                    FROM Libros l 
                    JOIN Documentos d ON l.Titulo = d.Titulo";

            // Agregamos el filtro para su busqueda por los siguientes: TITULO,ListaAutores o ISBN
            if (!empty($filtro)) {
                $sql .= " WHERE d.Titulo LIKE :filtro 
                          OR d.ListaAutores LIKE :filtro 
                          OR l.ISBN LIKE :filtro";
            }

            $stmt = $this->conexion->prepare($sql);
            if (!empty($filtro)) {
                $filtro = "%$filtro%";
                $stmt->bindParam(":filtro", $filtro, PDO::PARAM_STR);
            }

            $stmt->execute();
            $libros = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($libros)) {
                return "<p>No se encontraron libros.</p>";
            }

            // Generar la tabla HTML de forma dinámica
            $tabla = "<table border='1'><thead><tr>";

            // Crear los encabezados (th) usando los nombres de las columnas
            foreach (array_keys($libros[0]) as $columna) {
                $tabla .= "<th>" . htmlspecialchars($columna) . "</th>";
            }

            $tabla .= "</tr></thead><tbody>";

            // Crear las filas (tr) con los datos
            foreach ($libros as $libro) {
                $tabla .= "<tr>";
                foreach ($libro as $valor) {
                    $tabla .= "<td>" . htmlspecialchars($valor) . "</td>";
                }
                $tabla .= "</tr>";
            }

            $tabla .= "</tbody></table>";
            return $tabla;

        } catch (PDOException $e) {
            return "<p>Error al obtener los libros: " . $e->getMessage() . "</p>";
        }
    }
}
?>
