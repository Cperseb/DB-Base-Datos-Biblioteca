<?php
require_once "../models/database.php";

class LibrosController {
    private $conexion;

    public function __construct() {
        $baseDatos = new Database();
        $this->conexion = $baseDatos->getConnection();
    }

    // Función para obtener la tabla de documentos con búsqueda dinámica
    public function mostrarTablaDocumentos($filtro = "") {
        try {
            // Obtener nombres de columnas de la tabla Documentos dinámicamente
            $sqlColumnas = "SHOW COLUMNS FROM Documentos";
            $stmtColumnas = $this->conexion->prepare($sqlColumnas);
            $stmtColumnas->execute();
            $columnas = $stmtColumnas->fetchAll(PDO::FETCH_COLUMN);

            // Construir la consulta SQL con filtro dinámico
            $sql = "SELECT * FROM Documentos";
            if (!empty($filtro)) {
                $sql .= " WHERE ISBN LIKE :filtro OR Titulo LIKE :filtro OR ListaAutores LIKE :filtro";
            }

            $stmt = $this->conexion->prepare($sql);
            if (!empty($filtro)) {
                $filtro = "%$filtro%";
                $stmt->bindParam(":filtro", $filtro, PDO::PARAM_STR);
            }

            $stmt->execute();
            $documentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Si no hay documentos, mostrar mensaje
            if (empty($documentos)) {
                return "<p class='mensaje-aviso'>No se encontraron documentos.</p>";
            }

            // Generar la tabla de documentos dinámicamente
            $tabla = "<table class='tabla-documentos'><thead><tr>";
            foreach ($columnas as $columna) {
                $tabla .= "<th>" . htmlspecialchars($columna) . "</th>";
            }
            $tabla .= "<th>Ver Detalles</th></tr></thead><tbody>";

            foreach ($documentos as $documento) {
                $tabla .= "<tr>";
                foreach ($columnas as $columna) {
                    $valor = $documento[$columna] ?? ''; // Si es NULL, lo convierte en cadena vacía
                    $tabla .= "<td>" . htmlspecialchars($valor) . "</td>";
                }
                $tabla .= "<td><a href='listarDocumentos.php?titulo=" . urlencode($documento['Titulo']) . "' class='btn-detalles'>Ver Detalles</a></td>";
                $tabla .= "</tr>";
            }
            
            $tabla .= "</tbody></table>";

            return $tabla;

        } catch (PDOException $e) {
            return "<p class='mensaje-error'>Error al obtener los documentos: " . $e->getMessage() . "</p>";
        }
    }

    // Función para obtener información detallada de un documento dinámicamente
    public function obtenerInformacionDocumento($titulo) {
        try {
            $sql = "SELECT * FROM Documentos WHERE Titulo = :titulo LIMIT 1";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(":titulo", $titulo, PDO::PARAM_STR);
            $stmt->execute();
            $documento = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$documento) {
                return "<p class='mensaje-aviso'>No se encontró información adicional para este documento.</p>";
            }

            // Obtener información de los ejemplares
            $sqlEjemplares = "SELECT COUNT(*) as total, SUM(CASE WHEN Prestado = 0 THEN 1 ELSE 0 END) as disponibles, Localizacion 
                              FROM Ejemplares WHERE Titulo = :titulo GROUP BY Localizacion";
            $stmtEjemplares = $this->conexion->prepare($sqlEjemplares);
            $stmtEjemplares->bindParam(":titulo", $titulo, PDO::PARAM_STR);
            $stmtEjemplares->execute();
            $ejemplares = $stmtEjemplares->fetchAll(PDO::FETCH_ASSOC);

            // Generar lista de información dinámica
            $info = "<h2>Detalles del Documento</h2><ul>";
            foreach ($documento as $campo => $valor) {
                $info .= "<li><strong>" . htmlspecialchars($campo) . ":</strong> " . htmlspecialchars($valor ?? 'No disponible') . "</li>";

            }
            $info .= "</ul>";

            // Mostrar disponibilidad de ejemplares
            $info .= "<h3>Disponibilidad y Ubicación</h3><ul>";
            if (!empty($ejemplares)) {
                foreach ($ejemplares as $ejemplar) {
                    $info .= "<li><strong>Ubicación:</strong> {$ejemplar['Localizacion']} - 
                              <strong>Disponibles:</strong> {$ejemplar['disponibles']} / {$ejemplar['total']}</li>";
                }
            } else {
                $info .= "<p class='mensaje-aviso'>No hay ejemplares registrados.</p>";
            }
            $info .= "</ul>";

            return $info;

        } catch (PDOException $e) {
            return "<p class='mensaje-error'>Error al obtener información adicional: " . $e->getMessage() . "</p>";
        }
    }
}
?>
