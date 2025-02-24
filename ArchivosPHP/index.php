<?php
// Iniciar sesión
session_start();

// Incluir la clase Query
include 'query.php';
$query = new Query();

// Variables para el mensaje de error y la conservación del nombre de usuario
$error = '';
$nombreUsuario = ''; // Variable para guardar el nombre de usuario

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recoger datos del formulario
    $nombreUsuario = $_POST['nombre']; // Recogemos el nombre de usuario
    $clave = $_POST['clave'];

    // Comprobar si el nombre de usuario existe en la base de datos
    $usuario = $query->select('Usuarios', "Nombre = '$nombreUsuario'");

    // Validar que el usuario exista y la contraseña sea correcta
    if ($usuario && password_verify($clave, $usuario[0]['Clave'])) {
        // Guardar la información del usuario en la sesión
        $_SESSION['user'] = $usuario[0];
        header('Location: dashboard.php');  // Redirigir a la página principal
    } else {
        $error = 'Nombre de usuario o contraseña incorrectos';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Biblioteca</title>
    <link rel="stylesheet" href="estilos/estilosIndex.css">
</head>
<body>
    <div class="login-container">
        <form method="POST" action="index.php">
            <h2>Iniciar sesión</h2>
            <?php if ($error): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            <div class="input-group">
                <label for="nombre">Nombre de usuario:</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($nombreUsuario); ?>">
            </div>
            <div class="input-group">
                <label for="clave">Contraseña:</label>
                <input type="password" id="clave" name="clave">
            </div>
            <button type="submit">Iniciar sesión</button>
        </form>
    </div>
</body>
</html>
