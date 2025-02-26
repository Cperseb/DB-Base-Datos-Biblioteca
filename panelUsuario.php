<?php
session_start(); // Iniciar la sesión

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario']) || !isset($_SESSION['admin']) || $_SESSION['admin'] === true) {
    // Si el usuario no está logueado o es administrador, redirigir al login
    header('Location: ../views/login.php');
    exit();
}
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
        <div id="navbar">
            <div id="navbar-title">
                <h1>Bienvenido, <?php echo $_SESSION['usuario']; ?> (Administrador)</h1>
            </div>
            <div id="navbar-links">
                <ul>
                    <li><a href="gestionarUsuarios.php">Gestionar usuarios (alta, baja, edición)</a></li>
                    <li><a href="gestionarDocumentos.php">Gestionar documentos y ejemplares</a></li>
                    <li><a href="controlPrestamos.php">Control de préstamos y devoluciones</a></li>
                    <li><a href="reportes.php">Generar reportes y estadísticas</a></li>
                    <li><a href="logout.php">Cerrar sesión</a></li>
                </ul>
            </div>
        </div>

        <div id="content">
            <div id="panel">
                <h2>Bienvenido al panel de administrador</h2>
                <p>Desde aquí puedes gestionar usuarios, documentos y préstamos, además de generar reportes.</p>
            </div>
        </div>
    </div>
</body>
</html>