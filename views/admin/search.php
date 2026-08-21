<?php
// search.php

// Incluye el archivo de configuración (ajusta la ruta según tu estructura de carpetas)
require_once __DIR__ . '/../../Config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
    $searchTerm = trim($_POST['search']);
    $param = "%" . $searchTerm . "%";

    echo "<h4></h4>";

    // Búsqueda en estudiantes
    $stmt = $connect->prepare("SELECT id_estudiante, cedula, nombres, apellidos 
                               FROM estudiantes 
                               WHERE cedula LIKE ? OR nombres LIKE ? OR apellidos LIKE ?");
    $stmt->execute([$param, $param, $param]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($results) {
        echo "<h5>Estudiantes:</h5>";
        foreach ($results as $row) {
            echo "<div class='mb-3'>";
            echo "<p><strong>ID Estudiante:</strong> " . htmlspecialchars($row['id_estudiante']) . "</p>";
            echo "<p><strong>Cédula:</strong> " . htmlspecialchars($row['cedula']) . "</p>";
            echo "<p><strong>Nombre:</strong> " . htmlspecialchars($row['nombres']) . " " . htmlspecialchars($row['apellidos']) . "</p>";
            echo "</div><hr>";
        }
    }

    // Búsqueda en planteles
    $stmt = $connect->prepare("SELECT codigo, nombre, direccion_plantel, telefono 
                               FROM planteles 
                               WHERE codigo LIKE ? OR nombre LIKE ? OR direccion_plantel LIKE ?");
    $stmt->execute([$param, $param, $param]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($results) {
        echo "<h5>Planteles:</h5>";
        foreach ($results as $row) {
            echo "<div class='mb-3'>";
            echo "<p><strong>Código del Plantel:</strong> " . htmlspecialchars($row['codigo']) . "</p>";
            echo "<p><strong>Nombre:</strong> " . htmlspecialchars($row['nombre']) . "</p>";
            echo "<p><strong>Dirección:</strong> " . htmlspecialchars($row['direccion_plantel']) . "</p>";
            echo "<p><strong>Teléfono:</strong> " . htmlspecialchars($row['telefono']) . "</p>";
            echo "</div><hr>";
        }
    }

    // Búsqueda en periodos
    $stmt = $connect->prepare("SELECT id_plantel, numero_periodo, fecha_inicio, fecha_fin, nombre_periodo, estado 
                               FROM periodos 
                               WHERE nombre_periodo LIKE ? OR estado LIKE ?");
    $stmt->execute([$param, $param]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($results) {
        echo "<h5>Periodos:</h5>";
        foreach ($results as $row) {
            echo "<div class='mb-3'>";
            echo "<p><strong>ID Plantel:</strong> " . htmlspecialchars($row['id_plantel']) . "</p>";
            echo "<p><strong>Periodo:</strong> " . htmlspecialchars($row['numero_periodo']) . "</p>";
            echo "<p><strong>Inicio:</strong> " . htmlspecialchars($row['fecha_inicio']) . "</p>";
            echo "<p><strong>Fin:</strong> " . htmlspecialchars($row['fecha_fin']) . "</p>";
            echo "<p><strong>Nombre del Periodo:</strong> " . htmlspecialchars($row['nombre_periodo']) . "</p>";
            echo "<p><strong>Estado:</strong> " . htmlspecialchars($row['estado']) . "</p>";
            echo "</div><hr>";
        }
    }

    // Si no hay resultados en ninguna tabla
    if (!$results) {
        echo "<p class='text-warning'>No se encontraron mas resultados para el término: <strong>" . htmlspecialchars($searchTerm) . "</strong>.</p>";
    }
} else {
    echo "<p class='text-danger'>Error Termino Invalido.</p>";
}
?>
