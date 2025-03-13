<?php
require_once "../models/sesion.php";
require_once "../controller/UsuariosController.php";

// Verificar si el usuario tiene permisos de administrador
$usuario = Sesion::obtenerUsuario();
if (!$usuario || $usuario['Admin'] !== 1) {
    header("Location: ../views/login.php");
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["accion"]) && in_array($_POST["accion"], ["agregar", "editar", "eliminar", "sancionar"])) {
    $controller = new UsuariosController();

    $accion = $_POST["accion"] ?? null;
    $idUsuario = $_POST["idUsuario"] ?? null;
    $nombre = $_POST["nombre"] ?? null;
    $email = $_POST["email"] ?? null;
    $direccion = $_POST["direccion"] ?? null;
    $telefono = $_POST["telefono"] ?? null;
    $curso = $_POST["curso"] ?? null;
    $clave = $_POST["clave"] ?? null;
    $admin = isset($_POST["admin"]);

    switch ($accion) {
        case "agregar":
            $controller->agregarUsuario($nombre, $email, $direccion, $telefono, $curso, $clave, $admin);
            break;
        case "editar":
            $controller->editarUsuario($idUsuario, $nombre, $email, $direccion, $telefono, $curso, $admin);
            break;
        case "eliminar":
            $controller->eliminarUsuario($idUsuario);
            break;
        case "sancionar":
            $controller->aplicarSancion($idUsuario);
            break;
        default:
            header("Location: gestionarUsuarios.php?mensaje=" . urlencode("❌ Acción no válida."));
            exit();
    }
}
$controller = new UsuariosController();
$usuarios = $controller->obtenerUsuarios();
$mensaje = isset($_GET['mensaje']) ? $_GET['mensaje'] : "";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
    <link rel="stylesheet" href="../assets/css/estilosGestionUsuarios.css">
</head>
<body>

<div class="container">
    <h1>Gestión de Usuarios</h1>

    <?php if (!empty($mensaje)): ?>
        <p class="mensaje"><?php echo htmlspecialchars($mensaje); ?></p>
    <?php endif; ?>

    <h2>Añadir Usuario</h2>
    <form action="../controller/UsuariosController.php" method="POST">
        <input type="text" name="nombre" placeholder="Nombre" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="direccion" placeholder="Dirección" required>
        <input type="text" name="telefono" placeholder="Teléfono" required>
        <input type="number" name="curso" placeholder="Curso" required>
        <input type="password" name="clave" placeholder="Contraseña" required>
        <label><input type="checkbox" name="admin"> Administrador</label>
        <button type="submit" name="accion" value="agregar">Añadir Usuario</button>
    </form>

    <h2>Lista de Usuarios</h2>
    <table>
        <tr>
            <th>Nombre</th>
            <th>Email</th>
            <th>Dirección</th>
            <th>Teléfono</th>
            <th>Curso</th>
            <th>Admin</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($usuarios as $u): ?>
        <tr>
            <form action="../controller/UsuariosController.php" method="POST">
                <td><input type="text" name="nombre" value="<?php echo htmlspecialchars($u["Nombre"]); ?>" required></td>
                <td><input type="email" name="email" value="<?php echo htmlspecialchars($u["Email"]); ?>" required></td>
                <td><input type="text" name="direccion" value="<?php echo htmlspecialchars($u["Direccion"]); ?>" required></td>
                <td><input type="text" name="telefono" value="<?php echo htmlspecialchars($u["Telefono"]); ?>" required></td>
                <td><input type="number" name="curso" value="<?php echo htmlspecialchars($u["Curso"]); ?>" required></td>
                <td>
                    <input type="checkbox" name="admin" <?php echo $u["Admin"] ? "checked" : ""; ?>>
                </td>
                <td>
                    <input type="hidden" name="idUsuario" value="<?php echo $u["IdUsuario"]; ?>">
                    <button type="submit" name="accion" value="editar">✏️ Editar</button>
                    <button type="submit" name="accion" value="sancionar">🚫 Sancionar</button>
                    <button type="submit" name="accion" value="eliminar" onclick="return confirm('¿Estás seguro de eliminar este usuario?')">🗑️ Eliminar</button>
                </td>
            </form>
        </tr>
        <?php endforeach; ?>
    </table>

    <a href="panelAdministrador.php" class="volver-btn">Volver</a>
</div>

</body>
</html>
