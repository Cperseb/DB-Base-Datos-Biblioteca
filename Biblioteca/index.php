<?php
require_once "models/sesion.php";

// Obtener usuario de la sesión
$usuario = Sesion::obtenerUsuario();

// Verificar si hay una sesión activa y redirigir correctamente
if ($usuario) {
    if ($usuario['Admin']) {
        header("Location: views/panelAdministrador.php");
    } else {
        header("Location: views/panelUsuario.php");
    }
    exit();
}

// Si no hay sesión, redirigir al login
header("Location: views/login.php");
exit();
?>
