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
            // Retornamos un error si alguno está vacío
            $error = "El usuario y la clave son obligatorios.";
            include '../views/login.php'; // Mostramos el formulario de login con el error
            return;
        }

        // Creamos una instancia de la clase Query para realizar la consulta
        $query = new Query();
        
        // Consultamos si el usuario existe con la clave correspondiente
        $condicion = "nombre = ? AND clave = ?";
        $result = $query->select('usuarios', $condicion, [$usuario, $clave]);

        if ($result) {
            // Si encontramos el usuario, verificamos si la contraseña es correcta
            $usuarioEncontrado = $result[0]; // Tomamos el primer resultado de la consulta
            if ($usuarioEncontrado['clave'] === $clave) {
                // Si la clave es correcta, verificamos si el usuario es administrador
                $esAdmin = $query->adminUsuario($usuario, $clave);
                
                if ($esAdmin) {
                    // Si el usuario es administrador, redirigimos a la página de administración
                    $_SESSION['usuario'] = $usuario;
                    $_SESSION['admin'] = true;
                    header('Location: ../views/panelAdministrador.php');
                    exit();
                } else {
                    // Si no es administrador, lo redirigimos a la página de usuario normal
                    $_SESSION['usuario'] = $usuario;
                    $_SESSION['admin'] = false;
                    header('Location: ../views/panelUsuario.php');
                    exit();
                }
            } else {
                // Si la contraseña es incorrecta, mostramos un error y dejamos el campo de contraseña vacío
                $error = "Contraseña incorrecta.";
                include '../views/login.php'; // Volvemos a mostrar el login con el error
            }
        } else {
            // Si el usuario no existe en la base de datos, mostramos un error
            $error = "El usuario no existe.";
            include '../views/login.php'; // Mostramos el formulario de login con el error
        }
    }
}

// Creamos una instancia del controlador de login y llamamos al método login para manejar el login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $loginController = new LoginController();
    $loginController->login($_POST['usuario'], $_POST['clave']);
}
?>
