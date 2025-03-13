<?php
require_once "../models/sesion.php"; 

$usuario = Sesion::obtenerUsuario();

// Si el usuario no está logueado o no es administrador, redirigir al login
if (!$usuario || $usuario['Admin'] !== 1) {
    header("Location: ../views/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administrador</title>
    <link rel="stylesheet" href="../assets/css/estilosPaneles.css">
</head>
<body>
    <div class="container">
        <div class="panel-header">
            <h1>Bienvenido, <?php echo htmlspecialchars($usuario['Nombre']); ?> (Administrador)</h1>
        </div>

        <div class="button-container">
            <!-- Botón para gestionar usuarios -->
            <div class="button-item">
                <a href="gestionarUsuarios.php">
                    <button class="action-button">Gestión de Usuarios</button>
                </a>
            </div>

            <!-- Botón para gestionar documentos -->
            <div class="button-item">
                <a href="gestionarDocumentos.php">
                    <button class="action-button">Gestión de Documentos</button>
                </a>
            </div>

            <!-- Botón para gestionar préstamos y reservas -->
            <div class="button-item">
                <a href="gestionarPrestamosReservas.php">
                    <button class="action-button">Gestión de Préstamos y Reservas</button>
                </a>
            </div>
        </div>

        <div class="logout-section">
            <a href="logout.php" class="logout-btn">Cerrar sesión</a>
        </div>
    </div>
</body>
</html>
