<?php
require_once "../models/sesion.php";
require_once "../controller/librosController.php";

// Verificar si hay una sesión activa
$usuario = Sesion::obtenerUsuario();
if (!$usuario) {
    header("Location: login.php");
    exit();
}

// Crear una instancia del controlador
$librosController = new LibrosController();

$message = "";

// Procesar el formulario al enviarlo
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idEjemplar = $_POST['id_ejemplar'];
    // Usar el ID del usuario logueado
    $message = $librosController->registrarPrestamo($usuario['IdUsuario'], $idEjemplar);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Préstamo</title>
    <link rel="stylesheet" href="../assets/css/estilosPaneles.css">
    <style>
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #fff;
        }
        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #444;
            background-color: #2c2c2c;
            color: #fff;
            border-radius: 4px;
        }
        .message {
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
            background-color: #444;
            color: #fff;
        }
        .message.error {
            background-color: #ff4444;
        }
        .message.success {
            background-color: #44ff44;
            color: #000;
        }
        .user-info {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #444;
            border-radius: 4px;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Registrar Préstamo</h1>
        
        <div class="user-info">
            <p>Usuario: <?php echo htmlspecialchars($usuario['Nombre']); ?></p>
        </div>
        
        <!-- Mensaje de resultado -->
        <?php if ($message): ?>
            <div class="message <?php echo strpos($message, 'Error') === 0 ? 'error' : 'success'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="id_ejemplar">ID del Ejemplar:</label>
                <input type="number" name="id_ejemplar" id="id_ejemplar" required>
            </div>
            <button type="submit" class="action-button">Registrar Préstamo</button>
        </form>

        <div class="button-container" style="margin-top: 20px;">
            <a href="panelUsuario.php" class="action-button">Volver al Panel</a>
        </div>
    </div>
</body>
</html> 