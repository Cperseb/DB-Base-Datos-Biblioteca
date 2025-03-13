<?php
require_once "../models/sesion.php";
require_once "../controller/librosController.php";

// Verificar si hay una sesión activa
$usuario = Sesion::obtenerUsuario();
if (!$usuario) {
    header("Location: login.php");
    exit();
}

$controller = new LibrosController();
$prestamos = $controller->listarPrestamosPorUsuario($usuario['IdUsuario']);
$prestamosVencidos = $controller->listarPrestamosVencidos($usuario['IdUsuario']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Préstamos</title>
    <link rel="stylesheet" href="../assets/css/estilosPaneles.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: #2c2c2c;
        }
        th, td {
            padding: 10px;
            border: 1px solid #444;
            text-align: left;
            color: #fff;
        }
        th {
            background-color: #444;
        }
        tr:nth-child(even) {
            background-color: #383838;
        }
        .no-prestamos {
            color: #fff;
            text-align: center;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Mis Préstamos</h1>
        
        <h2>Préstamos Activos</h2>
        <?php if (empty($prestamos)): ?>
            <p class="no-prestamos">No tienes préstamos activos.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Devolución</th>
                        <th>Estado</th>
                        <th>Observación</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($prestamos as $prestamo): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($prestamo['Titulo']); ?></td>
                        <td><?php echo htmlspecialchars($prestamo['FechaInicio']); ?></td>
                        <td><?php echo htmlspecialchars($prestamo['FechaFin']); ?></td>
                        <td><?php echo $prestamo['estado'] ? 'Activo' : 'Devuelto'; ?></td>
                        <td><?php echo htmlspecialchars($prestamo['Observacion']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <?php if (!empty($prestamosVencidos)): ?>
            <h2>Préstamos Vencidos</h2>
            <table>
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Fecha Devolución</th>
                        <th>Días de Retraso</th>
                        <th>Observación</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($prestamosVencidos as $prestamo): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($prestamo['Titulo']); ?></td>
                        <td><?php echo htmlspecialchars($prestamo['FechaFin']); ?></td>
                        <td><?php echo htmlspecialchars($prestamo['DiasRetraso']); ?></td>
                        <td><?php echo htmlspecialchars($prestamo['Observacion']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <div class="button-container">
            <a href="panelUsuario.php" class="action-button">Volver al Panel</a>
        </div>
    </div>
</body>
</html>