<?php require_once 'models/sesion.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="assets/css/estilosIndex.css">
</head>
<body>
    <div class="login-container">
        <h2>Iniciar Sesión</h2>

        <?php if ($error = Sesion::obtenerError()): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>

        <form action="index.php" method="POST">
            <label for="usuario">Usuario:</label>
            <input type="text" name="usuario" id="usuario" value="<?php echo Sesion::obtenerUsuarioTemporal(); ?>"><br><br>
            
            <label for="clave">Contraseña:</label>
            <input type="password" name="clave" id="clave"><br><br>
            
            <button type="submit">Ingresar</button>
        </form>
    </div>
</body>
</html>
