<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once "../controller/librosController.php"; 

$controller = new LibrosController();

// Capturar el filtro y el título seleccionado dinámicamente
$filtro = isset($_GET['buscar']) ? trim($_GET['buscar']) : "";
$tituloSeleccionado = isset($_GET['titulo']) ? trim($_GET['titulo']) : "";

$tablaDocumentos = $controller->mostrarTablaDocumentos($filtro);
$informacionDocumento = !empty($tituloSeleccionado) ? $controller->obtenerInformacionDocumento($tituloSeleccionado) : "";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Documentos</title>
    <link rel="stylesheet" href="../assets/css/estiloDocumentos.css">
</head>
<body>

<div class="container">
    <h1>Listado de Documentos</h1>
    
    <form method="GET" action="listarDocumentos.php">
        <input type="text" name="buscar" placeholder="Buscar por ISBN, Título o Autor" value="<?php echo htmlspecialchars($filtro); ?>">
        <button type="submit">Buscar</button>
    </form>

    <!-- Mostrar la tabla generada por el controlador -->
    <?php echo $tablaDocumentos; ?>

    <!-- Mostrar la información del documento seleccionado dinámicamente -->
    <?php echo $informacionDocumento; ?>

    <div class="volver-btn-container">
        <a href="panelUsuario.php" class="volver-btn">Volver al Panel de Usuario</a>
    </div>
</div>

</body>
</html>
