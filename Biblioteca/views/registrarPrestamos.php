<?php
require_once "../controllers/librosController.php";

// Crear una instancia del controlador
$librosController = new LibrosController();

$message = "";

// Procesar el formulario al enviarlo
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuarioId = $_POST['usuario_id'];
    $isbn = $_POST['isbn'];
    
    // Llamar a la función para registrar el préstamo
    $message = $librosController->registrarPrestamo($usuarioId, $isbn);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Préstamo</title>
    <link rel="stylesheet" href="../assets/css/estilosPaneles.css">
</head>
<body>
    <div class="container">
        <h1>Registrar Préstamo</h1>
        
        <!-- Mensaje de resultado -->
        <?php if ($message): ?>
            <div class="message">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="usuario_id">ID de Usuario:</label>
                <input type="number" name="usuario_id" id="usuario_id" required>
            </div>
            <div class="form-group">
                <label for="isbn">ISBN del Libro:</label>
                <input type="text" name="isbn" id="isbn" required>
            </div>
            <button type="submit" class="action-button">Registrar Préstamo</button>
        </form>
    </div>
</body>
</html> 