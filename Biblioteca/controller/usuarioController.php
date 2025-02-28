<?php
require_once "../models/database.php";
require_once "../models/sesion.php";

class UsuarioController {
    private $conexion;

    public function __construct() {
        $baseDatos = new Database();
        $this->conexion = $baseDatos->getConnection();
    }

    public function iniciarSesion($nombre, $clave) {
        try {
            // Verificar si los campos están vacíos
            if (empty($nombre) || empty($clave)) {
                header("Location: ../views/login.php?error=Todos los campos son obligatorios&nombre=" . urlencode($nombre));
                exit();
            }

            // Buscar el usuario en la base de datos
            $sql = "SELECT IdUsuario, Nombre, Clave, Admin FROM Usuarios WHERE Nombre = :nombre LIMIT 1";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(":nombre", $nombre, PDO::PARAM_STR);
            $stmt->execute();
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario) {
                // Verificar la clave ingresada
                if ($usuario['Clave'] === $clave) {
                    // Guardamos solo los datos esenciales en sesión
                    $datosUsuario = [
                        'IdUsuario' => $usuario['IdUsuario'],
                        'Nombre' => $usuario['Nombre'],
                        'Admin' => $usuario['Admin']
                    ];
                    
                    // Iniciar sesión
                    Sesion::iniciarSesion($datosUsuario);

                    // Redirigir según el tipo de usuario
                    if ($usuario['Admin'] == 1) {
                        header("Location: ../views/panelAdministrador.php");
                    } else {
                        header("Location: ../views/panelUsuario.php");
                    }
                    exit();
                } else {
                    // Si la clave es incorrecta, mantenemos el nombre ingresado
                    header("Location: ../views/login.php?error=Clave incorrecta&nombre=" . urlencode($nombre));
                    exit();
                }
            } else {
                header("Location: ../views/login.php?error=Usuario no encontrado");
                exit();
            }
        } catch (PDOException $e) {
            header("Location: ../views/login.php?error=Error en el servidor");
            exit();
        }
    }
}

// Procesar el formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $nombre = trim($_POST['nombre']);
    $clave = trim($_POST['clave']);

    $controlador = new UsuarioController();
    $controlador->iniciarSesion($nombre, $clave);
}
?>
