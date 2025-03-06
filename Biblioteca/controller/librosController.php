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

    public function registrarPrestamo($usuarioId, $idPrestamo) {
        try {
            // Comprobar existencias del ejemplar
            $sqlExistencias = "SELECT numeroPagina FROM Libros WHERE IdPrestamo = :idPrestamo";
            $stmtExistencias = $this->conexion->prepare($sqlExistencias);
            $stmtExistencias->bindParam(":idPrestamo", $idPrestamo, PDO::PARAM_STR);
            $stmtExistencias->execute();
            $existencias = $stmtExistencias->fetchColumn();

            if ($existencias <= 0) {
                return "<p>No hay ejemplares disponibles para el préstamo.</p>";
            }

            // Comprobar máximo préstamos en vigor del usuario
            $sqlPrestamos = "SELECT COUNT(*) FROM Prestamos WHERE IdUsuario = :usuarioId AND estado = 1";
            $stmtPrestamos = $this->conexion->prepare($sqlPrestamos);
            $stmtPrestamos->bindParam(":usuarioId", $usuarioId, PDO::PARAM_INT);
            $stmtPrestamos->execute();
            $prestamosActivos = $stmtPrestamos->fetchColumn();

            if ($prestamosActivos >= 6) {
                return "<p>El usuario ya tiene el máximo de 6 préstamos activos.</p>";
            }

            // Calcular fecha de devolución (3 semanas)
            $fechaDevolucion = date('Y-m-d', strtotime('+3 weeks'));

            // Registrar el préstamo
            $sqlRegistrar = "INSERT INTO Prestamos (IdUsuario, IdPrestamo, Fechafin) VALUES (:usuarioId, :idPrestamo, :fechaDevolucion)";
            $stmtRegistrar = $this->conexion->prepare($sqlRegistrar);
            $stmtRegistrar->bindParam(":usuarioId", $usuarioId, PDO::PARAM_INT);
            $stmtRegistrar->bindParam(":idPrestamo", $idPrestamo, PDO::PARAM_STR);
            $stmtRegistrar->bindParam(":fechaDevolucion", $fechaDevolucion, PDO::PARAM_STR);
            $stmtRegistrar->execute();

            return "<p>Préstamo registrado con éxito. Fecha de devolución: $fechaDevolucion</p>";

        } catch (PDOException $e) {
            return "<p>Error al registrar el préstamo: " . $e->getMessage() . "</p>";
        }
    }
}
?>
