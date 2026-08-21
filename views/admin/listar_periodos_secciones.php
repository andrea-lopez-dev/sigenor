<?php
header('Content-Type: application/json');
require '../../Config/config.php';

try {
    $stmt_periodos = $connect->prepare("SELECT id_periodo, nombre_periodo FROM periodos ORDER BY nombre_periodo ASC");
    $stmt_periodos->execute();
    $periodos = $stmt_periodos->fetchAll(PDO::FETCH_ASSOC);

    $stmt_secciones = $connect->prepare("SELECT id_seccion, nombre_seccion FROM seccion ORDER BY nombre_seccion ASC");
    $stmt_secciones->execute();
    $secciones = $stmt_secciones->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['periodos' => $periodos, 'secciones' => $secciones]);

} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error en la consulta: ' . $e->getMessage()]);
}
