<?php

require_once '../../vendor/autoload.php';
ob_start(); 
date_default_timezone_set('America/Caracas');

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
        $this->Cell(0, 8, 'Listado de periodos académicos', 0, 1, 'C');

        $this->SetFont('helvetica', 'B', 10);
        $this->SetXY(16.8, 85);
        $this->SetFillColor(0, 102, 0);
        $this->SetTextColor(255, 255, 255);
        $this->Cell(47.4, 12, 'Nº', 1, 0, 'C', 1);
        $this->Cell(47.3, 12, utf8_decode('NOMBRE DEL PERIODO'), 1, 0, 'C', 1);
        $this->Cell(47.2, 12, utf8_decode('FECHA INICIO'), 1, 0, 'C', 1);
        $this->Cell(47.3, 12, utf8_decode('FECHA FIN'), 1, 1, 'C', 1);
    }

    public function page()
    {
        require('../../Config/config.php');

        $stmt = $connect->prepare("SELECT periodos.id_periodo, periodos.numero_periodo, periodos.fecha_inicio, periodos.fecha_fin, periodos.nombre_periodo FROM periodos");
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        $this->SetXY(17, 97);
        $this->SetFont('helvetica', '', 10);
        $this->SetFillColor(255, 255, 255);
        $this->SetTextColor(0, 0, 0);

        $html = '<table border="1" cellpadding="5"><tbody>';

        while ($row = $stmt->fetch()) {
            $html .= '<tr>
                        <td align="center">'.utf8_decode($row['numero_periodo']).'</td>
                        <td align="center">'.utf8_decode($row['nombre_periodo']).'</td>
                        <td align="center">'.utf8_decode($row['fecha_inicio']).'</td>
                        <td align="center">'.utf8_decode($row['fecha_fin']).'</td>
                      </tr>';
        }

        $html .= '</tbody></table>';

        $this->writeHTML($html, true, false, true, false, '');
    }

    // Pie de página
    public function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 5, utf8_decode('Página ').$this->getAliasNumPage().' / '.$this->getAliasNbPages(), 0, 0, 'L');
        $this->Cell(0, 5, date('d/m/Y | g:i:a'), 0, 1, 'R');
        $this->Cell(0, 5, "SIGENOR © Todos los derechos reservados.", 0, 0, "C");
    }
}

// Crear el documento PDF usando la clase extendida PDF
$pdf = new PDF('P', 'mm', array(215.9, 279.4), true, 'UTF-8', false);
$pdf->SetMargins(10, 10, 10);
$pdf->SetAutoPageBreak(true, 20);
$pdf->AddPage();
$pdf->page();  // Aquí imprimes la tabla con los periodos

ob_end_clean();

// Generar y mostrar el PDF
$pdf->Output('periodos.pdf', 'I');
echo '<script>window.onload = function() { window.print(); }</script>';
?>
