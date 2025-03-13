<?php
require_once "../models/database.php";

class PrestamosController {
    private $conexion;

    public function __construct() {
        $baseDatos = new Database();
        $this->conexion = $baseDatos->getConnection();
    }

    // 1️⃣ Verificar si el usuario está sancionado
    public function verificarSancion($idUsuario) {
        try {
            $sql = "SELECT FechaSancionFin FROM Usuarios WHERE IdUsuario = :idUsuario";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(":idUsuario", $idUsuario, PDO::PARAM_INT);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($resultado && !empty($resultado['FechaSancionFin'])) {
                $hoy = date("Y-m-d");
                if ($hoy < $resultado['FechaSancionFin']) {
                    return "Sancionado hasta " . $resultado['FechaSancionFin'];
                }
            }

            return false;
        } catch (PDOException $e) {
            return false;
        }
    }

    // 2️⃣ Obtener la lista de documentos disponibles para préstamo
    public function obtenerDocumentosDisponibles() {
        try {
            $sql = "SELECT d.Titulo, d.ISBN, d.NumEjemplares, d.Materia, d.Descripcion
                    FROM Documentos d
                    JOIN Ejemplares e ON d.Titulo = e.Titulo
                    WHERE e.Prestado = 0
                    GROUP BY d.Titulo";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // 3️⃣ Verificar cuántos libros tiene el usuario en préstamo
    public function contarPrestamosUsuario($idUsuario) {
        try {
            $sql = "SELECT COUNT(*) as total FROM Prestamos WHERE IdUsuario = :idUsuario AND estado = 1";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(":idUsuario", $idUsuario, PDO::PARAM_INT);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultado['total'];
        } catch (PDOException $e) {
            return 0;
        }
    }

    // 4️⃣ Solicitar un préstamo
    public function solicitarPrestamo($idUsuario, $titulo) {
        try {
            // Verificar sanción
            $sancion = $this->verificarSancion($idUsuario);
            if ($sancion) {
                return "🚫 No puedes solicitar préstamos hasta " . substr($sancion, 13);
            }

            // Verificar que el usuario no tenga más de 6 libros prestados
            $totalPrestamos = $this->contarPrestamosUsuario($idUsuario);
            if ($totalPrestamos >= 6) {
                return "Has alcanzado el límite de 6 préstamos.";
            }

            // Buscar un ejemplar disponible
            $sqlEjemplar = "SELECT IdEjemplar FROM Ejemplares WHERE Titulo = :titulo AND Prestado = 0 LIMIT 1";
            $stmtEjemplar = $this->conexion->prepare($sqlEjemplar);
            $stmtEjemplar->bindParam(":titulo", $titulo, PDO::PARAM_STR);
            $stmtEjemplar->execute();
            $ejemplar = $stmtEjemplar->fetch(PDO::FETCH_ASSOC);

            if (!$ejemplar) {
                return "No hay ejemplares disponibles para este documento.";
            }

            // Insertar el préstamo
            $sqlPrestamo = "INSERT INTO Prestamos (IdUsuario, IdEjemplar, FechaInicio, FechaFin, estado)
                            VALUES (:idUsuario, :idEjemplar, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 21 DAY), 1)";
            $stmtPrestamo = $this->conexion->prepare($sqlPrestamo);
            $stmtPrestamo->bindParam(":idUsuario", $idUsuario, PDO::PARAM_INT);
            $stmtPrestamo->bindParam(":idEjemplar", $ejemplar['IdEjemplar'], PDO::PARAM_INT);
            $stmtPrestamo->execute();

            // Marcar el ejemplar como prestado
            $sqlActualizar = "UPDATE Ejemplares SET Prestado = 1 WHERE IdEjemplar = :idEjemplar";
            $stmtActualizar = $this->conexion->prepare($sqlActualizar);
            $stmtActualizar->bindParam(":idEjemplar", $ejemplar['IdEjemplar'], PDO::PARAM_INT);
            $stmtActualizar->execute();

            return "✅ Préstamo realizado con éxito.";
        } catch (PDOException $e) {
            return "❌ Error al solicitar el préstamo.";
        }
    }

    // 5️⃣ Aplicar sanción si un usuario devuelve tarde
    public function aplicarSancion($idUsuario) {
        try {
            $sql = "UPDATE Usuarios SET FechaSancionFin = DATE_ADD(CURDATE(), INTERVAL 4 WEEK) WHERE IdUsuario = :idUsuario";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(":idUsuario", $idUsuario, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            return "❌ Error al aplicar la sanción.";
        }
    }

    // 6️⃣ Verificar si el usuario ha devuelto tarde un libro y aplicar sanción si es necesario
    public function verificarDevolucionTardia($idUsuario) {
        try {
            $sql = "SELECT COUNT(*) as tardios FROM Prestamos WHERE IdUsuario = :idUsuario AND FechaFin < CURDATE() AND estado = 1";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(":idUsuario", $idUsuario, PDO::PARAM_INT);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($resultado['tardios'] > 0) {
                $this->aplicarSancion($idUsuario);
                return "🚫 Sancionado hasta " . date('Y-m-d', strtotime('+4 weeks'));
            }

            return false;
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>
