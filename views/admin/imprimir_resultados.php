<?php
require_once '../../vendor/autoload.php';
require('../../Config/config.php');

$query = trim($_GET['busqueda'] ?? '');
$periodo = $_GET['periodo'] ?? '';
$seccion = $_GET['seccion'] ?? '';

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
    ':b1' => "%$query%",
    ':b2' => "%$query%",
    ':b3' => "%$query%"
];

if (!empty($periodo)) {
    $sql .= " AND p.id_periodo = :periodo";
    $params[':periodo'] = $periodo;
}
if (!empty($seccion)) {
    $sql .= " AND s.id_seccion = :seccion";
    $params[':seccion'] = $seccion;
}

$stmt = $connect->prepare($sql);
$stmt->execute($params);
$resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$resultados) {
    die("No se encontraron resultados para los filtros especificados.");
}

// Crear PDF
class PDF extends TCPDF {
    public function Header() {
        $this->Image('../../Assets/img/membrete.jpg', 10, 10, 85);
        $this->SetY(40);
        $this->SetFont('helvetica', 'B', 12);
        $this->Cell(0, 8, 'U.E BR. NOCT. RAFAEL RANGEL', 0, 1, 'C');
        $this->SetFont('helvetica', 'B', 14);
        $this->Cell(0, 10, 'Reporte de Calificaciones y Asistencias', 0, 1, 'C');
        $this->Ln(2);
    }
    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, 'Página '.$this->getAliasNumPage().' / '.$this->getAliasNbPages(), 0, 0, 'C');
    }
}

$pdf = new PDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetMargins(10, 20, 10);
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 10);
$pdf->SetY(80);

$tbl = '<table border="1" cellpadding="4" cellspacing="0" width="100%">
<tr bgcolor="#006600" style="color: #fff;">
<th>Cédula</th><th>Nombre</th><th>Periodo</th><th>Sección</th><th>Asignatura</th>
<th>Calificación</th><th>Asistencia</th><th>Inasistencia</th></tr>';

foreach ($resultados as $row) {
    $tbl .= '<tr>
        <td align="center">'.htmlspecialchars($row['cedula']).'</td>
        <td>'.htmlspecialchars($row['nombres'].' '.$row['apellidos']).'</td>
        <td align="center">'.htmlspecialchars($row['nombre_periodo']).'</td>
        <td align="center">'.htmlspecialchars($row['nombre_seccion']).'</td>
        <td align="center">'.htmlspecialchars($row['nombre_curso']).'</td>
        <td align="center">'.htmlspecialchars($row['calificacion']).'</td>
        <td align="center">'.htmlspecialchars($row['asistencias']).'</td>
        <td align="center">'.htmlspecialchars($row['inasistencias']).'</td>
    </tr>';
}
$tbl .= '</table>';

$pdf->writeHTML($tbl, true, false, false, false, '');

if (ob_get_length()) ob_end_clean();
$pdf->Output('reporte_estudiante.pdf', 'I');
exit;
