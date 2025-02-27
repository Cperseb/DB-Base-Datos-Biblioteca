<?php
function listarLibrosPorISBN() {
    // Aquí va la lógica para conectarse a la base de datos y realizar el SELECT
    $conn = new mysqli('localhost', 'usuario', 'contraseña', 'base_de_datos');

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    $sql = "SELECT isbn, titulo, autor FROM libros"; // Ajusta la consulta según tu base de datos
    $result = $conn->query($sql);

    $libros = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $libros[] = $row;
        }
    }

    $conn->close();
    return $libros;
}

// Mostrar la tabla de libros
$libros = listarLibrosPorISBN(); // Llamar a la función para obtener los libros

if (!empty($libros)) {
    echo '<table>';
    echo '<tr><th>ISBN</th><th>Título</th><th>Autor</th></tr>';
    foreach ($libros as $libro) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($libro['isbn']) . '</td>';
        echo '<td>' . htmlspecialchars($libro['titulo']) . '</td>';
        echo '<td>' . htmlspecialchars($libro['autor']) . '</td>';
        echo '</tr>';
    }
    echo '</table>';
} else {
    echo '<p>No se encontraron libros.</p>';
}
?> 