<?php
session_start();  // Iniciar la sesión

// Incluir el modelo de la base de datos
require_once '../model/query.php';

// Verificar si el usuario ya está logueado
if (isset($_SESSION['usuario'])) {
    // Si está logueado y es administrador, redirigir a panelAdministrador
    if ($_SESSION['admin'] === true) {
        header('Location: ../views/panelAdministrador.php');
    } else {
        // Si está logueado pero no es administrador, redirigir a panelUsuario
        header('Location: ../views/panelUsuario.php');
    }
    exit();
}

class LoginController {

    // Función que maneja la lógica del login
    public function login($usuario, $clave) {
        // Verificamos si el usuario o la clave están vacíos
        if (empty($usuario) || empty($clave)) {
            $error = "El usuario y la clave son obligatorios.";
            include '../views/login.php';
            return;
        }

        // Creamos una instancia de la clase Query para realizar la consulta
        $query = new Query();
        
        // Consultamos si el usuario existe con la clave correspondiente
        $condicion = "nombre = ? AND clave = ?";
        $result = $query->select('usuarios', $condicion, [$usuario, $clave]);

        if ($result && !empty($result)) {
            // Si encontramos el usuario, ya no necesitamos verificar la contraseña de nuevo
            // porque la consulta SQL ya lo hizo
            $esAdmin = $query->adminUsuario($usuario, $clave);
            
            // Guardamos la información en la sesión
            $_SESSION['usuario'] = $usuario;
            $_SESSION['admin'] = $esAdmin;
            
            // Redirigimos según el tipo de usuario
            if ($esAdmin) {
                header('Location: ../views/panelAdministrador.php');
            } else {
                header('Location: ../views/panelUsuario.php');
            }
            exit();
        } else {
            // Si no encontramos resultados, mostramos un error genérico
            $error = "Usuario o contraseña incorrectos.";
            include '../views/login.php';
        }
    }
}

// Creamos una instancia del controlador de login y llamamos al método login para manejar el login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $loginController = new LoginController();
    $loginController->login($_POST['usuario'], $_POST['clave']);
}
?>
