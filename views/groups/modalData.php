<?php
require_once __DIR__ . '/../../Config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_estudiante'])) {
    $idEstudiante = intval($_POST['id_estudiante']);

    try {
        // Consulta optimizada para extraer los detalles del estudiante
        $stmt = $connect->prepare("SELECT asignaturas.nombre_curso AS nombre_asignatura, calificaciones.calificacion, asistencias.asistencias, asistencias.inasistencias
                                   FROM estudiantes
                                   INNER JOIN calificaciones ON estudiantes.id_estudiante = calificaciones.id_estudiante
                                   INNER JOIN asignaturas ON calificaciones.id_curso = asignaturas.id_curso
                                   INNER JOIN asistencias ON estudiantes.id_estudiante = asistencias.id_estudiante
                                   WHERE estudiantes.id_estudiante = ?");
        $stmt->execute([$idEstudiante]);
        $details = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($details) {
            // Generar la tabla con diseño estilizado
            echo "<div class='table-responsive'>";
            echo "<table class='table table-bordered table-hover align-middle text-center table-sm'>";
            echo "<thead class='table-primary text-dark uniform-text'>";
            echo "<tr>";
            echo "<th scope='col'>Asignatura</th>";
            echo "<th scope='col'>Calificación</th>";
            echo "<th scope='col'>Asistencias</th>";
            echo "<th scope='col'>Inasistencias</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody class='table-light uniform-text'>";
            foreach ($details as $detail) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($detail['nombre_asignatura']) . "</td>";
                echo "<td>" . htmlspecialchars($detail['calificacion']) . "</td>";
                echo "<td>" . htmlspecialchars($detail['asistencias']) . "</td>";
                echo "<td>" . htmlspecialchars($detail['inasistencias']) . "</td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
            echo "</div>";
        } else {
            echo "<div class='alert alert-warning text-center' role='alert uniform-text'>No hay detalles disponibles para este estudiante.</div>";
        }
    } catch (PDOException $e) {
        // Capturamos errores de SQL y mostramos un mensaje claro
        echo "<div class='alert alert-danger text-center uniform-text' role='alert'>Error en la consulta SQL: " . htmlspecialchars($e->getMessage()) . "</div>";
    }
}
?>
