<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once "../controller/librosController.php"; // Incluir el controlador

// Crear una instancia del controlador
$controller = new LibrosController();

// Llamar a la función para obtener la tabla generada dinámicamente
$tablaLibros = $controller->mostrarTablaLibros();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Libros</title>
    <link rel="stylesheet" href="../assets/css/estiloLibros.css">
</head>
<body>

<div class="container">
    <h1>Listado de Libros</h1>
    
    <!-- Mostrar la tabla generada por el controlador -->
    <?php echo $tablaLibros; ?>
    
    <!-- Botón para volver al panel de usuario -->
    <div class="volver-btn-container">
        <a href="panelUsuario.php" class="volver-btn">Volver al Panel de Usuario</a>
    </div>
</div>

</body>
</html>
