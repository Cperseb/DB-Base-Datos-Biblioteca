<?php
require_once "../models/database.php";

class GestionPrestamosController {
    private $conexion;

    public function __construct() {
        $baseDatos = new Database();
        $this->conexion = $baseDatos->getConnection();
    }

    // 1️⃣ Obtener la lista de préstamos activos
    public function obtenerPrestamosActivos() {
        try {
            $sql = "SELECT p.*, u.Nombre AS NombreUsuario, e.Titulo 
                    FROM Prestamos p
                    JOIN Usuarios u ON p.IdUsuario = u.IdUsuario
                    JOIN Ejemplares e ON p.IdEjemplar = e.IdEjemplar
                    WHERE p.estado = 1";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // 2️⃣ Registrar la devolución de un libro
    public function devolverLibro($idPrestamo) {
        try {
            $this->conexion->beginTransaction();

            // Marcar el préstamo como finalizado
            $sql = "UPDATE Prestamos SET estado = 0 WHERE IdPrestamo = :idPrestamo";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(":idPrestamo", $idPrestamo, PDO::PARAM_INT);
            $stmt->execute();

            // Liberar el ejemplar
            $sqlEjemplar = "UPDATE Ejemplares SET Prestado = 0 WHERE IdEjemplar = (SELECT IdEjemplar FROM Prestamos WHERE IdPrestamo = :idPrestamo)";
            $stmtEjemplar = $this->conexion->prepare($sqlEjemplar);
            $stmtEjemplar->bindParam(":idPrestamo", $idPrestamo, PDO::PARAM_INT);
            $stmtEjemplar->execute();

            $this->conexion->commit();

            header("Location: ../views/gestionarPrestamosReservas.php?mensaje=" . urlencode("✅ Devolución registrada correctamente."));
            exit();
        } catch (PDOException $e) {
            $this->conexion->rollBack();
            header("Location: ../views/gestionarPrestamosReservas.php?mensaje=" . urlencode("❌ Error al registrar devolución."));
            exit();
        }
    }

    // 3️⃣ Aplicar sanción manualmente
    public function aplicarSancion($idUsuario) {
        try {
            $sql = "UPDATE Usuarios SET FechaSancionFin = DATE_ADD(CURDATE(), INTERVAL 4 WEEK) WHERE IdUsuario = :idUsuario";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(":idUsuario", $idUsuario, PDO::PARAM_INT);
            $stmt->execute();

            header("Location: ../views/gestionarPrestamosReservas.php?mensaje=" . urlencode("🚫 Sanción aplicada por retraso."));
            exit();
        } catch (PDOException $e) {
            header("Location: ../views/gestionarPrestamosReservas.php?mensaje=" . urlencode("❌ Error al aplicar sanción."));
            exit();
        }
    }

    // 4️⃣ Aprobar o rechazar renovación
    public function gestionarRenovacion($idPrestamo, $accion) {
        try {
            if ($accion === "aprobar_renovacion") {
                $sql = "UPDATE Prestamos SET FechaFin = DATE_ADD(FechaFin, INTERVAL 14 DAY) WHERE IdPrestamo = :idPrestamo";
            } elseif ($accion === "rechazar_renovacion") {
                $sql = "UPDATE Prestamos SET estado = 1 WHERE IdPrestamo = :idPrestamo";
            }

            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(":idPrestamo", $idPrestamo, PDO::PARAM_INT);
            $stmt->execute();

            $mensaje = ($accion === "aprobar_renovacion") ? "✅ Renovación aprobada." : "❌ Renovación rechazada.";
            header("Location: ../views/gestionarPrestamosReservas.php?mensaje=" . urlencode($mensaje));
            exit();
        } catch (PDOException $e) {
            header("Location: ../views/gestionarPrestamosReservas.php?mensaje=" . urlencode("❌ Error al gestionar la renovación."));
            exit();
        }
    }
}

// 📌 Manejo de solicitudes POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $controller = new GestionPrestamosController();

    $accion = $_POST["accion"] ?? null;
    $idPrestamo = $_POST["idPrestamo"] ?? null;

    switch ($accion) {
        case "devolver":
            $controller->devolverLibro($idPrestamo);
            break;
        case "sancionar":
            $controller->aplicarSancion($_POST["idUsuario"]);
            break;
        case "aprobar_renovacion":
        case "rechazar_renovacion":
            $controller->gestionarRenovacion($idPrestamo, $accion);
            break;
        default:
            header("Location: ../views/gestionarPrestamosReservas.php?mensaje=" . urlencode("❌ Acción no válida."));
            exit();
    }
}
?>
