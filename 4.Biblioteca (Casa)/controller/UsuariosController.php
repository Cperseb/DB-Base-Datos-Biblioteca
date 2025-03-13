<?php
require_once "../models/database.php";

class UsuariosController {
    private $conexion;

    public function __construct() {
        try {
            $baseDatos = new Database();
            $this->conexion = $baseDatos->getConnection();
        } catch (PDOException $e) {
            die("❌ Error de conexión a la base de datos: " . $e->getMessage());
        }
    }

    // 1️⃣ Obtener la lista de todos los usuarios
    public function obtenerUsuarios() {
        try {
            $sql = "SELECT u.IdUsuario, u.Nombre, u.Email, u.Direccion, u.Telefono, u.Curso, u.Admin, 
                           (SELECT COUNT(*) FROM Prestamos p WHERE p.IdUsuario = u.IdUsuario AND p.estado = 1) AS librosPrestados 
                    FROM Usuarios u";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("❌ Error al obtener usuarios: " . $e->getMessage());
        }
    }

    // 2️⃣ Añadir usuario
    public function agregarUsuario($nombre, $email, $direccion, $telefono, $curso, $clave, $admin) {
        try {
            $sql = "INSERT INTO Usuarios (Nombre, Email, Direccion, Telefono, Curso, Clave, Admin) 
                    VALUES (:nombre, :email, :direccion, :telefono, :curso, :clave, :admin)";
            $stmt = $this->conexion->prepare($sql);
            $hashedClave = password_hash($clave, PASSWORD_DEFAULT);
            $stmt->bindParam(":nombre", $nombre, PDO::PARAM_STR);
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->bindParam(":direccion", $direccion, PDO::PARAM_STR);
            $stmt->bindParam(":telefono", $telefono, PDO::PARAM_STR);
            $stmt->bindParam(":curso", $curso, PDO::PARAM_INT);
            $stmt->bindParam(":clave", $clave, PDO::PARAM_STR); 
            $stmt->bindParam(":admin", $admin, PDO::PARAM_BOOL);
            $stmt->execute();

            header("Location: ../views/gestionarUsuarios.php?mensaje=" . urlencode("✅ Usuario añadido correctamente."));
            exit();
        } catch (PDOException $e) {
            header("Location: ../views/gestionarUsuarios.php?mensaje=" . urlencode("❌ Error al añadir usuario: " . $e->getMessage()));
            exit();
        }
    }

    // 3️⃣ Editar usuario
    public function editarUsuario($idUsuario, $nombre, $email, $direccion, $telefono, $curso, $admin) {
        try {
            $sql = "UPDATE Usuarios SET Nombre = :nombre, Email = :email, Direccion = :direccion, 
                    Telefono = :telefono, Curso = :curso, Admin = :admin WHERE IdUsuario = :idUsuario";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(":idUsuario", $idUsuario, PDO::PARAM_INT);
            $stmt->bindParam(":nombre", $nombre, PDO::PARAM_STR);
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->bindParam(":direccion", $direccion, PDO::PARAM_STR);
            $stmt->bindParam(":telefono", $telefono, PDO::PARAM_STR);
            $stmt->bindParam(":curso", $curso, PDO::PARAM_INT);
            $stmt->bindParam(":admin", $admin, PDO::PARAM_BOOL);
            $stmt->execute();

            header("Location: ../views/gestionarUsuarios.php?mensaje=" . urlencode("✅ Usuario actualizado correctamente."));
            exit();
        } catch (PDOException $e) {
            header("Location: ../views/gestionarUsuarios.php?mensaje=" . urlencode("❌ Error al actualizar usuario: " . $e->getMessage()));
            exit();
        }
    }

    // 4️⃣ Eliminar usuario
    public function eliminarUsuario($idUsuario) {
        try {
            $sql = "DELETE FROM Usuarios WHERE IdUsuario = :idUsuario";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(":idUsuario", $idUsuario, PDO::PARAM_INT);
            $stmt->execute();

            header("Location: ../views/gestionarUsuarios.php?mensaje=" . urlencode("✅ Usuario eliminado correctamente."));
            exit();
        } catch (PDOException $e) {
            header("Location: ../views/gestionarUsuarios.php?mensaje=" . urlencode("❌ Error al eliminar usuario: " . $e->getMessage()));
            exit();
        }
    }

    // 5️⃣ Aplicar sanción manualmente (bloquea préstamos por 4 semanas)
    public function aplicarSancion($idUsuario) {
        try {
            $sql = "UPDATE Usuarios SET FechaSancionFin = DATE_ADD(CURDATE(), INTERVAL 4 WEEK) WHERE IdUsuario = :idUsuario";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(":idUsuario", $idUsuario, PDO::PARAM_INT);
            $stmt->execute();

            header("Location: ../views/gestionarUsuarios.php?mensaje=" . urlencode("🚫 Sanción aplicada hasta " . date('Y-m-d', strtotime('+4 weeks'))));
            exit();
        } catch (PDOException $e) {
            header("Location: ../views/gestionarUsuarios.php?mensaje=" . urlencode("❌ Error al aplicar la sanción: " . $e->getMessage()));
            exit();
        }
    }
}

// Manejo de solicitudes POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $controller = new UsuariosController();

    $accion = $_POST["accion"] ?? null;
    $idUsuario = $_POST["idUsuario"] ?? null;
    $nombre = $_POST["nombre"] ?? null;
    $email = $_POST["email"] ?? null;
    $direccion = $_POST["direccion"] ?? null;
    $telefono = $_POST["telefono"] ?? null;
    $curso = $_POST["curso"] ?? null;
    $clave = $_POST["clave"] ?? null;
    $admin = isset($_POST["admin"]);

    switch ($accion) {
        case "agregar":
            $controller->agregarUsuario($nombre, $email, $direccion, $telefono, $curso, $clave, $admin);
            break;
        case "editar":
            $controller->editarUsuario($idUsuario, $nombre, $email, $direccion, $telefono, $curso, $admin);
            break;
        case "eliminar":
            $controller->eliminarUsuario($idUsuario);
            break;
        case "sancionar":
            $controller->aplicarSancion($idUsuario);
            break;
        default:
            header("Location: ../views/gestionarUsuarios.php?mensaje=" . urlencode("❌ Acción no válida."));
            exit();
    }
}
?>
