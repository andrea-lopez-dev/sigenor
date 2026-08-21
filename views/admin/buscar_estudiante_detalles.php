<?php
require '../../Config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $busqueda = trim($_POST['busqueda'] ?? '');
    $periodo = $_POST['periodo'] ?? '';
    $seccion = $_POST['seccion'] ?? '';

    if (empty($busqueda) || empty($periodo)) {
        echo "<div class='alert alert-danger'>Faltan parámetros de búsqueda.</div>";
        exit;
    }

    $sql = "
        SELECT e.cedula, e.nombres, e.apellidos, p.nombre_periodo, s.nombre_seccion,
               a.nombre_curso, ast.asistencias, ast.inasistencias, cal.calificacion
        FROM estudiantes e
        LEFT JOIN calificaciones cal ON e.id_estudiante = cal.id_estudiante
        LEFT JOIN asignaturas a ON cal.id_curso = a.id_curso
        LEFT JOIN asistencias ast ON e.id_estudiante = ast.id_estudiante
        LEFT JOIN seccion s ON ast.id_seccion = s.id_seccion
        LEFT JOIN periodos p ON cal.id_periodo = p.id_periodo
        WHERE (e.cedula LIKE :b1 OR e.nombres LIKE :b2 OR e.apellidos LIKE :b3)
    ";

    $params = [
        ':b1' => "%$busqueda%",
        ':b2' => "%$busqueda%",
        ':b3' => "%$busqueda%"
    ];

    if (!empty($periodo)) {
        $sql .= " AND p.id_periodo = :periodo";
        $params[':periodo'] = $periodo;
    }

    if (!empty($seccion)) {
        $sql .= " AND s.id_seccion = :seccion";
        $params[':seccion'] = $seccion;
    }

    $sql .= " ORDER BY e.apellidos, e.nombres";

    try {
        $stmt = $connect->prepare($sql);
        $stmt->execute($params);
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($resultados) {
            echo "<div style='max-height: 400px; overflow-y: auto; overflow-x: auto;'>";
            echo "<table class='table table-bordered table-hover table-sm text-center'>";
            echo "<thead class='table-success'>";
            echo "<tr>
                    <th>Cédula</th>
                    <th>Nombre</th>
                    <th>Periodo</th>
                    <th>Sección</th>
                    <th>Asignatura</th>
                    <th>Asistencias</th>
                    <th>Inasistencias</th>
                    <th>Calificación</th>
                  </tr>";
            echo "</thead><tbody>";

            foreach ($resultados as $fila) {
                echo "<tr>
                        <td>{$fila['cedula']}</td>
                        <td>{$fila['nombres']} {$fila['apellidos']}</td>
                        <td>{$fila['nombre_periodo']}</td>
                        <td>{$fila['nombre_seccion']}</td>
                        <td>{$fila['nombre_curso']}</td>
                        <td>{$fila['asistencias']}</td>
                        <td>{$fila['inasistencias']}</td>
                        <td>{$fila['calificacion']}</td>
                      </tr>";
            }

            echo "</tbody></table>";
            echo "</div>";
        }  else {
            echo "<div class='alert alert-warning text-center'>No se encontraron estudiantes con mas de 2 periodos porfavor ingrese los datos completos.</div>";
        }
    } catch (PDOException $e) {
        echo "<div class='alert alert-danger'>Error en la consulta SQL: " . htmlspecialchars($e->getMessage()) . "</div>";
    }
}
?>
