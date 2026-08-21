<?php
require_once '../../vendor/autoload.php';
ob_start();
date_default_timezone_set('America/Caracas');

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$database = "sigenor";

$conexion = new mysqli($servername, $username, $password, $database);
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Consulta para obtener todos los estudiantes
$sql = "SELECT 
            id_estudiante, cedula, nombres, apellidos, edad, sexo, 
            fecha_nacimiento, direccion, tlf_estudiante, correo, 
            foto, lugar_nacimiento, entidad_federal, fecha, estado 
        FROM estudiantes 
        ORDER BY id_estudiante ASC";

$resultado = $conexion->query($sql);
$estudiantes = [];

if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $estudiantes[] = $fila;
    }
}

// Clase personalizada para el PDF
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
        $this->Cell(0, 8, utf8_decode('Listado de estudiantes'), 0, 1, 'C');

        // Cabecera de la tabla
        $this->SetFont('helvetica', 'B', 9);
            $this->SetFillColor(0, 102, 0);

$this->SetTextColor(255, 255, 255);
        $this->SetXY(10, 70);
        $this->Cell(25, 10, 'CÉDULA', 1, 0, 'C', 1);
        $this->Cell(60, 10, 'NOMBRES Y APELLIDOS', 1, 0, 'C', 1);
        $this->Cell(15, 10, 'EDAD', 1, 0, 'C', 1);
        $this->Cell(35, 10, 'TELÉFONO', 1, 0, 'C', 1);
        $this->Cell(60, 10, 'CORREO', 1, 1, 'C', 1);
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
$pdf->SetY(80); // Posicionar después del encabezado

foreach ($estudiantes as $e) {
    $pdf->Cell(25, 8, utf8_decode($e['cedula']), 1, 0, 'C');
    $pdf->Cell(60, 8, utf8_decode($e['nombres'] . ' ' . $e['apellidos']), 1, 0, 'L');
    $pdf->Cell(15, 8, $e['edad'], 1, 0, 'C');
    $pdf->Cell(35, 8, $e['tlf_estudiante'], 1, 0, 'C');
    $pdf->Cell(60, 8, utf8_decode($e['correo']), 1, 1, 'L');
}

ob_end_clean();
$pdf->Output('listado_estudiantes.pdf', 'I');
echo '<script>window.onload = function() { window.print(); }</script>';
?>
