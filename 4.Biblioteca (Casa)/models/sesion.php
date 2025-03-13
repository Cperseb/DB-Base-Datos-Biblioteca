<?php

class Sesion {
    // Método privado para iniciar la sesión si no está activa
    private static function iniciar() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Iniciar sesión con un usuario
    public static function iniciarSesion($usuario) {
        self::iniciar();
        $_SESSION['usuario'] = $usuario;
    }

    // Cerrar sesión y redirigir al login
    public static function cerrarSesion() {
        self::iniciar();

        // Eliminar todas las variables de sesión
        $_SESSION = [];

        // Destruir la sesión
        session_destroy();

        // Eliminar la cookie de sesión si existe
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
        }

        // Redirigir al login
        header("Location: ../views/login.php");
        exit();
    }

    // Obtener el usuario activo
    public static function obtenerUsuario() {
        self::iniciar();
        return $_SESSION['usuario'] ?? null;
    }

    // Manejo de errores en la sesión
    public static function obtenerError() {
        self::iniciar();
        $error = $_SESSION['error'] ?? null;
        unset($_SESSION['error']); // Limpiar error después de obtenerlo
        return $error;
    }

    public static function guardarError($mensaje) {
        self::iniciar();
        $_SESSION['error'] = $mensaje;
    }

    // Manejo de usuarios temporales (por ejemplo, cuando falla el login)
    public static function obtenerUsuarioTemporal() {
        self::iniciar();
        $usuarioTemporal = $_SESSION['usuario_temporal'] ?? "";
        unset($_SESSION['usuario_temporal']); // Eliminar después de obtenerlo
        return $usuarioTemporal;
    }

    public static function guardarUsuarioTemporal($usuario) {
        self::iniciar();
        $_SESSION['usuario_temporal'] = $usuario;
    }
}

?>
