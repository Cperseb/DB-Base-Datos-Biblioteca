<?php
require_once "../models/sesion.php"; 

// Verificar si hay una sesión activa
$usuario = Sesion::obtenerUsuario();

// Si no hay sesión activa o el usuario es administrador, redirigir al login
if (!$usuario || $usuario['Admin']) {
    header("Location: ../views/login.php");
    exit();
}

// Si la sesión está activa y el usuario no es administrador, continua
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Usuario</title>
    <link rel="stylesheet" href="../assets/css/estilosPaneles.css">
</head>
<body>
    <div class="container">
        <div class="panel-header">
            <h1>Bienvenido, <?php echo htmlspecialchars($usuario['Nombre']); ?></h1>
        </div>

        <div class="button-container">
            <div class="button-item">
                <a href="listarLibros.php">
                    <button class="action-button">Consultar Libros</button>
                </a>
                <a href="registrarPrestamos.php">
                    <button class="action-button">Solicitar Préstamo</button>
                </a>
                <a href="misPrestamos.php">
                    <button class="action-button">Mis Préstamos</button>
                </a>
            </div>
        </div>
        <div class="logout-section">
            <!-- Botón para cerrar sesión -->
            <a href="logout.php" class="logout-btn">Cerrar sesión</a>
        </div>
    </div>
</body>
</html>
