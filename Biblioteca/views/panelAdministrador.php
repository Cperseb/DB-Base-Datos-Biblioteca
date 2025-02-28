<?php
require_once "../models/sesion.php"; // Incluir la clase Sesion

// Verificar si hay una sesión activa
$usuario = Sesion::obtenerUsuario();

// Si no hay sesión activa o el usuario no es administrador, redirigir al login
if (!$usuario || $usuario['Admin'] !== 1) {
    header("Location: ../views/login.php");
    exit();
}

// Si la sesión está activa y el usuario es un administrador, continua
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administrador</title>
    <link rel="stylesheet" href="../css/estilosPaneles.css">
</head>
<body>
    <div id="container">
        <header>
            <h1>Bienvenido, <?php echo htmlspecialchars($usuario['Nombre']); ?> (Administrador)</h1>
            <a href="../controller/logout.php" class="logout-btn">Cerrar Sesión</a>
        </header>

        <nav>
            <ul>
                <li><a href="gestionarUsuarios.php">Gestionar usuarios (alta, baja, edición)</a></li>
                <li><a href="gestionarDocumentos.php">Gestionar documentos y ejemplares</a></li>
                <li><a href="controlPrestamos.php">Control de préstamos y devoluciones</a></li>
                <li><a href="reportes.php">Generar reportes y estadísticas</a></li>
            </ul>
        </nav>

        <main>
            <h2>Bienvenido a tu Panel de Administración</h2>
            <p>Desde aquí puedes gestionar todos los aspectos relacionados con los usuarios, documentos y préstamos.</p>
        </main>
    </div>
</body>
</html>
