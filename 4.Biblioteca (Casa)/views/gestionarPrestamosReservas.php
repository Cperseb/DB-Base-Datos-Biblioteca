<?php
require_once "../models/sesion.php";
require_once "../controller/GestionPrestamosController.php";

$usuario = Sesion::obtenerUsuario();
if (!$usuario || $usuario['Admin'] !== 1) {
    header("Location: ../views/login.php");
    exit();
}

$controller = new GestionPrestamosController();
$prestamos = $controller->obtenerPrestamosActivos();
$mensaje = isset($_GET['mensaje']) ? $_GET['mensaje'] : "";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Préstamos y Reservas</title>
    <link rel="stylesheet" href="../assets/css/estilosGestionPrestamos.css">
</head>
<body>

<div class="container">
    <h1>Gestión de Préstamos y Reservas</h1>

    <?php if (!empty($mensaje)): ?>
        <p class="mensaje"><?php echo htmlspecialchars($mensaje); ?></p>
    <?php endif; ?>

    <h2>Préstamos Activos</h2>
    <table>
        <tr>
            <th>Usuario</th>
            <th>Ejemplar</th>
            <th>Fecha Inicio</th>
            <th>Fecha Fin</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($prestamos as $p): ?>
        <tr>
            <td><?php echo htmlspecialchars($p["NombreUsuario"]); ?></td>
            <td><?php echo htmlspecialchars($p["Titulo"]); ?></td>
            <td><?php echo htmlspecialchars($p["FechaInicio"]); ?></td>
            <td><?php echo htmlspecialchars($p["FechaFin"]); ?></td>
            <td><?php echo $p["estado"] ? "Activo" : "Finalizado"; ?></td>
            <td>
                <form action="../controller/GestionPrestamosController.php" method="POST">
                    <input type="hidden" name="idPrestamo" value="<?php echo $p["IdPrestamo"]; ?>">
                    <button type="submit" name="accion" value="devolver">📚 Registrar Devolución</button>
                    <?php if ($p["estado"]): ?>
                        <button type="submit" name="accion" value="sancionar">🚫 Aplicar Sanción</button>
                        <button type="submit" name="accion" value="aprobar_renovacion">✅ Aprobar Renovación</button>
                        <button type="submit" name="accion" value="rechazar_renovacion">❌ Rechazar Renovación</button>
                    <?php endif; ?>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <a href="panelAdministrador.php" class="volver-btn">Volver</a>
</div>

</body>
</html>
