<?php
require_once '../../vendor/autoload.php';

// 🔹 1. CONEXIÓN A BASE DE DATOS
$servername = "localhost";
$username = "root";
$password = "";
$database = "sigenor";
$conexion = new mysqli($servername, $username, $password, $database);
ob_start();

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// 🔹 2. OBTENER PARÁMETROS DESDE LA URL
$id_estudiante = isset($_GET['id']) ? intval($_GET['id']) : 0;
$id_periodo = isset($_GET['periodo']) ? intval($_GET['periodo']) : null;

if ($id_estudiante <= 0 || $id_periodo === null) {
    die("Parámetros inválidos. Asegúrate de seleccionar un estudiante y un periodo.");
}

// 🔹 3. CONSULTAR DATOS DEL ESTUDIANTE Y PLANTELES
$sql = "SELECT 
            estudiantes.*, 
            planteles.id_plantel, planteles.nombre AS nombre_plantel, planteles.localidad,
            planteles.entidad_federal AS planteles_entidad, 
            periodos.id_periodo, periodos.nombre_periodo, periodos.numero_periodo  
        FROM estudiantes 
        LEFT JOIN estudiantes_planteles ON estudiantes.id_estudiante = estudiantes_planteles.id_estudiante
        LEFT JOIN planteles ON estudiantes_planteles.id_plantel = planteles.id_plantel
        LEFT JOIN periodos ON planteles.id_plantel = periodos.id_plantel
        WHERE estudiantes.id_estudiante = ?
        ORDER BY planteles.id_plantel ASC";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_estudiante);
$stmt->execute();
$resultado = $stmt->get_result();

$estudiante = [];
$planteles_unicos = [];

if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $estudiante = $fila;

        // Guardar planteles únicos
        if (!empty($fila['nombre_plantel']) && !isset($planteles_unicos[$fila['id_plantel']])) {
            $planteles_unicos[$fila['id_plantel']] = [
                'nombre' => $fila['nombre_plantel'],
                'localidad' => $fila['localidad'],
                'entidad_federal' => $fila['planteles_entidad']
            ];
        }
    }
} else {
    die("No se encontraron datos para este estudiante.");
}
$stmt->close();

// 🔹 4. CONSULTAR CALIFICACIONES DEL ESTUDIANTE EN EL PERIODO ACTUAL
$sql_calificaciones = "SELECT 
    calificaciones.*, 
    asignaturas.nombre_curso,
    planteles.numero_plantel,
    periodos.nombre_periodo,
    periodos.numero_periodo
FROM calificaciones
INNER JOIN asignaturas ON calificaciones.id_curso = asignaturas.id_curso
INNER JOIN planteles ON calificaciones.id_plantel = planteles.id_plantel
INNER JOIN periodos ON calificaciones.id_periodo = periodos.id_periodo
WHERE calificaciones.id_estudiante = ? AND calificaciones.id_periodo = ?
ORDER BY asignaturas.id_curso ASC";

$stmt = $conexion->prepare($sql_calificaciones);
$stmt->bind_param("ii", $id_estudiante, $id_periodo);
$stmt->execute();
$resultado = $stmt->get_result();

$nombre_curso = [];

if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $nombre_curso[] = [
            'nombre_curso' => $fila['nombre_curso'],
            'calificacion' => $fila['calificacion'],
            'calificacion_letras' => $fila['calificacion_letras'],
            'te' => $fila['T-E'],
            'mes' => $fila['mes'],
            'anio' => $fila['año'],
            'numero_plantel' => $fila['numero_plantel'],
            'nombre_periodo' => $fila['nombre_periodo'],
            'numero_periodo' => $fila['numero_periodo']
        ];
    }
} else {
    echo "<script>alert('No se encontraron calificaciones para este estudiante en el periodo seleccionado.');</script>";
}

$stmt->close();

// 🔹 5. RELLENAR SI HAY MENOS DE 8 MATERIAS
while (count($nombre_curso) < 8) {
    $nombre_curso[] = [
        'nombre_curso' => '*****',
        'calificacion' => '*****',
        'calificacion_letras' => '*****',
        'te' => '*****',
        'mes' => '*****',
        'anio' => '*****',
        'numero_plantel' => '*****',
        'nombre_periodo' => '*****',
        'numero_periodo' => '*****'
    ];
}

// 🔹 6. CONSULTAR PLANES ADMINISTRATIVOS
$sql_estudio = "SELECT * FROM plan_administrativo";
$stmtest = $conexion->prepare($sql_estudio);
$stmtest->execute();
$resultado_estudio = $stmtest->get_result();

$planesAdministrativos = [];
if ($resultado_estudio && $resultado_estudio->num_rows > 0) {
    while ($fila = $resultado_estudio->fetch_assoc()) {
        $planesAdministrativos[] = $fila;
    }
}

// Limpiar buffer de salida antes de usar TCPDF
ob_end_clean();
// Crear un array de planteles únicos

// Si hay más de 5 planteles, limitar la lista

// Ajustar la zona horaria
date_default_timezone_set('America/Caracas'); 
// Obtener la fecha actual
$meses = [
    "01" => "ENERO", "02" => "FEBRERO", "03" => "MARZO", "04" => "ABRIL", "05" => "MAYO",
    "06" => "JUNIO", "07" => "JULIO", "08" => "AGOSTO", "09" => "SEPTIEMBRE", "10" => "OCTUBRE",
    "11" => "NOVIEMBRE", "12" => "DICIEMBRE"
];


$dia = date("d"); // Obtener el día
$mes = $meses[date("m")]; // Convertir el mes a texto
$ano = date("Y"); // Obtener el año

// Formatear la fecha como "MARACAIBO, 01 DE ENERO DE 2023"
$lugar_fecha = "MARACAIBO, $dia DE $mes DE $ano";

// Convertir la fecha al formato DD-MM-YYYY En Estudiante
$fecha_nacimiento = $estudiante['fecha_nacimiento'];
$fecha_formateada = date("d/m/Y", strtotime($fecha_nacimiento));

$planteles_unicos = array_slice($planteles_unicos, 0, 5);


$sql_estudio = "SELECT id_plan_est, plan_estudio, codigo_estudio, estrategia_estudio, tipo_evaluacion, descripcion, fecha_estudio, COUNT(*) AS conteo FROM plan_administrativo";
$stmtest = $conexion->prepare($sql_estudio);
$stmtest->execute();
$resultado_estudio = $stmtest->get_result();

$planesAdministrativos = [];

if ($resultado_estudio && $resultado_estudio->num_rows > 0) {
    while ($fila = $resultado_estudio->fetch_assoc()) {
        $planesAdministrativos[] = $fila;
    }
}

foreach ($planesAdministrativos as $plan) {
    // tu lógica aquí
}


foreach ($nombre_curso as $materia) {
    }


   function obtenerMateriasPorIndices(array $materias, array $indices) {
    $seleccionadas = [];

    foreach ($indices as $i) {
        if (isset($materias[$i]) && !empty($materias[$i]['nombre_curso'])) {
            $seleccionadas[] = $materias[$i];
        } else {
            // Materia no existe o está vacía → usar marcador
            $seleccionadas[] = [
                'nombre_curso' => '*****',
                'calificacion' => '*****',
                'calificacion_letras' => '*****',
                'te' => '*****',
                'mes' => '*****',
                'anio' => '*****',
                'numero_plantel' => '*****',
                'nombre_periodo' => '*****',
                'numero_periodo' => '*****'
            ];
        }
    }

    return $seleccionadas;
}
$materiasSeleccionadas = obtenerMateriasPorIndices($nombre_curso, [5, 6]);
$materia2 = obtenerMateriasPorIndices($nombre_curso, [6]);
 foreach ($materiasSeleccionadas as $materia1) {}
foreach ($materia2 as $materia3) {}



$index = 4; // Índice 3 representa la 4ª materia (materia 4)

// Verificamos que exista la materia en el índice 3
if (isset($nombre_curso[$index])) {
    $item = $nombre_curso[$index];

    $nomb = !empty($item['nombre_curso']) ? $item['nombre_curso'] : '***';
    $califica = !empty($item['calificacion_letras']) ? strtoupper(trim($item['calificacion_letras'])) : '***';
    $te = !empty($item['te']) ? $item['te'] : '***';
    $mesx = !empty($item['mes']) ? $item['mes'] : '***';
    $anio = !empty($item['anio']) ? $item['anio'] : '***';
    $numero_plantel = !empty($item['numero_plantel']) ? $item['numero_plantel'] : '***';
} else {
    // Si no existe el índice 3 en el arreglo, usar valores vacíos
    $nomb = $califica = $te = $mesx = $anio = $numero_plantel = '***';
}
// Crear una nueva instancia de TCPDF con tamaño de hoja personalizado (Carta: 8.5 x 11 pulgadas)
$pdf = new TCPDF('P', 'mm', array(215.9, 279.4), true, 'UTF-8', false);

// Configurar los márgenes
$pdf->SetMargins(10, 5, 10); // Margen izquierdo, superior, y derecho
$pdf->SetHeaderMargin(10); // Margen del encabezado
$pdf->SetFooterMargin(5); // Margen del pie de página


// Configuración del documento
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Sistema Escolar');
$pdf->SetTitle('Boletin de Calificaciones');
$pdf->SetSubject('Boletin de Calificaciones');
$pdf->SetKeywords('IBoletin, Calificaciones, Venezuela');

// Configuración de encabezado y pie de página
$pdf->SetPrintHeader(false); // Desactivar encabezado
$pdf->SetPrintFooter(false); // Desactivar pie de página

// Configuración de fuentes
$pdf->setHeaderFont([PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN]);
$pdf->setFooterFont([PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA]);

// Márgenes
$pdf->SetMargins(15, 10, 15); // Reducir margen superior
$pdf->SetHeaderMargin(0); // Sin margen para el encabezado
$pdf->SetFooterMargin(0); // Sin margen para el pie de página

// Configuración de salto automático de página
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// Configuración de fuente predeterminada
$pdf->SetFont('helvetica', '', 8); // Cambiado a tamaño 8

// Agregar una página
$pdf->AddPage();

$image_file = '../../Assets/img/membrete.jpg'; // Ruta de la imagen

// Obtener las dimensiones originales de la imagen
list($width, $height) = getimagesize($image_file);

// Calcular el tamaño basado en el ancho deseado (puedes cambiar el valor de $new_width)
$new_width = 90; // Nuevo ancho deseado
$new_height = ($height / $width) * $new_width; // Mantener la proporción original

// Colocar la imagen en la parte superior izquierda con el tamaño ajustado
$pdf->Image($image_file, 5.9, 7, $new_width, $new_height, '', '', '', false, 300, '', false, false, 0, false, false, false);



// Añadir el texto "RESUMEN DE RENDIMIENTO ESTUDIANTIL" en la parte superior derecha
$pdf->SetFont('helvetica', 'B', 11); // Fuente en negrita y tamaño 12
$pdf->SetXY(130, 8); // Ajustar la posición horizontal (X) más hacia la izquierda
$pdf->Cell(0, 10, 'BOLETÌN DE CALIFICACIONES', 0, 1, 'L'); // Agregar el texto

// Dibujar una línea más pegada al texto
$pdf->SetLineWidth(0.3); // Grosor de la línea
$pdf->Line(130.6, 15, 188.6, 15); // Ajustar la línea para que coincida con la nueva posición del texto

// Añadir el texto "Código del Formato: EMGMJAA" centrado debajo de "CERTIFICACIÓN DE CALIFICACIONES"
$pdf->SetFont('helvetica', 'B', 9); // Fuente normal y tamaño 10
$pdf->SetXY(117, 13); // Ajustar la posición vertical (Y) debajo de la línea
$pdf->Cell(0, 10, 'Código del Formato: EMGMJAA', 0, 1, 'C'); // Texto centrado




// Añadir el texto "I. Código del Plan de Estudio:"
$pdf->SetFont('helvetica', 'B', 9); // Fuente normal y tamaño 10
$pdf->SetXY(95, 27); // Ajustar la posición horizontal (X) y vertical (Y)
$pdf->Cell(50, 10, 'I. Código del Plan de Estudio:', 0, 0, 'L'); // Agregar el texto
// DISMINUIR X HACIA LA IZQUIERDA AUMENTAR HACIA LA DERECHA
// Disminuir Y para subir y aumentar para bajar

// Añadir el texto "31058" encima de la línea
$pdf->SetFont('helvetica', '', 9); // Fuente normal y tamaño 8
$pdf->SetXY(170, 27.2); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, $plan['codigo_estudio'], 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha

// Dibujar una línea al lado derecho del texto
$pdf->SetLineWidth(0.3); // Grosor de la línea
$pdf->Line(141, 34, 210, 34); // Coordenadas de inicio y fin de la línea (X1, Y1, X2, Y2)
// 30 SE Y 30 SE AUMENTA PARA BAJAR Y SE DISMINUYE PARA SUBIR)





// Añadir el texto "Código Plantel:"
$pdf->SetFont('helvetica', 'B', 9); // Fuente normal y tamaño 10
$pdf->SetXY(7, 37); // Ajustar la posición horizontal (X) y vertical (Y)
$pdf->Cell(50, 10, 'Código:', 0, 0, 'L'); // Agregar el texto
// DISMINUIR X HACIA LA IZQUIERDA AUMENTAR HACIA LA DERECHA


// Añadir el texto "S2033N2313" encima de la línea
$pdf->SetFont('helvetica', 'B', 9); // Fuente normal y tamaño 8
$pdf->SetXY(21, 37); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, 'S2033N2313', 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha

// Dibujar una línea al lado derecho del texto
$pdf->SetLineWidth(0.3); // Grosor de la línea
$pdf->Line(20, 44, 43, 44); // Coordenadas de inicio y fin de la línea (X1, Y1, X2, Y2)
// Y1 y y2 30 SE Y 30 SE AUMENTA PARA BAJAR Y SE DISMINUYE PARA SUBIR)
// x1 se disminuye para mover hacia la izquierda y x2  y se aumenta para la izquierda y se disminuye hacia la derecha


// Añadir el texto "Nombre:"
$pdf->SetFont('helvetica', 'B', 9); // Fuente normal y tamaño 10
$pdf->SetXY(44, 37); // Ajustar la posición horizontal (X) y vertical (Y)
$pdf->Cell(50, 10, 'Nombre:', 0, 0, 'L'); // Agregar el texto
// DISMINUIR X HACIA LA IZQUIERDA AUMENTAR HACIA LA DERECHA


// Añadir el texto "UNIDAD EDUCATIVA NOCTURNA BR. RAFAEL RANGEL" encima de la línea
$pdf->SetFont('helvetica', 'B', 9); // Fuente normal y tamaño 8
$pdf->SetXY(58.5, 37); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, 'U.E.P. NOCT. BR. RAFAEL RANGEL', 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha

// Dibujar una línea al lado derecho del texto
$pdf->SetLineWidth(0.3); // Grosor de la línea
$pdf->Line(115, 44, 58, 44); // Coordenadas de inicio y fin de la línea (X1, Y1, X2, Y2)
// Y1 y y2 30 SE Y 30 SE AUMENTA PARA BAJAR Y SE DISMINUYE PARA SUBIR)
// x1 se disminuye para mover hacia la izquierda y x2  y disminuye para mover hacia la izquierda y se aumenta para ir hacia hacia la derecha




$pdf->SetFont('helvetica', 'B', 9); // Fuente normal y tamaño 8
$pdf->SetXY(124, 37); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea


$pdf->Cell(60, 10, 'Modaidad en Educación de Jovenes, Adultos y Adultas', 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha




// Añadir el texto "Cèdula de Identidad:"
$pdf->SetFont('helvetica', 'B', 9); // Fuente normal y tamaño 10
$pdf->SetXY(7, 53); // Ajustar la posición horizontal (X) y vertical (Y)
$pdf->Cell(50, 10, 'Cèdula de Identidad:', 0, 0, 'L'); // Agregar el texto
// DISMINUIR X HACIA LA IZQUIERDA AUMENTAR HACIA LA DERECHA


// Añadir el texto "SECTOR PANAMERICANO CALLE 69B No. 90-36" encima de la línea
$pdf->SetFont('helvetica', 'B', 9); // Fuente normal y tamaño 8
$pdf->SetXY(43, 53); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, $estudiante['cedula'], 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha

// Dibujar una línea al lado derecho del texto
$pdf->SetLineWidth(0.3); // Grosor de la línea
$pdf->Line(67, 60.5, 39, 60.5); // Coordenadas de inicio y fin de la línea (X1, Y1, X2, Y2)
// Y1 y y2 30 SE Y 30 SE AUMENTA PARA BAJAR Y SE DISMINUYE PARA SUBIR)
// x1 se disminuye para mover hacia la izquierda y x2  y disminuye para mover hacia la izquierda y se aumenta para ir hacia hacia la derecha



// Añadir el texto "Apellidos::"
$pdf->SetFont('helvetica', 'B', 9); // Fuente normal y tamaño 10
$pdf->SetXY(68, 53); // Ajustar la posición horizontal (X) y vertical (Y)
$pdf->Cell(50, 10, 'Apellidos:', 0, 0, 'L'); // Agregar el texto
// DISMINUIR X HACIA LA IZQUIERDA AUMENTAR HACIA LA DERECHA


$pdf->SetFont('helvetica', 'B', 9); // Fuente normal y tamaño 8
$pdf->SetXY(90, 53); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, $estudiante['apellidos'], 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha

// Dibujar una línea al lado derecho del texto
$pdf->SetLineWidth(0.3); // Grosor de la línea
$pdf->Line(84.6, 60.5, 134, 60.5); // Coordenadas de inicio y fin de la línea (X1, Y1, X2, Y2)
// Y1 y y2 30 SE Y 30 SE AUMENTA PARA BAJAR Y SE DISMINUYE PARA SUBIR)
// x1 se disminuye para mover hacia la izquierda y x2  y disminuye para mover hacia la izquierda y se aumenta para ir hacia hacia la derecha


// Añadir el texto "Apellidos::"
$pdf->SetFont('helvetica', 'B', 9); // Fuente normal y tamaño 10
$pdf->SetXY(133.8, 53); // Ajustar la posición horizontal (X) y vertical (Y)
$pdf->Cell(50, 10, 'Nombres:', 0, 0, 'L'); // Agregar el texto
// DISMINUIR X HACIA LA IZQUIERDA AUMENTAR HACIA LA DERECHA




// Añadir el texto "SECTOR PANAMERICANO CALLE 69B No. 90-36" encima de la línea
$pdf->SetFont('helvetica', 'B', 9); // Fuente normal y tamaño 8
$pdf->SetXY(150, 53); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, $estudiante['nombres'], 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha


// Dibujar una línea al lado derecho del texto
$pdf->SetLineWidth(0.3); // Grosor de la línea
$pdf->Line(150, 60.5, 210, 60.5); // Coordenadas de inicio y fin de la línea (X1, Y1, X2, Y2)
// Y1 y y2 30 SE Y 30 SE AUMENTA PARA BAJAR Y SE DISMINUYE PARA SUBIR)
// x1 se disminuye para mover hacia la izquierda y x2  y disminuye para mover hacia la izquierda y se aumenta para ir hacia hacia la derecha




// Añadir el texto "IV. Planteles donde cursò estudios:"
$pdf->SetFont('helvetica', 'B', 9); // Fuente normal y tamaño 10
$pdf->SetXY(7, 47); // Ajustar la posición horizontal (X) y vertical (Y)
$pdf->Cell(50, 10, 'III. Datos de Identificaciòn del Estudiante:', 0, 0, 'L'); // Agregar el texto
// DISMINUIR X HACIA LA IZQUIERDA AUMENTAR HACIA LA DERECHA



// Añadir un espaciado para bajar el contenido
$pdf->Ln(8); // Espaciado adicional para bajar el contenido





// Colspan encima
$pdf->SetXY(7.5, 66.1); // Ajustar la posición del colspan
$colspanTable = '
<table border="1" cellpadding="5" style=" font-size: 3px; width: 53.4%;">
    <tr>
       <td colspan="8" style="text-align: center; font-size: 1px;">
            <span style="font-size: 8px; font-weight: bold; "></span>
        </td>
    </tr>
</table>';
$pdf->writeHTMLCell(190, '', '', '', $colspanTable, 0, 1, false, true, '');



//texto

$pdf->SetFont('helvetica', 'B', 9 ); // Fuente y tamaño del texto
$pdf->SetXY(8.5, 63.3); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'PERIODO:'. htmlspecialchars($nombre_curso[0]['nombre_periodo']) , 0, 0, 'L'); // Agrega el texto




// Texto AREAS DE FORMACION
$pdf->SetFont('helvetica', 'B', 9 ); // Fuente y tamaño del texto
$pdf->SetXY(17.5, 70.4); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'AREAS DE FORMACION', 0, 0, 'L'); // Agrega el texto



// Texto AREAS DE FORMACION
$pdf->SetFont('helvetica', 'B', 8 ); // Fuente y tamaño del texto
$pdf->SetXY(86.4, 69.4); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'T-E', 0, 0, 'L'); // Agrega el texto



// Texto AREAS DE FORMACION
$pdf->SetFont('helvetica', 'B', 9 ); // Fuente y tamaño del texto
$pdf->SetXY(27.6, 106); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'IDIOMAS', 0, 0, 'L'); // Agrega el texto



// Texto AREAS DE FORMACION
$pdf->SetFont('helvetica', 'B', 9 ); // Fuente y tamaño del texto
$pdf->SetXY(35, 98.5); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'COMPONENTE DE IDIOMA', 0, 0, 'L'); // Agrega el texto




// Texto AREAS DE FORMACION
$pdf->SetFont('helvetica', 'B', 8 ); // Fuente y tamaño del texto
$pdf->SetXY(62.1, 106); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'APROBADO', 0, 0, 'L'); // Agrega el texto



// Texto AREAS DE FORMACION
$pdf->SetFont('helvetica', 'B', 7); // Fuente y tamaño del texto
$pdf->SetXY(95.5, 106); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'PERIODO', 0, 0, 'L'); // Agrega el texto



// Texto AREAS DE FORMACION
$pdf->SetFont('helvetica', 'B', 9 ); // Fuente y tamaño del texto
$pdf->SetXY(127, 98.5); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'COMPONENTE DE FORMACIÒN LABORAL', 0, 0, 'L'); // Agrega el texto


// Texto AREAS DE FORMACION
$pdf->SetFont('helvetica', 'B', 9 ); // Fuente y tamaño del texto
$pdf->SetXY(130.7, 106); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'OFICIO', 0, 0, 'L'); // Agrega el texto



// Texto AREAS DE FORMACION
$pdf->SetFont('helvetica', 'B', 8 ); // Fuente y tamaño del texto
$pdf->SetXY(164, 106); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'APROBADO', 0, 0, 'L'); // Agrega el texto




// Texto AREAS DE FORMACION
$pdf->SetFont('helvetica', 'B', 7); // Fuente y tamaño del texto
$pdf->SetXY(197.7, 106); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'PERIODO', 0, 0, 'L'); // Agrega el texto






// Tabla HTML izquierda superior



$pdf->SetXY(8.5, 70.6); // Posicionar esta tabla hacia la izquierda
// Añadir un espaciado para bajar el contenido

//prueba6

foreach ($nombre_curso as $materia) {
    
$html = '
<table border="1" cellpadding="1" style="width: 100%; float: left; border-collapse: collapse;">


  <tr>
    <th rowspan="2" style="width: 156.4px; height: 20px; vertical-align: middle; font-size: 8px; border: 1px solid black; text-align: center; padding: 2px;"> </th>
    <th colspan="2" style="width: 65px; height: 9px; border: 1px solid black; text-align: center; vertical-align: middle; padding: 2px;"><b>Calificación</b></th>
    <th rowspan="2" style="width: 18px; vertical-align: middle; border: 1px solid black; text-align: center; padding: 2px;"> </th>
    <th colspan="2" style="width: 45px; border: 1px solid black; text-align: center; padding: 2px;"><b>Fecha</b></th>
  
</tr>
<tr>
    <th style="width: 20px; border: 1px solid black; text-align: center; vertical-align: middle; padding: 2px;">N°</th>
    <th style="width: 45px; border: 1px solid black; text-align: center; vertical-align: middle; padding: 2px;">Letras</th>
    <th style="width: 22px; border: 1px solid black; text-align: center; vertical-align: middle; padding: 2px;">Mes</th>
    <th style="width: 23px; border: 1px solid black; text-align: center; vertical-align: middle; padding: 2px;">Año</th>
</tr>

  <tr>
    <td style="text-align: left; height: 5px; font-size: 7px; border: 1px solid black; padding: 5px;" class="custom-height">'.
    $nombre_curso[0]['nombre_curso'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.
    $nombre_curso[0]['calificacion'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.
    $nombre_curso[0]['calificacion_letras'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.
    $nombre_curso[0]['te'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.
    $nombre_curso[0]['mes'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.
    $nombre_curso[0]['anio'].'</td>

  </tr>
  <tr>
    <td style="text-align: left; height: 5px; font-size: 7px; border: 1px solid black; padding: 5px;" class="custom-height">'.$nombre_curso[1]['nombre_curso'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'. $nombre_curso[1]['calificacion'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'. $nombre_curso[1]['calificacion_letras'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'. $nombre_curso[1]['te'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$nombre_curso[1]['mes'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'. $nombre_curso[1]['anio'].'</td>

  </tr>
  <tr>
    <td style="text-align: left; height: 5px; font-size: 7px; border: 1px solid black; padding: 5px;" class="custom-height">'.$nombre_curso[2]['nombre_curso'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'. $nombre_curso[2]['calificacion'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$nombre_curso[2]['calificacion_letras'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'. $nombre_curso[2]['te'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'. $nombre_curso[2]['mes'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'. $nombre_curso[2]['anio'].'</td>
 
  </tr>
  <tr>
    <td style="text-align: left; height: 5px; font-size: 7px; border: 1px solid black; padding: 5px;" class="custom-height">'.$nombre_curso[3]['nombre_curso'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'. $nombre_curso[3] ['calificacion'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'. $nombre_curso[3] ['calificacion_letras'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'. $nombre_curso[3] ['te'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'. $nombre_curso[3] ['mes'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'. $nombre_curso[3] ['anio'].'</td>

  </tr>

  <!-- Aprobado / No Aprobado -->
  <tr>
    <td style="text-align: left;  font-size: 7px; border: 1px solid black; vertical-align: middle; padding: 5px;" rowspan="2">'.$nomb.'</td>
    <td colspan="2" style="width: 65px; height: 5px; font-size: 7px; border: 1px solid black; text-align: left; vertical-align: middle; padding: 5px;">APROBADO</td>
   <td style="text-align: center;">'.($califica === 'APROBADO' ? htmlspecialchars($te) : '***').'</td>
            <td style="text-align: center;">'.($califica === 'APROBADO' ? htmlspecialchars($mesx) : '***').'</td>
            <td style="text-align: center;">'.($califica === 'APROBADO' ? htmlspecialchars($anio) : '***').'</td>

  </tr>
  <tr>
    <td colspan="2" style="width: 65px; height: 5px; font-size: 7px; border: 1px solid black; text-align: left; vertical-align: middle; padding: 5px;">NO APROBADO</td>
     <td style="text-align: center;">'.($califica !== 'APROBADO' ? htmlspecialchars($te) : '***').'</td>
            <td style="text-align: center;">'.($califica !== 'APROBADO' ? htmlspecialchars($mesx) : '***').'</td>
            <td style="text-align: center;">'.($califica !== 'APROBADO' ? htmlspecialchars($anio) : '***').'</td>
           
  </tr>
</table>';
}


$pdf->writeHTML($html, true, false, true, false, ''); // Escribe la tabla en el PDF










//AREAS DE FORMACION

$pdf->SetXY(8.5, 101); // Posicionar esta tabla hacia la izquierda
// Añadir un espaciado para bajar el contenido
$html = '
<table border="1" cellpadding="2" cellspacing="0" style="font-size:8px; width: 52.1%; border-collapse: collapse; text-align: center;">
    <tr>
        <td colspan="5" style="font-weight: bold;"></td>
    </tr>
    <tr>
       <td rowspan="2" style="font-weight: bold; width: 54%;"> </td>
        <td rowspan="2" style="font-weight: bold; width: 18%;"> </td>
        <td colspan="2" style="font-weight: bold; width: 15%;">Fecha</td>
        <td rowspan="2" style="font-weight: bold; width: 13%;"> </td>
    </tr>
    <tr>
        <td style="font-weight: bold; width: 7%;">Mes</td>
        <td style="font-weight: bold; width: 8%;">Año</td>
    </tr>
    <tr>
        <td style="font-size:7px; ">'.$materia1['nombre_curso'] .'</td>
        <td style="font-size:7px;">'.$materia1['calificacion_letras'] .'</td>
        <td>'.$materia1['mes'] .'</td>
        <td>'.$materia1['anio'] .'</td>
        <td>'.$materia1['numero_periodo'] .'</td>
    </tr>
    <tr>
        <td >***</td>
        <td>*</td>
        <td>*</td>
        <td>*</td>
        <td>*</td>
    </tr>
    <tr>
        <td style="font-size:7px; height: 3px;>***</td>
        <td>*</td>
        <td>*</td>
        <td>*</td>
        <td>*</td>
    </tr>
    <tr>
        <td style="font-size:7px; height: 3px; >***</td>
        <td>*</td>
        <td>*</td>
        <td>*</td>
        <td>*</td>
    </tr>
</table>

';






$pdf->writeHTML($html, true, false, true, false, ''); // Escribe la tabla en el PDF










// Colspan encima mensaje
$pdf->SetXY(109.6, 66); // Ajustar la posición del colspan
$colspanTable = '
<table border="1" cellpadding="5" style=" font-size: 3px; width: 53.3%;  ">
    <tr>
       <td colspan="8" style="text-align: center; font-size: 1px; height: 99.2px;">
            <span style="font-size: 8px; font-weight: bold; "></span>
        </td>
    </tr>
</table>';
$pdf->writeHTMLCell(190, '', '', '', $colspanTable, 0, 1, false, true, '');



$pdf->SetXY(110.5, 101); // Posicionar esta tabla hacia la izquierda
// Añadir un espaciado para bajar el contenido

//prueba8

$html2 = '
<table border="1" cellpadding="2" cellspacing="0" style="font-size:8px; width: 110.9%; border-collapse: collapse; text-align: center;">
    <tr>
        <td colspan="5" style="font-weight: bold;"></td>
    </tr>
    <tr>
           <td rowspan="2" style="font-weight: bold; width: 54%;"> </td>
        <td rowspan="2" style="font-weight: bold; width: 18%;"> </td>
        <td colspan="2" style="font-weight: bold; width: 15%;">Fecha</td>
        <td rowspan="2" style="font-weight: bold; width: 13%;"> </td>
    </tr>
    <tr>
        <td style="font-weight: bold; width: 7%;">Mes</td>
        <td style="font-weight: bold; width: 8%;">Año</td>
    </tr>';
   foreach ($materiasSeleccionadas as $materia1) {
    $html2 .= '
       <tr >
            <td style="font-size:7px; ">'.$materia1['nombre_curso'] .'</td>
        <td style="font-size:7px;">'.$materia1['calificacion_letras'] .'</td>
        <td>'.$materia1['mes'] .'</td>
        <td>'.$materia1['anio'] .'</td>
        <td>'.$materia1['numero_periodo'] .'</td>
    </tr>
    <tr>
        <td style="font-size:7px;">' . htmlspecialchars($materia3['nombre_curso']) . '</td>
        <td style="font-size:7px;">' . htmlspecialchars($materia3['calificacion_letras']) . '</td>
        <td>' . htmlspecialchars($materia3['mes']) . '</td>
        <td>' . htmlspecialchars($materia3['anio']) . '</td>
        <td>' . htmlspecialchars($materia3['numero_periodo']) . '</td>
    </tr>

    <tr>
        <td style="font-size:7px; height: 3px; >***</td>
        <td>*</td>
        <td>*</td>
        <td>*</td>
        <td>*</td>
         
    </tr>';
}

// Cerrar la tabla
$html2 .= '</table>';


$pdf->writeHTML($html2, true, false, true, false, ''); // Escribe la tabla en el PDF




//texto final



$pdf->SetFont('helvetica', 'B', 9); // Fuente y tamaño del texto
$pdf->SetXY(8.5, 128.2); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'IX. Plantel:', 0, 0, 'L'); // Agrega el texto



$pdf->SetFont('helvetica', 'B', 9); // Fuente y tamaño del texto
$pdf->SetXY(8.5, 133.5); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'Director(a)', 0, 0, 'L'); // Agrega el texto




$pdf->SetFont('helvetica', 'B', 9); // Fuente y tamaño del texto
$pdf->SetXY(8.5, 138.7); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'Apellidos y Nombres:', 0, 0, 'L'); // Agrega el texto



$pdf->SetFont('helvetica', 'B', 9); // Fuente y tamaño del texto
$pdf->SetXY(20, 144); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'JHONNY VILORIA', 0, 0, 'L'); // Agrega el texto




$pdf->SetFont('helvetica', 'B', 9); // Fuente y tamaño del texto
$pdf->SetXY(8.5, 149.3); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'Nùmero de C.I:', 0, 0, 'L'); // Agrega el texto





$pdf->SetFont('helvetica', 'B', 9); // Fuente y tamaño del texto
$pdf->SetXY(25.5, 154.6); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'V-14206691', 0, 0, 'L'); // Agrega el texto


$pdf->SetFont('helvetica', 'B', 9); // Fuente y tamaño del texto
$pdf->SetXY(8.5, 159.7); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'Firma:', 0, 0, 'L'); // Agrega el texto




$pdf->SetXY(8.5, 130.5); // Posicionar esta tabla hacia la izquierda
// Añadir un espaciado para bajar el contenido

//tablafinal1

$html = '

<table border="1" cellpadding="1" cellspacing="0" style="font-size:8px; width: 48.81%; border-collapse: collapse; text-align: center; line-height: 0.7;">
  
<tr><td style="text-align: left; width:57.6%; height: 15px; font-size: 8px; "></td></tr>
  <tr><td style="text-align: left; width:57.6%; height: 15px; font-size: 8px; "></td></tr>
  <tr><td style="text-align: left; width:57.6%; height: 15px;font-size: 8px; "></td></tr>
  <tr><td style="text-align: center; width:57.6%; height: 15px; font-size: 8px; "> </td></tr>
  <tr><td style="text-align: left; width:57.6%; height: 15px; font-size: 8px; "></td></tr>
  <tr><td style="text-align: center; width:57.6%; height: 15px; font-size: 8px; "> </td></tr>
  <tr><td style="text-align: left; width:57.6%; height: 15px; font-size: 8px; "> </td></tr>
  
</table>



';

// Escribe el HTML
$pdf->writeHTML($html, true, false, true, false, '');




// Colspan debajo
$pdf->SetXY(61.7, 130.5); // Ajustar la posición del colspan
$colspanTable = '
<table border="1" cellpadding="5" style=" font-size: 3px; width: 24.5%;">
    <tr>
       <td colspan="8" style="text-align: center; height: 104.8px; font-size: 1px;">
            <span style="font-size: 8px; font-weight: bold; "></span>
        </td>
    </tr>
</table>';
$pdf->writeHTMLCell(190, '', '', '', $colspanTable, 0, 1, false, true, '');


//texto final


$pdf->SetFont('helvetica', 'B', 9); // Fuente y tamaño del texto
$pdf->SetXY(110.5, 128.2); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'X. Profesor Guìa:', 0, 0, 'L'); // Agrega el texto



$pdf->SetFont('helvetica', 'B', 9); // Fuente y tamaño del texto
$pdf->SetXY(110.5, 133.5); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'Apellidos y Nombres:', 0, 0, 'L'); // Agrega el texto




$pdf->SetFont('helvetica', 'B', 9); // Fuente y tamaño del texto
$pdf->SetXY(110.5, 138.7); // Ajusta la posición del texto
$pdf->Cell(60, 10, ' ', 0, 0, 'L'); // Agrega el texto



$pdf->SetFont('helvetica', 'B', 9); // Fuente y tamaño del texto
$pdf->SetXY(110.5, 144); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'Nùmero de C.I: ', 0, 0, 'L'); // Agrega el texto




$pdf->SetFont('helvetica', 'B', 9); // Fuente y tamaño del texto
$pdf->SetXY(110.5, 149.3); // Ajusta la posición del texto
$pdf->Cell(60, 10, ' ', 0, 0, 'L'); // Agrega el texto





$pdf->SetFont('helvetica', 'B', 9); // Fuente y tamaño del texto
$pdf->SetXY(110.5, 154.6); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'Firma: ', 0, 0, 'L'); // Agrega el texto


$pdf->SetFont('helvetica', 'B', 9); // Fuente y tamaño del texto
$pdf->SetXY(110.5, 159.7); // Ajusta la posición del texto
$pdf->Cell(60, 10, '', 0, 0, 'L'); // Agrega el texto





// texto motivacional



$pdf->SetFont('helvetica', 'B', 12); // Fuente y tamaño del texto
$pdf->SetXY(114, 75); // Posición del texto

// Texto combinado
$textoMotivacional = $plan['descripcion'];

// MultiCell: ancho=60, alto=10, texto, sin borde, alineado a la izquierda
$pdf->MultiCell(60, 10, $textoMotivacional, 0, 'L');





$pdf->SetXY(110.5, 130.5); // Posicionar esta tabla hacia la izquierda
// Añadir un espaciado para bajar el contenido

//tablafinal

$html = '

<table border="1" cellpadding="1" cellspacing="0" style="font-size:8px; width: 104%; border-collapse: collapse; text-align: center; line-height: 0.7;">
  
<tr><td style="text-align: left; width:57.6%; height: 15px; font-size: 8px; "></td></tr>
  <tr><td style="text-align: left; width:57.6%; height: 15px; font-size: 8px; "></td></tr>
  <tr><td style="text-align: left; width:57.6%; height: 15px;font-size: 8px; "></td></tr>
  <tr><td style="text-align: center; width:57.6%; height: 15px; font-size: 8px; "> </td></tr>
  <tr><td style="text-align: left; width:57.6%; height: 15px; font-size: 8px; "></td></tr>
  <tr><td style="text-align: center; width:57.6%; height: 15px; font-size: 8px; "> </td></tr>
  <tr><td style="text-align: left; width:57.6%; height: 15px; font-size: 8px; "> </td></tr>
  
</table>



';

// Escribe el HTML
$pdf->writeHTML($html, true, false, true, false, '');





// Colspan debajo
$pdf->SetXY(163.6, 130.5); // Ajustar la posición del colspan
$colspanTable = '
<table border="1" cellpadding="5" style=" font-size: 3px; width: 24.6%;">
    <tr>
       <td colspan="8" style="text-align: center; height: 104.8px; font-size: 1px;">
            <span style="font-size: 8px; font-weight: bold; "></span>
        </td>
    </tr>
</table>';
$pdf->writeHTMLCell(190, '', '', '', $colspanTable, 0, 1, false, true, '');



ob_end_clean(); // Limpia la salida antes de generar el PDF
$pdf->Output('boletin.pdf', 'I');

echo '<script>window.onload = function() { window.print(); }</script>';
?>