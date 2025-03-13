<?php
require_once "models/database.php";

$baseDatos = new BaseDatos();
$conexion = $baseDatos->obtenerConexion();

if ($conexion) {
    echo "✅ Conexión establecida correctamente.";
} else {
    echo "❌ Error en la conexión.";
}
?>
