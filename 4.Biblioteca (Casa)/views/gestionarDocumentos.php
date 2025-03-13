<?php
require_once "../models/sesion.php";
require_once "../controller/DocumentosController.php";

$usuario = Sesion::obtenerUsuario();
if (!$usuario || $usuario['Admin'] !== 1) {
    header("Location: ../views/login.php");
    exit();
}

$controller = new DocumentosController();
$documentos = $controller->obtenerDocumentos();
$mensaje = isset($_GET['mensaje']) ? $_GET['mensaje'] : "";
$tipoSeleccionado = isset($_POST['tipo_documento']) ? $_POST['tipo_documento'] : "";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Documentos</title>
    <link rel="stylesheet" href="../assets/css/estilosGestionDocumentos.css">
</head>
<body>

<div class="container">
    <h1>Gestión de Documentos</h1>

    <?php if (!empty($mensaje)): ?>
        <p class="mensaje"><?php echo htmlspecialchars($mensaje); ?></p>
    <?php endif; ?>

    <!-- FORMULARIO PASO 1: Selección del tipo de documento -->
    <h2>Seleccionar tipo de documento</h2>
    <form method="POST">
        <select name="tipo_documento" required onchange="this.form.submit()">
            <option value="">-- Selecciona un tipo --</option>
            <option value="libro" <?php echo ($tipoSeleccionado == "libro") ? "selected" : ""; ?>>Libro</option>
            <option value="revista" <?php echo ($tipoSeleccionado == "revista") ? "selected" : ""; ?>>Revista</option>
            <option value="multimedia" <?php echo ($tipoSeleccionado == "multimedia") ? "selected" : ""; ?>>Multimedia</option>
        </select>
    </form>

    <!-- FORMULARIO PASO 2: Mostrar formulario según el tipo seleccionado -->
    <?php if ($tipoSeleccionado): ?>
        <h2>Añadir <?php echo ucfirst($tipoSeleccionado); ?></h2>
        <form action="../controller/DocumentosController.php" method="POST">
            <input type="hidden" name="tipo_documento" value="<?php echo $tipoSeleccionado; ?>">

            <input type="text" name="titulo" placeholder="Título" required>
            <input type="text" name="isbn" placeholder="ISBN (Opcional)">
            <input type="text" name="autores" placeholder="Autor(es)" required>
            <input type="date" name="fecha_publicacion" required>
            <input type="number" name="num_ejemplares" placeholder="Nº de ejemplares" required>
            <input type="text" name="descripcion" placeholder="Descripción" required>
            <input type="text" name="materia" placeholder="Materia" required>

            <!-- Campos adicionales según tipo -->
            <?php if ($tipoSeleccionado == "libro"): ?>
                <input type="number" name="numero_paginas" placeholder="Número de páginas" required>
                <input type="text" name="casa" placeholder="Ubicación en biblioteca" required>
            <?php elseif ($tipoSeleccionado == "revista"): ?>
                <select name="frecuencia" required>
                    <option value="diario">Diario</option>
                    <option value="semanal">Semanal</option>
                    <option value="mensual">Mensual</option>
                    <option value="anual">Anual</option>
                </select>
            <?php elseif ($tipoSeleccionado == "multimedia"): ?>
                <input type="text" name="soporte" placeholder="Formato (DVD, Blu-ray, etc.)" required>
            <?php endif; ?>

            <button type="submit" name="accion" value="agregar">Añadir Documento</button>
        </form>
    <?php endif; ?>

    <h2>Lista de Documentos</h2>
    <table>
        <tr>
            <th>Título</th>
            <th>ISBN</th>
            <th>Autor(es)</th>
            <th>Fecha de Publicación</th>
            <th>Nº de Ejemplares</th>
            <th>Materia</th>
            <th>Tipo</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($documentos as $doc): ?>
        <tr>
            <td><?php echo htmlspecialchars($doc["Titulo"]); ?></td>
            <td><?php echo htmlspecialchars($doc["ISBN"]); ?></td>
            <td><?php echo htmlspecialchars($doc["ListaAutores"]); ?></td>
            <td><?php echo htmlspecialchars($doc["FechaPublicacion"]); ?></td>
            <td><?php echo htmlspecialchars($doc["NumEjemplares"]); ?></td>
            <td><?php echo htmlspecialchars($doc["Materia"]); ?></td>
            <td><?php echo htmlspecialchars($doc["Tipo"]); ?></td>
            <td>
                <form action="../controller/DocumentosController.php" method="POST">
                    <input type="hidden" name="titulo" value="<?php echo $doc["Titulo"]; ?>">
                    <button type="submit" name="accion" value="eliminar" onclick="return confirm('¿Eliminar este documento?')">🗑️ Eliminar</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <a href="panelAdministrador.php" class="volver-btn">Volver</a>
</div>

</body>
</html>
