<?php
require_once '../../vendor/autoload.php';
ob_start();
date_default_timezone_set('America/Caracas');

$materia = [];
$profesor_nombre = [];
$profesor_cedula = [];
$profesor_id = [];

for ($i = 0; $i < 7; $i++) {
    if (isset($asignaturasPeriodo[$i])) {
        $materia[$i] = htmlspecialchars($asignaturasPeriodo[$i]['nombre_curso']);
        $profesor_nombre[$i] = htmlspecialchars($asignaturasPeriodo[$i]['nombre_apellido'] ?? '*');
        $profesor_cedula[$i] = htmlspecialchars($asignaturasPeriodo[$i]['cedula_profesor'] ?? '*');
        $profesor_id[$i] = htmlspecialchars($asignaturasPeriodo[$i]['id_profesor'] ?? '*');



    } else {
        $materia[$i] = '*';
        $profesor_nombre[$i] = '*';
        $profesor_cedula[$i] = '*';
        $profesor_id[$i] = '*';
    }
}

class PDF extends TCPDF
{
    // Cabecera de página
    public function Header()
    {
        $this->Image('../../Assets/img/membrete.jpg', 10, 10, 85);
        $this->SetY(40);
        $this->SetFont('helvetica', 'B', 12);
        $this->SetTextColor(0, 0, 0);
        $this->Cell(0, 8, 'U.E BR. NOCT. RAFAEL RANGEL', 0, 1, 'C');

        $this->SetY(50);
        $this->SetFont('helvetica', '', 10);
        $this->Cell(0, 8, utf8_decode('Listado de profesores'), 0, 1, 'C');

      $this->SetFillColor(0, 102, 0);

$this->SetTextColor(255, 255, 255);

$this->SetFont('helvetica', 'B', 10);
$this->SetXY(16.9, 85);
$this->Cell(37.8, 12,('CEDULA'), 1, 0, 'C', 1);
$this->Cell(37.9, 12, ('NOMBRE Y APELLIDO'), 1, 0, 'C', 1);
$this->Cell(37.7, 12,('CORREO'), 1, 0, 'C', 1);
$this->Cell(37.9, 12, ('TELEFONO'), 1, 0, 'C', 1);
$this->Cell(37.8, 12, ('SEXO'), 1, 1, 'C', 1);
    }

public function page()
{
    require('../../Config/config.php');

    $stmt = $connect->prepare("SELECT * FROM profesores ORDER BY id_profesor");
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $stmt->execute();
 $this->SetXY(17, 97); $this->SetFont('helvetica', '', 10); $this->SetFillColor(255, 255, 255); $this->SetTextColor(0, 0, 0);
    $html = '<table border="1" cellpadding="5">
                <tbody>';

    while ($row = $stmt->fetch()) {
        $html .= '<tr>
                    <td align="center">'.utf8_decode($row['cedula_profesor']).'</td>
                    <td align="center">'.utf8_decode($row['nombre_apellido']).'</td>
                    <td align="center">'.utf8_decode($row['correo_profesor']).'</td>
                    <td align="center">'.utf8_decode($row['telefono_profesor']).'</td>
                    <td align="center">'.utf8_decode($row['sexo']).'</td>
                  </tr>';
    }

    $html .= '</tbody></table>';

    // Agregar la tabla HTML al PDF
    $this->writeHTML($html, true, false, true, false, '');
}

// Pie de página
public function Footer()
{
    $this->SetY(-15);
    $this->SetFont('helvetica', 'I', 8);
    $this->Cell(0, 5, utf8_decode('Página ') . $this->getAliasNumPage() . ' / ' . $this->getAliasNbPages(), 0, 0, 'L');
    $this->Cell(0, 5, date('d/m/Y | g:i:a'), 0, 1, 'R');
    $this->Cell(0, 5, "SIGENOR © Todos los derechos reservados.", 0, 0, "C");
}

}

$pdf = new PDF('P', 'mm', array(215.9, 279.4), true, 'UTF-8', false);
$pdf->SetMargins(10, 10, 10);
$pdf->SetAutoPageBreak(true, 20);
$pdf->AddPage();
$pdf->page();


ob_end_clean();
$pdf->Output('profesores.pdf', 'I');
echo '<script>window.onload = function() { window.print(); }</script>';
?>
