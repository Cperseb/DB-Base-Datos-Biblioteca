<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "../models/database.php";

class DocumentosController {
    private $conexion;

    public function __construct() {
        try {
            $baseDatos = new Database();
            $this->conexion = $baseDatos->getConnection();
        } catch (PDOException $e) {
            die("❌ Error de conexión a la base de datos: " . $e->getMessage());
        }
    }

    // 1️⃣ Obtener lista de documentos
    public function obtenerDocumentos() {
        try {
            $sql = "SELECT d.*, 
                        CASE 
                            WHEN l.Titulo IS NOT NULL THEN 'Libro'
                            WHEN r.Titulo IS NOT NULL THEN 'Revista'
                            WHEN m.Titulo IS NOT NULL THEN 'Multimedia'
                        END AS Tipo
                    FROM Documentos d
                    LEFT JOIN Libros l ON d.Titulo = l.Titulo
                    LEFT JOIN Revistas r ON d.Titulo = r.Titulo
                    LEFT JOIN Multimedia m ON d.Titulo = m.Titulo";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("❌ Error al obtener documentos: " . $e->getMessage());
        }
    }

    // 2️⃣ Añadir documento con su tipo correspondiente
    public function agregarDocumento($titulo, $isbn, $autores, $fecha_publicacion, $num_ejemplares, $descripcion, $materia, $tipo_documento, $numero_paginas = null, $casa = null, $frecuencia = null, $soporte = null) {
        try {
            $this->conexion->beginTransaction(); // Iniciar transacción

            // Insertar en la tabla Documentos
            $sql = "INSERT INTO Documentos (Titulo, ISBN, ListaAutores, FechaPublicacion, NumEjemplares, Descripcion, Materia) 
                    VALUES (:titulo, :isbn, :autores, :fecha_publicacion, :num_ejemplares, :descripcion, :materia)";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([
                ":titulo" => $titulo,
                ":isbn" => $isbn ?: null,
                ":autores" => $autores,
                ":fecha_publicacion" => $fecha_publicacion,
                ":num_ejemplares" => $num_ejemplares,
                ":descripcion" => $descripcion,
                ":materia" => $materia
            ]);

            // Insertar en la tabla específica según el tipo de documento
            if ($tipo_documento === "libro") {
                $sqlLibro = "INSERT INTO Libros (Titulo, ISBN, numeroPagina, CASA) VALUES (:titulo, :isbn, :numero_paginas, :casa)";
                $stmtLibro = $this->conexion->prepare($sqlLibro);
                $stmtLibro->execute([
                    ":titulo" => $titulo,
                    ":isbn" => $isbn ?: null,
                    ":numero_paginas" => $numero_paginas ?: 100,
                    ":casa" => $casa ?: "Biblioteca Central"
                ]);
            } elseif ($tipo_documento === "revista") {
                $sqlRevista = "INSERT INTO Revistas (Titulo, ISBN, frecuencia) VALUES (:titulo, :isbn, :frecuencia)";
                $stmtRevista = $this->conexion->prepare($sqlRevista);
                $stmtRevista->execute([
                    ":titulo" => $titulo,
                    ":isbn" => $isbn ?: null,
                    ":frecuencia" => $frecuencia ?: "mensual"
                ]);
            } elseif ($tipo_documento === "multimedia") {
                $sqlMultimedia = "INSERT INTO Multimedia (Titulo, Soporte) VALUES (:titulo, :soporte)";
                $stmtMultimedia = $this->conexion->prepare($sqlMultimedia);
                $stmtMultimedia->execute([
                    ":titulo" => $titulo,
                    ":soporte" => $soporte ?: "DVD"
                ]);
            }

            $this->conexion->commit(); // Confirmar cambios

            header("Location: ../views/gestionarDocumentos.php?mensaje=" . urlencode("✅ Documento añadido correctamente."));
            exit();
        } catch (PDOException $e) {
            $this->conexion->rollBack(); // Revertir cambios si hay un error
            header("Location: ../views/gestionarDocumentos.php?mensaje=" . urlencode("❌ Error al añadir documento: " . $e->getMessage()));
            exit();
        }
    }

    // 3️⃣ Eliminar documento y su relación en las tablas secundarias
    public function eliminarDocumento($titulo) {
        try {
            $this->conexion->beginTransaction(); // Iniciar transacción

            // Eliminar de las tablas secundarias si existen
            $sqlDeleteLibro = "DELETE FROM Libros WHERE Titulo = :titulo";
            $sqlDeleteRevista = "DELETE FROM Revistas WHERE Titulo = :titulo";
            $sqlDeleteMultimedia = "DELETE FROM Multimedia WHERE Titulo = :titulo";

            $stmtLibro = $this->conexion->prepare($sqlDeleteLibro);
            $stmtRevista = $this->conexion->prepare($sqlDeleteRevista);
            $stmtMultimedia = $this->conexion->prepare($sqlDeleteMultimedia);

            $stmtLibro->execute([":titulo" => $titulo]);
            $stmtRevista->execute([":titulo" => $titulo]);
            $stmtMultimedia->execute([":titulo" => $titulo]);

            // Luego eliminar el documento principal
            $sql = "DELETE FROM Documentos WHERE Titulo = :titulo";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([":titulo" => $titulo]);

            $this->conexion->commit(); // Confirmar eliminación

            header("Location: ../views/gestionarDocumentos.php?mensaje=" . urlencode("✅ Documento eliminado correctamente."));
            exit();
        } catch (PDOException $e) {
            $this->conexion->rollBack(); // Revertir cambios si hay un error
            header("Location: ../views/gestionarDocumentos.php?mensaje=" . urlencode("❌ Error al eliminar documento: " . $e->getMessage()));
            exit();
        }
    }
}

// 📌 Manejo de solicitudes POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $controller = new DocumentosController();

    $accion = $_POST["accion"] ?? null;
    $titulo = $_POST["titulo"] ?? null;
    $isbn = $_POST["isbn"] ?? null;
    $autores = $_POST["autores"] ?? null;
    $fecha_publicacion = $_POST["fecha_publicacion"] ?? null;
    $num_ejemplares = $_POST["num_ejemplares"] ?? null;
    $descripcion = $_POST["descripcion"] ?? null;
    $materia = $_POST["materia"] ?? null;
    $tipo_documento = $_POST["tipo_documento"] ?? null;
    $numero_paginas = $_POST["numero_paginas"] ?? null;
    $casa = $_POST["casa"] ?? null;
    $frecuencia = $_POST["frecuencia"] ?? null;
    $soporte = $_POST["soporte"] ?? null;

    switch ($accion) {
        case "agregar":
            $controller->agregarDocumento($titulo, $isbn, $autores, $fecha_publicacion, $num_ejemplares, $descripcion, $materia, $tipo_documento, $numero_paginas, $casa, $frecuencia, $soporte);
            break;
        case "eliminar":
            $controller->eliminarDocumento($titulo);
            break;
        default:
            header("Location: ../views/gestionarDocumentos.php?mensaje=" . urlencode("❌ Acción no válida."));
            exit();
    }
}
?>
