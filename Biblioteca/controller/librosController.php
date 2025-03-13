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

    public function registrarPrestamo($usuarioId, $idEjemplar) {
        try {
            // 3.1 Comprobar existencias del ejemplar
            $sqlDisponibilidad = "SELECT e.IdEjemplar 
                                 FROM Ejemplares e 
                                 LEFT JOIN Prestamos p ON e.IdEjemplar = p.IdEjemplar AND p.estado = true
                                 WHERE e.IdEjemplar = :idEjemplar AND p.IdPrestamo IS NULL";
            
            $stmtDisponibilidad = $this->conexion->prepare($sqlDisponibilidad);
            $stmtDisponibilidad->bindParam(":idEjemplar", $idEjemplar, PDO::PARAM_INT);
            $stmtDisponibilidad->execute();
            
            if (!$stmtDisponibilidad->fetch()) {
                return "El ejemplar no está disponible para préstamo.";
            }

            // 3.2 Comprobar máximo préstamos activos (máximo 6)
            $sqlPrestamos = "SELECT COUNT(*) FROM Prestamos 
                            WHERE IdUsuario = :usuarioId AND estado = true";
            $stmtPrestamos = $this->conexion->prepare($sqlPrestamos);
            $stmtPrestamos->bindParam(":usuarioId", $usuarioId, PDO::PARAM_INT);
            $stmtPrestamos->execute();
            $prestamosActivos = $stmtPrestamos->fetchColumn();

            if ($prestamosActivos >= 6) {
                return "El usuario ya tiene el máximo de 6 préstamos activos.";
            }

            // 3.3 Calcular fecha de devolución (3 semanas)
            $fechaInicio = date('Y-m-d');
            $fechaDevolucion = date('Y-m-d', strtotime('+3 weeks'));

            // Registrar el préstamo
            $sqlRegistrar = "INSERT INTO Prestamos (IdUsuario, IdEjemplar, FechaInicio, FechaFin, estado) 
                            VALUES (:usuarioId, :idEjemplar, :fechaInicio, :fechaFin, true)";
            $stmtRegistrar = $this->conexion->prepare($sqlRegistrar);
            $stmtRegistrar->bindParam(":usuarioId", $usuarioId, PDO::PARAM_INT);
            $stmtRegistrar->bindParam(":idEjemplar", $idEjemplar, PDO::PARAM_INT);
            $stmtRegistrar->bindParam(":fechaInicio", $fechaInicio, PDO::PARAM_STR);
            $stmtRegistrar->bindParam(":fechaFin", $fechaDevolucion, PDO::PARAM_STR);
            $stmtRegistrar->execute();

            return "Préstamo registrado con éxito. Fecha de devolución: $fechaDevolucion";
        } catch (PDOException $e) {
            return "Error al registrar el préstamo: " . $e->getMessage();
        }
    }

    // 4. Listar documentos prestados para un usuario
    public function listarPrestamosPorUsuario($usuarioId) {
        try {
            $sql = "SELECT p.IdPrestamo, p.IdEjemplar, d.Titulo, p.FechaInicio, p.FechaFin, 
                    p.estado, p.Observacion
                    FROM Prestamos p
                    JOIN Ejemplares e ON p.IdEjemplar = e.IdEjemplar
                    JOIN Documentos d ON e.Titulo = d.Titulo
                    WHERE p.IdUsuario = :usuarioId
                    ORDER BY p.FechaInicio DESC";
            
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(":usuarioId", $usuarioId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // 5. Listar documentos no devueltos en fecha para un usuario
    public function listarPrestamosVencidos($usuarioId) {
        try {
            $sql = "SELECT p.IdPrestamo, p.IdEjemplar, d.Titulo, p.FechaInicio, p.FechaFin,
                    DATEDIFF(CURRENT_DATE, p.FechaFin) as DiasRetraso,
                    p.Observacion
                    FROM Prestamos p
                    JOIN Ejemplares e ON p.IdEjemplar = e.IdEjemplar
                    JOIN Documentos d ON e.Titulo = d.Titulo
                    WHERE p.IdUsuario = :usuarioId 
                    AND p.estado = true 
                    AND p.FechaFin < CURRENT_DATE
                    ORDER BY p.FechaFin ASC";
            
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(":usuarioId", $usuarioId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }
}
?>
