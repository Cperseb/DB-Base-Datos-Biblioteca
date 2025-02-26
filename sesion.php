<?php
class Sesion {
    public static function iniciar() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function establecerUsuario($usuario) {
        self::iniciar();
        $_SESSION['usuario'] = $usuario;
        unset($_SESSION['error'], $_SESSION['user_temp']); // Limpiamos errores previos
    }

    public static function obtenerUsuario() {
        self::iniciar();
        return isset($_SESSION['usuario']) ? $_SESSION['usuario'] : null;
    }

    public static function cerrar() {
        self::iniciar();
        session_destroy();
        header("Location: index.php");
        exit();
    }

    public static function establecerError($mensaje, $usuario = null) {
        self::iniciar();
        $_SESSION['error'] = $mensaje;
        if ($usuario) {
            $_SESSION['user_temp'] = $usuario; // Guardamos usuario si aplica
        } else {
            unset($_SESSION['user_temp']);
        }
    }

    public static function obtenerError() {
        self::iniciar();
        if (isset($_SESSION['error'])) {
            $error = $_SESSION['error'];
            unset($_SESSION['error']); // Eliminamos después de mostrarlo
            return $error;
        }
        return null;
    }

    public static function obtenerUsuarioTemporal() {
        self::iniciar();
        return isset($_SESSION['user_temp']) ? $_SESSION['user_temp'] : '';
    }
}
?>
