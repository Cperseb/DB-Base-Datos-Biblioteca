<?php
require_once "../models/sesion.php";
require_once "../controller/PrestamosController.php";

$usuario = Sesion::obtenerUsuario();
if (!$usuario || $usuario['Admin']) {
    header("Location: ../views/login.php");
    exit();
}

$controller = new PrestamosController();
$sancion = $controller->verificarSancion($usuario['IdUsuario']);
$mensaje = "";

if ($sancion) {
    $mensaje = "🚫 No puedes solicitar préstamos hasta " . $sancion;
}

$documentosDisponibles = !$sancion ? $controller->obtenerDocumentosDisponibles() : [];
$totalPrestamos = $controller->contarPrestamosUsuario($usuario['IdUsuario']);

// Si se ha enviado el formulario de solicitud de préstamo
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['solicitar']) && !$sancion) {
    $titulo = $_POST['titulo'];
    $mensaje = $controller->solicitarPrestamo($usuario['IdUsuario'], $titulo);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitar Préstamos</title>
    <link rel="stylesheet" href="../assets/css/estilosPrestamos.css">
</head>
<body>

<div class="container">
    <h1>Solicitar Préstamos</h1>

    <?php if ($sancion): ?>
        <p class="mensaje-sancion"><?php echo $mensaje; ?></p>
    <?php else: ?>
        <p><strong>Libros en préstamo actualmente:</strong> <?php echo $totalPrestamos; ?> / 6</p>

        <?php if (!empty($mensaje)): ?>
            <p class="mensaje"><?php echo htmlspecialchars($mensaje); ?></p>
        <?php endif; ?>

        <h2>Documentos Disponibles</h2>
        <form method="POST" action="solicitarPrestamos.php">
            <label for="titulo">Seleccione un documento:</label>
            <select name="titulo" required>
                <?php foreach ($documentosDisponibles as $documento): ?>
                    <option value="<?php echo htmlspecialchars($documento['Titulo']); ?>">
                        <?php echo htmlspecialchars($documento['Titulo']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" name="solicitar">Solicitar Préstamo</button>
        </form>
    <?php endif; ?>

    <div class="volver-btn-container">
        <a href="panelUsuario.php" class="volver-btn">Volver al Panel de Usuario</a>
    </div>
</div>

</body>
</html>
