<?php

class Sesion {
    public static function iniciarSesion($usuario) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['usuario'] = $usuario;
    }

    public static function cerrarSesion() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    
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
    

    public static function obtenerUsuario() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['usuario']) ? $_SESSION['usuario'] : null;
    }

    public static function obtenerError() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $error = isset($_SESSION['error']) ? $_SESSION['error'] : null;
        unset($_SESSION['error']);
        return $error;
    }

    public static function guardarError($mensaje) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['error'] = $mensaje;
    }

    public static function obtenerUsuarioTemporal() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $usuarioTemporal = isset($_SESSION['usuario_temporal']) ? $_SESSION['usuario_temporal'] : "";
        unset($_SESSION['usuario_temporal']);
        return $usuarioTemporal;
    }

    public static function guardarUsuarioTemporal($usuario) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['usuario_temporal'] = $usuario;
    }
}

?>
