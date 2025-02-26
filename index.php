<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Test básico
echo "Test de PHP";

// Verificar que los archivos existen
if (!file_exists('controllers/loginController.php')) {
    die('Error: No se encuentra controllers/loginController.php');
}

if (!file_exists('models/sesion.php')) {
    die('Error: No se encuentra models/sesion.php');
}

// Verificar que PHP puede incluir archivos
require_once 'controllers/loginController.php';
require_once 'models/sesion.php';

echo "<br>Si puedes ver esto, los archivos se incluyeron correctamente";

// Si el usuario ya está autenticado, lo redirigimos
if (Sesion::obtenerUsuario()) {
    header("Location: views/panelUsuario.php");
    exit();
}

// Si se envió el formulario de login, manejamos el login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = trim($_POST['usuario']);
    $clave = trim($_POST['clave']);

    // Creamos el controlador de login
    $loginController = new LoginController();
    $loginController->login($usuario, $clave); // Llamamos a la función login
} else {
    // Si no se envió el formulario, mostramos la vista del login
    require_once 'views/login.php';
}

?>

