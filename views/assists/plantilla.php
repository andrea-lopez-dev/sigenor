<?php
require_once '../../vendor/autoload.php';
ob_start();
date_default_timezone_set('America/Caracas');

// Conexión
$servername = "localhost";
$username = "root";
$password = "";
$database = "sigenor";

$conexion = new mysqli($servername, $username, $password, $database);
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Consulta para asistencias e inasistencias de los estudiantes
$sql = "SELECT  
            estudiantes.cedula, 
            asignaturas.nombre_curso, 
            seccion.nombre_seccion, 
            periodos.nombre_periodo,
            asistencias.id_asistencia, 
            asistencias.asistencias, 
            asistencias.inasistencias, 
            asistencias.fecha_creacion
        FROM asistencias
        INNER JOIN estudiantes ON asistencias.id_estudiante = estudiantes.id_estudiante
        INNER JOIN asignaturas ON asistencias.id_curso = asignaturas.id_curso
        INNER JOIN seccion ON asistencias.id_seccion = seccion.id_seccion
        INNER JOIN periodos ON asistencias.id_periodo = periodos.id_periodo
        ORDER BY estudiantes.cedula ASC, asistencias.fecha_creacion DESC";

$resultado = $conexion->query($sql);
$datos = [];

if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $datos[] = $fila;
    }
}

// Clase PDF personalizada
class PDF extends TCPDF
{
    public function Header()
    {
        $this->Image('../../Assets/img/membrete.jpg', 10, 10, 85);
        $this->SetY(40);
        $this->SetFont('helvetica', 'B', 12);
 $this->SetTextColor(0, 0, 0);

        $this->Cell(0, 8, 'U.E BR. NOCT. RAFAEL RANGEL', 0, 1, 'C');

        $this->SetY(50);
        $this->SetFont('helvetica', '', 10);
        $this->Cell(0, 8, utf8_decode('Listado de asistencias e inasistencias'), 0, 1, 'C');

        // Encabezado de la tabla
        $this->SetFont('helvetica', 'B', 9);
      $this->SetFillColor(0, 102, 0);

        $this->SetTextColor(255, 255, 255);
        $this->SetXY(10, 70);
        $this->Cell(25, 10, 'CÉDULA', 1, 0, 'C', 1);
        $this->Cell(40, 10, 'ASIGNATURA', 1, 0, 'C', 1);
        $this->Cell(25, 10, 'SECCIÓN', 1, 0, 'C', 1);
        $this->Cell(35, 10, 'PERÍODO', 1, 0, 'C', 1);
        $this->Cell(25, 10, 'FECHA', 1, 0, 'C', 1);
        $this->Cell(20, 10, 'ASIST.', 1, 0, 'C', 1);
        $this->Cell(25, 10, 'INASIST.', 1, 1, 'C', 1);
    }

    public function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 5, utf8_decode('Página ') . $this->getAliasNumPage() . ' / ' . $this->getAliasNbPages(), 0, 0, 'L');
        $this->Cell(0, 5, date('d/m/Y | g:i:a'), 0, 1, 'R');
        $this->Cell(0, 5, "SIGENOR © Todos los derechos reservados.", 0, 0, "C");
    }
}

// Crear PDF
$pdf = new PDF('P', 'mm', array(215.9, 330.2), true, 'UTF-8', false);
$pdf->SetMargins(10, 10, 10);
$pdf->SetAutoPageBreak(true, 20);
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 9);
$pdf->SetY(80); // Comienza debajo del encabezado

// Imprimir los datos
foreach ($datos as $fila) {
    $pdf->Cell(25, 8, utf8_decode($fila['cedula']), 1, 0, 'C');

function abreviarAsignatura($nombre) {
    $reemplazos = [
        'LENGUA CULTURA Y COMUNICACION' => 'LENGUA CULT. Y COM.',
        'MATEMATICA' => 'MATEM.',
        'MEMORIA TERRITORIO Y CIUDADANIA' => 'MEMORIA TERR. Y CIUD.',
        'CIENCIAS NATURALES' => 'CIENCIAS NAT.',
        'COMPONENTE DE PART. E INTEG.' => 'COMP. PART. E INTEG.',
        'COMPONENTE DE IDIOMAS' => 'COMP. IDIOMAS',
        'COMPONENTE DE FORMACIÒN LABORAL' => 'COMP. FORM. LABORAL',
    ];

    return $reemplazos[$nombre] ?? $nombre;
}

// En el foreach:
$nombre_curso = abreviarAsignatura($fila['nombre_curso']);

if (strlen($nombre_curso) > 19) {
    $nombre_curso = substr($nombre_curso, 0, 19) . '.';
}

$pdf->Cell(40, 8, utf8_decode($nombre_curso), 1, 0, 'L');



    $pdf->Cell(25, 8, utf8_decode($fila['nombre_seccion']), 1, 0, 'C');
    $pdf->Cell(35, 8, utf8_decode($fila['nombre_periodo']), 1, 0, 'C');
    $pdf->Cell(25, 8, date('d/m/Y', strtotime($fila['fecha_creacion'])), 1, 0, 'C');
    $pdf->Cell(20, 8, $fila['asistencias'], 1, 0, 'C');
    $pdf->Cell(25, 8, $fila['inasistencias'], 1, 1, 'C');
}

ob_end_clean();
$pdf->Output('asistencias_estudiantes.pdf', 'I');
echo '<script>window.onload = function() { window.print(); }</script>';
?>
