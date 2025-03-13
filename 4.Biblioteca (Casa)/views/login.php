<?php
session_start();
if (isset($_SESSION['usuario'])) {
    header("Location: panelUsuario.php");
    exit();
}

// Si el usuario ingresó su nombre pero falló la clave, lo mantenemos
$nombreIngresado = isset($_GET['nombre']) ? $_GET['nombre'] : '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio de sesión</title>
    <link rel="stylesheet" href="../assets/css/estiloLogin.css">
</head>
<body>

    <div class="contenedor-login">
        <h2>Iniciar Sesión</h2>

        <?php
        if (isset($_GET['error'])) {
            echo "<p class='mensaje-error'>".$_GET['error']."</p>";
        }
        ?>

        <form action="../controller/loginController.php" method="POST">
            <div class="input-group">
                <label for="nombre">Nombre de usuario:</label>
                <input type="text" name="nombre" value="<?php echo htmlspecialchars($nombreIngresado); ?>">
            </div>

            <div class="input-group">
                <label for="clave">Contraseña:</label>
                <input type="password" name="clave">
            </div>

            <button type="submit" name="login" class="boton-login">Ingresar</button>
        </form>
    </div>

</body>
</html>
