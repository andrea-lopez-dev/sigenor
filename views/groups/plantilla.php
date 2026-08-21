<?php
ob_start(); // Inicia el almacenamiento en búfer de salida

require_once '../../vendor/autoload.php';
require_once '../../Config/Config.php';// Conectar a la base de datos

// Consulta para obtener datos de los estudiantes
$sentencia = $connect->prepare("SELECT 
    estudiantes.id_estudiante, estudiantes.cedula, estudiantes.nombres, estudiantes.apellidos, 
    estudiantes.lugar_nacimiento, estudiantes.entidad_federal, estudiantes.sexo, estudiantes.fecha_nacimiento,
    seccion.nombre_seccion
FROM estudiantes 
INNER JOIN seccion ON estudiantes.id_seccion = seccion.id_seccion 
INNER JOIN estudiantes_planteles ON estudiantes.id_estudiante = estudiantes_planteles.id_estudiante 
GROUP BY estudiantes.id_estudiante, estudiantes.nombres 
LIMIT 30");

$sentencia->execute();
$students = $sentencia->fetchAll(PDO::FETCH_ASSOC); // Obtener datos de los estudiantes


$rows = [];


$id_seccion = $_GET['id'] ?? null;
if ($id_seccion) {
    $count_stmt = $connect->prepare("
        SELECT COUNT(DISTINCT estudiantes.id_estudiante) AS total_estudiantes
        FROM estudiantes 
        INNER JOIN seccion ON estudiantes.id_seccion = seccion.id_seccion 
        INNER JOIN estudiantes_planteles ON estudiantes.id_estudiante = estudiantes_planteles.id_estudiante 
        WHERE seccion.id_seccion = :id_seccion
    ");
    $count_stmt->bindParam(':id_seccion', $id_seccion, PDO::PARAM_INT);
    $count_stmt->execute();
    $resultado = $count_stmt->fetch(PDO::FETCH_ASSOC);
    $total_estudiantes = $resultado ? $resultado['total_estudiantes'] : 0;
} else {
    $total_estudiantes = 0; // O manejar error por falta de id_seccion
}

$id_periodo_seleccionado = $_GET['periodo'] ?? $id_periodo_actual ?? null;

if (!$id_periodo_seleccionado) {
    die("No se seleccionó un período válido.");
}

foreach ($students as $index => $student) {
    // Separar fecha de nacimiento
    list($dia, $mes, $anio) = explode("-", $student['fecha_nacimiento']);

    // Paso 1: Verificamos si el estudiante tiene calificaciones en el período seleccionado
    $periodo_stmt = $connect->prepare("
        SELECT DISTINCT periodos.id_periodo, periodos.numero_periodo 
        FROM calificaciones 
        INNER JOIN periodos ON calificaciones.id_periodo = periodos.id_periodo 
        WHERE calificaciones.id_estudiante = :id_estudiante 
        AND calificaciones.id_periodo = :id_periodo
        LIMIT 1
    ");
    $periodo_stmt->bindParam(':id_estudiante', $student['id_estudiante']);
    $periodo_stmt->bindParam(':id_periodo', $id_periodo_seleccionado);
    $periodo_stmt->execute();
    $periodo = $periodo_stmt->fetch(PDO::FETCH_ASSOC);

    // Si no tiene calificaciones en ese período, lo ignoramos
    if (!$periodo) {
        continue;
    }

    // Paso 2: Obtener las calificaciones del período seleccionado
    $calificaciones_stmt = $connect->prepare("
        SELECT asignaturas.nombre_curso, calificaciones.calificacion, calificaciones.calificacion_letras 
        FROM calificaciones 
        INNER JOIN asignaturas ON calificaciones.id_curso = asignaturas.id_curso 
        WHERE calificaciones.id_estudiante = :id_estudiante 
        AND calificaciones.id_periodo = :id_periodo
    ");
    $calificaciones_stmt->bindParam(':id_estudiante', $student['id_estudiante']);
    $calificaciones_stmt->bindParam(':id_periodo', $id_periodo_seleccionado);
    $calificaciones_stmt->execute();
    $calificaciones = $calificaciones_stmt->fetchAll(PDO::FETCH_ASSOC);

    // Paso 3: Manejo de calificaciones vacías o incompletas
    if (empty($calificaciones)) {
        $cursos = array_fill(0, 4, '*******');
        $componentes = array_fill(0, 8, '*******');
    } else {
        $cursos = array_column($calificaciones, 'calificacion');
        $componentes = array_column($calificaciones, 'calificacion_letras');

        while (count($cursos) < 4) { $cursos[] = "*"; }
        while (count($componentes) < 8) { $componentes[] = "*"; }
    }

    // Paso 4: Agregar fila al arreglo final
    $rows[] = [
        str_pad($index + 1, 2, '0', STR_PAD_LEFT),
        $student['cedula'],
        $student['apellidos'],
        $student['nombres'],
        $student['lugar_nacimiento'],
        substr($student['entidad_federal'], 0, 2),
        substr($student['sexo'], 0, 1),
        $anio, $mes, $dia,
        substr($cursos[0], 0, 1),
        substr($cursos[1], 0, 1),
        substr($cursos[2], 0, 1),
        substr($cursos[3], 0, 1),
        substr($componentes[5], 0, 1),
        substr($componentes[6], 0, 1),
        substr($componentes[7], 0, 1)
    ];
}

// (Opcional) Mostrar el nombre del período seleccionado




$consultaPeriodo = $connect->prepare("
    SELECT id_periodo, nombre_periodo, numero_periodo 
    FROM periodos 
    WHERE id_periodo = :id_periodo
");
$consultaPeriodo->bindParam(':id_periodo', $id_periodo_seleccionado);
$consultaPeriodo->execute();
$periodoSeleccionado = $consultaPeriodo->fetch(PDO::FETCH_ASSOC);

function convertirNombrePeriodo($nombre) {
    $mapa = [
        'UNO'   => 'PRIMERO',
        'DOS'   => 'SEGUNDO',
        'TRES'  => 'TERCERO',
        'CUATRO'=> 'CUARTO',
        'CINCO' => 'QUINTO',
        'SEIS'  => 'SEXTO'
    ];
    $nombre = strtoupper(trim($nombre));
    return $mapa[$nombre] ?? $nombre;
}

function convertirNumeroPeriodo($numero) {
    $mapa = [
        1 => '1RO',
        2 => '2DO',
        3 => '3RO',
        4 => '4TO',
        5 => '5TO',
        6 => '6TO'
    ];
    return $mapa[intval($numero)] ?? $numero;
}

// Mostrar periodo si existe
if ($periodoSeleccionado) {
    $nombreConvertido = convertirNombrePeriodo($periodoSeleccionado['nombre_periodo']);
    $numeroConvertido = convertirNumeroPeriodo($periodoSeleccionado['numero_periodo']);
   
      }

$consultaSeccion = $connect->prepare("
    SELECT id_seccion, nombre_seccion, capacidad, fecha, estado 
    FROM seccion 
    WHERE id_seccion = :id_seccion
");
$consultaSeccion->bindParam(':id_seccion', $id_seccion);
$consultaSeccion->execute();
$seccionSeleccionada = $consultaSeccion->fetch(PDO::FETCH_ASSOC);



$fechaActual = date("Y-m-d");

$consultaPeriodoActual = $connect->prepare("
    SELECT id_periodo, nombre_periodo, numero_periodo, fecha_inicio, fecha_fin
    FROM periodos 
    WHERE :fecha_actual BETWEEN fecha_inicio AND fecha_fin
    LIMIT 1
");
$consultaPeriodoActual->bindParam(':fecha_actual', $fechaActual);
$consultaPeriodoActual->execute();
$periodoActual = $consultaPeriodoActual->fetch(PDO::FETCH_ASSOC);

if ($periodoActual) {
    $fechaInicio = date("Y", strtotime($periodoActual['fecha_inicio']));
    $fechaFin = date("Y", strtotime($periodoActual['fecha_fin']));
   
} 

// **Completar hasta 30 filas si hay menos estudiantes**
$total_estudiantes = count($rows);
for ($i = $total_estudiantes + 1; $i <= 30; $i++) {
    $rows[] = [
        str_pad($i, 2, '0', STR_PAD_LEFT), // N°
        '***',                       // Cédula de Identidad
        '***',                       // Apellidos
        '***',                       // Nombres
        '***',                       // Lugar de Nacimiento
        '**',                       // EF
        '*',                               // Sexo
        '*',                              // Día
        '*',                              // Mes
        '*',                            // Año
        '*', '*', '*', '*', '*', '*', '*'  // Componentes
    ];
}

$sql = "SELECT id_plan_est, plan_estudio, codigo_estudio, estrategia_estudio, tipo_evaluacion, descripcion, fecha_estudio, COUNT(*) AS conteo FROM plan_administrativo";
$stmt = $connect->prepare($sql);
$stmt->execute();

$planesAdministrativos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ejemplo de uso:
foreach ($planesAdministrativos as $plan) {
}

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

$lugar_fecha= "$mes $ano"; // Formato de fecha


//============================================ 
// CONSULTA FINAL CON "*" SI FALTA NOTA EN ALGUNA ASIGNATURA DEL PERÍODO
// ============================================
$totales = [
    'inscritos' => array_fill(0, 7, 0),
    'inasistentes' => array_fill(0, 7, 0),
    'aprobados' => array_fill(0, 7, 0),
    'no_aprobados' => array_fill(0, 7, 0)
];

foreach ($rows as $row) {
    // Notas desde columna 10 a 16 (7 materias)
    $notas = array_slice($row, 10, 7);

    foreach ($notas as $i => $nota) {
        // Si está inscrito (diferente de '*')
        if ($nota !== '*') {
            $totales['inscritos'][$i]++;

            if ($nota === 'I') {
                $totales['inasistentes'][$i]++;
            } elseif (is_numeric($nota)) {
                $nota = (float)$nota;
                if ($nota >= 3) {
                    $totales['aprobados'][$i]++;
                } else {
                    $totales['no_aprobados'][$i]++;
                }
            } elseif (in_array($nota, ['A', 'R'])) {
                if ($nota === 'A') {
                    $totales['aprobados'][$i]++;
                } else {
                    $totales['no_aprobados'][$i]++;
                }
            }
        }
    }
}

// Formato de salida: si el total es 0, mostrar '**'
$mostrarTotales = [];
foreach ($totales as $categoria => $valores) {
    foreach ($valores as $i => $valor) {
        $mostrarTotales[$categoria][$i] = $valor > 0 ? str_pad($valor, 2, "0", STR_PAD_LEFT) : '**';
    }
}


$asignaturasPeriodo = [];

if ($id_periodo_seleccionado) {
    $consultaAsignaturas = $connect->prepare("
        SELECT 
            a.nombre_curso,
            a.descripcion,
            p.nombre_periodo,
            pr.id_profesor,
            pr.nombre_apellido,
            pr.cedula_profesor
        FROM asignaturas a
        INNER JOIN periodos p ON a.id_periodo = p.id_periodo
        LEFT JOIN profesores pr ON a.id_profesor = pr.id_profesor
        WHERE a.id_periodo = :id_periodo
        ORDER BY a.id_curso ASC
    ");

    $consultaAsignaturas->bindParam(':id_periodo', $id_periodo_seleccionado, PDO::PARAM_INT);
    $consultaAsignaturas->execute();
    $asignaturasPeriodo = $consultaAsignaturas->fetchAll(PDO::FETCH_ASSOC);
}

// Crear arreglos $materia[0..6], $profesor_nombre[0..6], $profesor_cedula[0..6], $profesor_id[0..6]
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



// Crear una nueva instancia de TCPDF con tamaño de hoja personalizado (Oficio: 8.5 x 14 pulgadas)
$pdf = new TCPDF('P', 'mm', array(215.9, 330.2), true, 'UTF-8', false);

// Configurar los márgenes
$pdf->SetMargins(10, 5, 10); // Margen izquierdo, superior (30 mm), y derecho
$pdf->SetHeaderMargin(10); // Margen del encabezado
$pdf->SetFooterMargin(5); // Margen del pie de página

// Configuración del documento
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Sistema Escolar');
$pdf->SetTitle('Resumen de rendimiento estudiantil');
$pdf->SetSubject('Resumen de rendimiento estudiantil');
$pdf->SetKeywords('Resumen, Curricular, Venezuela');

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
$pdf->SetXY(120, 8); // Ajustar la posición horizontal (X) más hacia la izquierda
$pdf->Cell(0, 10, 'RESUMEN DE RENDIMIENTO ESTUDIANTIL', 0, 1, 'L'); // Agregar el texto

// Dibujar una línea más pegada al texto
$pdf->SetLineWidth(0.3); // Grosor de la línea
$pdf->Line(121, 15, 201.7, 15); // Ajustar la línea para que coincida con la nueva posición del texto

// Añadir el texto "Código del Formato: EMGMJAA" centrado debajo de "CERTIFICACIÓN DE CALIFICACIONES"
$pdf->SetFont('helvetica', 'B', 9); // Fuente normal y tamaño 10
$pdf->SetXY(117, 13); // Ajustar la posición vertical (Y) debajo de la línea
$pdf->Cell(0, 10, 'Código del Formato: EMGMJAA', 0, 1, 'C'); // Texto centrado

// Añadir el texto "I. Año escolar:" debajo de "Código del Formato: EMGMJAA"
$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 10
$pdf->SetXY(100, 20.5); // Ajustar la posición horizontal (X) y vertical (Y)
$pdf->Cell(50, 10, 'I. Año escolar:', 0, 0, 'L'); // Agregar el texto
// DISMINUIR X HACIA LA IZQUIERDA AUMENTAR HACIA LA DERECHA


// Añadir el texto "31058" encima de la línea
$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 8
$pdf->SetXY(135, 20.7); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(0, 10, $fechaInicio.'-'.$fechaFin, 0, 0, 'L'); // Agregar el texto
// Disminuir Y y para bajar y aumentar para subir
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha

// Dibujar una línea al lado derecho del texto
$pdf->SetLineWidth(0.3); // Grosor de la línea
$pdf->Line(121, 27.5, 160, 27.5); // Coordenadas de inicio y fin de la línea (X1, Y1, X2, Y2)
// 30 SE Y 30 SE AUMENTA PARA BAJAR Y SE DISMINUYE PARA SUBIR)


// Añadir el texto "Tipo de Evaluaciòn:"
$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 10
$pdf->SetXY(100, 27); // Ajustar la posición horizontal (X) y vertical (Y)
$pdf->Cell(50, 10, 'Tipo de Evaluaciòn:', 0, 0, 'L'); // Agregar el texto
// DISMINUIR X HACIA LA IZQUIERDA AUMENTAR HACIA LA DERECHA
// Disminuir Y para subir y aumentar para bajar

// Añadir el texto "Ingresar" encima de la línea
$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 8
$pdf->SetXY(136, 27.2); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, $plan['tipo_evaluacion'], 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha

// Dibujar una línea al lado derecho del texto
$pdf->SetLineWidth(0.3); // Grosor de la línea
$pdf->Line(128, 34, 160, 34); // Coordenadas de inicio y fin de la línea (X1, Y1, X2, Y2)
// 30 SE Y 30 SE AUMENTA PARA BAJAR Y SE DISMINUYE PARA SUBIR)



// Añadir el texto "Periodo:" debajo de "Código del Formato: EMGMJAA"
$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 10
$pdf->SetXY(160, 20.5); // Ajustar la posición horizontal (X) y vertical (Y)
$pdf->Cell(50, 10, 'Periodo:', 0, 0, 'L'); // Agregar el texto
// DISMINUIR X HACIA LA IZQUIERDA AUMENTAR HACIA LA DERECHA


// Añadir el texto "Ingresar" encima de la línea
$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 8
$pdf->SetXY(185, 20.5); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10,$nombreConvertido, 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha


// Dibujar una línea al lado derecho del texto
$pdf->SetLineWidth(0.3); // Grosor de la línea
$pdf->Line(173, 27.5, 210, 27.5); // Coordenadas de inicio y fin de la línea (X1, Y1, X2, Y2)
// 30 SE Y 30 SE AUMENTA PARA BAJAR Y SE DISMINUYE PARA SUBIR)



// Añadir el texto "Mes y Año:"
$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 10
$pdf->SetXY(160, 27); // Ajustar la posición horizontal (X) y vertical (Y)
$pdf->Cell(50, 10, 'Mes y Año:', 0, 0, 'L'); // Agregar el texto
// DISMINUIR X HACIA LA IZQUIERDA AUMENTAR HACIA LA DERECHA
// Disminuir Y para subir y aumentar para bajar

// Añadir el texto "Ingresar" encima de la línea
$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 8
$pdf->SetXY(185, 27.2); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, $lugar_fecha, 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha

// Dibujar una línea al lado derecho del texto
$pdf->SetLineWidth(0.3); // Grosor de la línea
$pdf->Line(176, 34, 210, 34); // Coordenadas de inicio y fin de la línea (X1, Y1, X2, Y2)
// 30 SE Y 30 SE AUMENTA PARA BAJAR Y SE DISMINUYE PARA SUBIR)



// Añadir el texto "II. Datos del Plantel o Zona Educativa que emite la certificaciòn:" debajo de "Código del Formato: EMGMJAA"
$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 10
$pdf->SetXY(7, 30.7); // Ajustar la posición horizontal (X) y vertical (Y)
$pdf->Cell(50, 10, 'II. Datos del Plantel:', 0, 0, 'L'); // Agregar el texto
// DISMINUIR X HACIA LA IZQUIERDA AUMENTAR HACIA LA DERECHA
// Disminuir Y para subir y aumentar para bajar


// Añadir el texto "Código Plantel:"
$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 10
$pdf->SetXY(7, 37); // Ajustar la posición horizontal (X) y vertical (Y)
$pdf->Cell(50, 10, 'Código Plantel:', 0, 0, 'L'); // Agregar el texto
// DISMINUIR X HACIA LA IZQUIERDA AUMENTAR HACIA LA DERECHA


// Añadir el texto "S2033N2313" encima de la línea
$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 8
$pdf->SetXY(30, 37); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, 'S2033N2313', 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha

// Dibujar una línea al lado derecho del texto
$pdf->SetLineWidth(0.3); // Grosor de la línea
$pdf->Line(29, 44, 63, 44); // Coordenadas de inicio y fin de la línea (X1, Y1, X2, Y2)
// Y1 y y2 30 SE Y 30 SE AUMENTA PARA BAJAR Y SE DISMINUYE PARA SUBIR)
// x1 se disminuye para mover hacia la izquierda y x2  y se aumenta para la izquierda y se disminuye hacia la derecha


// Añadir el texto "Nombre:"
$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 10
$pdf->SetXY(63, 37); // Ajustar la posición horizontal (X) y vertical (Y)
$pdf->Cell(50, 10, 'Nombre:', 0, 0, 'L'); // Agregar el texto
// DISMINUIR X HACIA LA IZQUIERDA AUMENTAR HACIA LA DERECHA


// Añadir el texto "UNIDAD EDUCATIVA NOCTURNA BR. RAFAEL RANGEL" encima de la línea
$pdf->SetFont('helvetica', 'B', 9); // Fuente normal y tamaño 8
$pdf->SetXY(98, 37); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, 'U.E.P. NOCT. BR. RAFAEL RANGEL', 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha

// Dibujar una línea al lado derecho del texto
$pdf->SetLineWidth(0.3); // Grosor de la línea
$pdf->Line(210, 44, 76, 44); // Coordenadas de inicio y fin de la línea (X1, Y1, X2, Y2)
// Y1 y y2 30 SE Y 30 SE AUMENTA PARA BAJAR Y SE DISMINUYE PARA SUBIR)
// x1 se disminuye para mover hacia la izquierda y x2  y disminuye para mover hacia la izquierda y se aumenta para ir hacia hacia la derecha

// Añadir el texto "Direcciòn:"
$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 10
$pdf->SetXY(7, 43); // Ajustar la posición horizontal (X) y vertical (Y)
$pdf->Cell(50, 10, 'Direcciòn:', 0, 0, 'L'); // Agregar el texto
// DISMINUIR X HACIA LA IZQUIERDA AUMENTAR HACIA LA DERECHA


// Añadir el texto "SECTOR PANAMERICANO CALLE 69B No. 90-36" encima de la línea
$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 8
$pdf->SetXY(50, 43); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, 'SECTOR PANAMERICANO CALLE 69B No. 90-36', 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha

// Dibujar una línea al lado derecho del texto
$pdf->SetLineWidth(0.3); // Grosor de la línea
$pdf->Line(143, 50, 22.5, 50); // Coordenadas de inicio y fin de la línea (X1, Y1, X2, Y2)
// Y1 y y2 30 SE Y 30 SE AUMENTA PARA BAJAR Y SE DISMINUYE PARA SUBIR)
// x1 se disminuye para mover hacia la izquierda y x2  y disminuye para mover hacia la izquierda y se aumenta para ir hacia hacia la derecha

// Añadir el texto "Telèfono:"
$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 10
$pdf->SetXY(143, 43); // Ajustar la posición horizontal (X) y vertical (Y)
$pdf->Cell(50, 10, 'Telèfono:', 0, 0, 'L'); // Agregar el texto
// DISMINUIR X HACIA LA IZQUIERDA AUMENTAR HACIA LA DERECHA


// Añadir el texto "0424-6787968" encima de la línea
$pdf->SetFont('helvetica', '', 8); // Fuente normal y tamaño 8
$pdf->SetXY(172, 43); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, '0424-6787968', 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha

// Dibujar una línea al lado derecho del texto
$pdf->SetLineWidth(0.3); // Grosor de la línea
$pdf->Line(156.5, 50, 210, 50); // Coordenadas de inicio y fin de la línea (X1, Y1, X2, Y2)
// Y1 y y2 30 SE Y 30 SE AUMENTA PARA BAJAR Y SE DISMINUYE PARA SUBIR)
// x1 se disminuye para mover hacia la izquierda y x2  y disminuye para mover hacia la izquierda y se aumenta para ir hacia hacia la derecha

// Añadir el texto "Municipio:"
$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 10
$pdf->SetXY(7, 49); // Ajustar la posición horizontal (X) y vertical (Y)
$pdf->Cell(50, 10, 'Municipio:', 0, 0, 'L'); // Agregar el texto
// DISMINUIR X HACIA LA IZQUIERDA AUMENTAR HACIA LA DERECHA


// Añadir el texto "MARACAIBO" encima de la línea
$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 8
$pdf->SetXY(40, 49); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, 'MARACAIBO', 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha

// Dibujar una línea al lado derecho del texto
$pdf->SetLineWidth(0.3); // Grosor de la línea
$pdf->Line(77.8, 55.7, 23, 55.7); // Coordenadas de inicio y fin de la línea (X1, Y1, X2, Y2)
// Y1 y y2 30 SE Y 30 SE AUMENTA PARA BAJAR Y SE DISMINUYE PARA SUBIR)
// x1 se AUMENTA para mover hacia la derecha y se disminuye para ir hacia la izquierda y x2  y disminuye para mover hacia la izquierda y se aumenta para ir hacia hacia la derecha

// Añadir el texto "Entidad Federal:"
$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 10
$pdf->SetXY(77, 49); // Ajustar la posición horizontal (X) y vertical (Y)
$pdf->Cell(50, 10, 'Entidad Federal:', 0, 0, 'L'); // Agregar el texto
// DISMINUIR X HACIA LA IZQUIERDA AUMENTAR HACIA LA DERECHA


// Añadir el texto "ZULIA" encima de la línea
$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 8
$pdf->SetXY(110, 49); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, 'ZULIA', 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha

// Dibujar una línea al lado derecho del texto
$pdf->SetLineWidth(0.3); // Grosor de la línea
$pdf->Line(133.9, 55.7, 100, 55.7); // Coordenadas de inicio y fin de la línea (X1, Y1, X2, Y2)
// Y1 y y2 30 SE Y 30 SE AUMENTA PARA BAJAR Y SE DISMINUYE PARA SUBIR)
// x1 se AUMENTA para mover hacia la derecha y se disminuye para ir hacia la izquierda y x2  y disminuye para mover hacia la izquierda y se aumenta para ir hacia hacia la derecha

// Añadir el texto "Zona Educativa:"
$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 10
$pdf->SetXY(134, 49); // Ajustar la posición horizontal (X) y vertical (Y)
$pdf->Cell(50, 10, 'Zona Educativa:', 0, 0, 'L'); // Agregar el texto
// DISMINUIR X HACIA LA IZQUIERDA AUMENTAR HACIA LA DERECHA


// Añadir el texto "ZULIA" encima de la línea
$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 8
$pdf->SetXY(176, 49); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, 'ZULIA', 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha

// Dibujar una línea al lado derecho del texto
$pdf->SetLineWidth(0.3); // Grosor de la línea
$pdf->Line(156.5, 55.7, 210, 55.7); // Coordenadas de inicio y fin de la línea (X1, Y1, X2, Y2)
// Y1 y y2 30 SE Y 30 SE AUMENTA PARA BAJAR Y SE DISMINUYE PARA SUBIR)
// x1 se disminuye para mover hacia la izquierda y x2  y disminuye para mover hacia la izquierda y se aumenta para ir hacia hacia la derecha



// Añadir el texto "Director(a):"
$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 10
$pdf->SetXY(7, 55); // Ajustar la posición horizontal (X) y vertical (Y)
$pdf->Cell(50, 10, 'Director(a):', 0, 0, 'L'); // Agregar el texto
// DISMINUIR X HACIA LA IZQUIERDA AUMENTAR HACIA LA DERECHA

// Añadir el texto "Nro de cèdula del estudiante" encima de la línea
$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 8
$pdf->SetXY(50, 55); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, 'DR. JHONNY VILORIA', 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha

// Dibujar una línea al lado derecho del texto
$pdf->SetLineWidth(0.3); // Grosor de la línea
$pdf->Line(24, 62, 105, 62); // Coordenadas de inicio y fin de la línea (X1, Y1, X2, Y2)
// Y1 y y2 30 SE Y 30 SE AUMENTA PARA BAJAR Y SE DISMINUYE PARA SUBIR)
// x1 se disminuye para mover hacia la izquierda y x2  y disminuye para mover hacia la izquierda y se aumenta para ir hacia hacia la derecha

// Añadir el texto "Cèdula de Identidad:"
$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 10
$pdf->SetXY(105, 55); // Ajustar la posición horizontal (X) y vertical (Y)
$pdf->Cell(50, 10, 'Cèdula de Identidad:', 0, 0, 'L'); // Agregar el texto
// DISMINUIR X HACIA LA IZQUIERDA AUMENTAR HACIA LA DERECHA


// Añadir el texto "Formato de fecha" encima de la línea
$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 8
$pdf->SetXY(160, 55); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, 'V-14206691', 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha

// Dibujar una línea al lado derecho del texto
$pdf->SetLineWidth(0.3); // Grosor de la línea
$pdf->Line(136, 62, 210, 62); // Coordenadas de inicio y fin de la línea (X1, Y1, X2, Y2)
// Y1 y y2 30 SE Y 30 SE AUMENTA PARA BAJAR Y SE DISMINUYE PARA SUBIR)
// x1 se disminuye para mover hacia la izquierda y x2  y disminuye para mover hacia la izquierda y se aumenta para ir hacia hacia la derecha



// Añadir el texto "IV. Planteles donde cursò estudios:"
$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 10
$pdf->SetXY(7, 63); // Ajustar la posición horizontal (X) y vertical (Y)
$pdf->Cell(50, 10, 'III. Identificaciòn del Estudiante:', 0, 0, 'L'); // Agregar el texto
// DISMINUIR X HACIA LA IZQUIERDA AUMENTAR HACIA LA DERECHA



// Añadir un espaciado para bajar el contenido
$pdf->Ln(8); // Espaciado adicional para bajar el contenido




//texto


// Añadir el texto "N°"
$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 10
$pdf->SetXY(8.2, 84); // Ajustar la posición horizontal (X) y vertical (Y)
$pdf->Cell(50, 10, 'N°', 0, 0, 'L'); // Agregar el texto
// DISMINUIR X HACIA LA IZQUIERDA AUMENTAR HACIA LA DERECHA


$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 10
$pdf->SetXY(16, 82); // Ajustar la posición horizontal (X) y vertical (Y)
$pdf->Cell(50, 10, 'Cédula de', 0, 0, 'L'); // Agregar el texto
// DISMINUIR X HACIA LA IZQUIERDA AUMENTAR HACIA LA DERECHA

$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 10
$pdf->SetXY(16.4, 86); // Ajustar la posición horizontal (X) y vertical (Y)
$pdf->Cell(50, 10, 'Identidad', 0, 0, 'L'); // Agregar el texto
// DISMINUIR X HACIA LA IZQUIERDA AUMENTAR HACIA LA DERECHA


$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 10
$pdf->SetXY(44, 83); // Ajustar la posición horizontal (X) y vertical (Y)
$pdf->Cell(50, 10, 'Apellidos', 0, 0, 'L'); // Agregar el texto
// DISMINUIR X HACIA LA IZQUIERDA AUMENTAR HACIA LA DERECHA


$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 10
$pdf->SetXY(80, 83); // Ajustar la posición horizontal (X) y vertical (Y)
$pdf->Cell(50, 10, 'Nombres', 0, 0, 'L'); // Agregar el texto
// DISMINUIR X HACIA LA IZQUIERDA AUMENTAR HACIA LA DERECHA



$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 10
$pdf->SetXY(106.6, 82); // Ajustar la posición horizontal (X) y vertical (Y)
$pdf->Cell(50, 10, 'Lugar de', 0, 0, 'L'); // Agregar el texto
// DISMINUIR X HACIA LA IZQUIERDA AUMENTAR HACIA LA DERECHA




$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 10
$pdf->SetXY(106.6, 86); // Ajustar la posición horizontal (X) y vertical (Y)
$pdf->Cell(50, 10, 'Nacimiento', 0, 0, 'L'); // Agregar el texto
// DISMINUIR X HACIA LA IZQUIERDA AUMENTAR HACIA LA DERECHA



$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 10
$pdf->SetXY(126.5, 83); // Ajustar la posición horizontal (X) y vertical (Y)
$pdf->Cell(50, 10, 'EF', 0, 0, 'L'); // Agregar el texto
// DISMINUIR X HACIA LA IZQUIERDA AUMENTAR HACIA LA DERECHA



$pdf->SetFont('helvetica', 'B', 8 ); // Fuente y tamaño del texto
$pdf->StartTransform(); // Inicia la transformación
$pdf->Rotate(90, 100, 90); // Rota el texto -90 grados hacia la izquierda en el punto (100, 90)
$pdf->SetXY(97, 120); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'Sexo', 0, 0, 'L'); // Agrega el texto
$pdf->StopTransform(); // Finaliza la transformación



$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 10
$pdf->SetXY(139.5, 69.7); // Ajustar la posición horizontal (X) y vertical (Y)
$pdf->Cell(50, 10, 'FECHA DE', 0, 0, 'L'); // Agregar el texto
// DISMINUIR X HACIA LA IZQUIERDA AUMENTAR HACIA LA DERECHA

$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 10
$pdf->SetXY(137.8, 73); // Ajustar la posición horizontal (X) y vertical (Y)
$pdf->Cell(50, 10, 'NACIMIENTO', 0, 0, 'L'); // Agregar el texto
// DISMINUIR X HACIA LA IZQUIERDA AUMENTAR HACIA LA DERECHA


$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 10
$pdf->SetXY(170, 71.5); // Ajustar la posición horizontal (X) y vertical (Y)
$pdf->Cell(50, 10, 'COMPONENTES', 0, 0, 'L'); // Agregar el texto
// DISMINUIR X HACIA LA IZQUIERDA AUMENTAR HACIA LA DERECHA




$pdf->SetFont('helvetica', 'B', 8 ); // Fuente y tamaño del texto
$pdf->StartTransform(); // Inicia la transformación
$pdf->Rotate(90, 100, 90); // Rota el texto -90 grados hacia la izquierda en el punto (100, 90)
$pdf->SetXY(93, 126); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'DIA', 0, 0, 'L'); // Agrega el texto
$pdf->StopTransform(); // Finaliza la transformación




$pdf->SetFont('helvetica', 'B', 8 ); // Fuente y tamaño del texto
$pdf->StartTransform(); // Inicia la transformación
$pdf->Rotate(90, 100, 90); // Rota el texto -90 grados hacia la izquierda en el punto (100, 90)
$pdf->SetXY(93, 131); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'MES', 0, 0, 'L'); // Agrega el texto
$pdf->StopTransform(); // Finaliza la transformación



$pdf->SetFont('helvetica', 'B', 8 ); // Fuente y tamaño del texto
$pdf->StartTransform(); // Inicia la transformación
$pdf->Rotate(90, 100, 90); // Rota el texto -90 grados hacia la izquierda en el punto (100, 90)
$pdf->SetXY(93, 137.7); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'AÑO', 0, 0, 'L'); // Agrega el texto
$pdf->StopTransform(); // Finaliza la transformación



$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 10
$pdf->SetXY(163, 86); // Ajustar la posición horizontal (X) y vertical (Y)
$pdf->Cell(50, 10, 'BÀSICO', 0, 0, 'L'); // Agregar el texto
// DISMINUIR X HACIA LA IZQUIERDA AUMENTAR HACIA LA DERECHA



$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 10
$pdf->SetXY(157.2, 98.5); // Ajustar la posición horizontal (X) y vertical (Y)
$pdf->Cell(50, 10, 'LC', 0, 0, 'L'); // Agregar el texto
// DISMINUIR X HACIA LA IZQUIERDA AUMENTAR HACIA LA DERECHA




$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 10
$pdf->SetXY(163.4, 98.5); // Ajustar la posición horizontal (X) y vertical (Y)
$pdf->Cell(50, 10, 'MA', 0, 0, 'L'); // Agregar el texto
// DISMINUIR X HACIA LA IZQUIERDA AUMENTAR HACIA LA DERECHA




$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 10
$pdf->SetXY(169.7, 98.5); // Ajustar la posición horizontal (X) y vertical (Y)
$pdf->Cell(50, 10, 'MT', 0, 0, 'L'); // Agregar el texto
// DISMINUIR X HACIA LA IZQUIERDA AUMENTAR HACIA LA DERECHA




$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 10
$pdf->SetXY(176, 98.5); // Ajustar la posición horizontal (X) y vertical (Y)
$pdf->Cell(50, 10, 'CN', 0, 0, 'L'); // Agregar el texto
// DISMINUIR X HACIA LA IZQUIERDA AUMENTAR HACIA LA DERECHA



$pdf->SetFont('helvetica', 'B', 8 ); // Fuente y tamaño del texto
$pdf->StartTransform(); // Inicia la transformación
$pdf->Rotate(90, 100, 90); // Rota el texto -90 grados hacia la izquierda en el punto (100, 90)
$pdf->SetXY(84.7, 170.5); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'PARTICIPACIÒN', 0, 0, 'L'); // Agrega el texto
$pdf->StopTransform(); // Finaliza la transformación


$pdf->SetFont('helvetica', 'B', 8 ); // Fuente y tamaño del texto
$pdf->StartTransform(); // Inicia la transformación
$pdf->Rotate(90, 100, 90); // Rota el texto -90 grados hacia la izquierda en el punto (100, 90)
$pdf->SetXY(84.7, 174); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'E INT. COM', 0, 0, 'L'); // Agrega el texto
$pdf->StopTransform(); // Finaliza la transformación



$pdf->SetFont('helvetica', 'B', 8 ); // Fuente y tamaño del texto
$pdf->StartTransform(); // Inicia la transformación
$pdf->Rotate(90, 100, 90); // Rota el texto -90 grados hacia la izquierda en el punto (100, 90)
$pdf->SetXY(90, 181); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'IDIOMAS', 0, 0, 'L'); // Agrega el texto
$pdf->StopTransform(); // Finaliza la transformación




$pdf->SetFont('helvetica', 'B', 8 ); // Fuente y tamaño del texto
$pdf->StartTransform(); // Inicia la transformación
$pdf->Rotate(90, 100, 90); // Rota el texto -90 grados hacia la izquierda en el punto (100, 90)
$pdf->SetXY(87, 188); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'FORMACION', 0, 0, 'L'); // Agrega el texto
$pdf->StopTransform(); // Finaliza la transformación




$pdf->SetFont('helvetica', 'B', 8 ); // Fuente y tamaño del texto
$pdf->StartTransform(); // Inicia la transformación
$pdf->Rotate(90, 100, 90); // Rota el texto -90 grados hacia la izquierda en el punto (100, 90)
$pdf->SetXY(87, 191.7); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'LABORAL', 0, 0, 'L'); // Agrega el texto
$pdf->StopTransform(); // Finaliza la transformación



$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 10
$pdf->SetXY(157, 64.4); // Ajustar la posición horizontal (X) y vertical (Y)
$pdf->Cell(50, 10, 'IV. Resumen del Rendimiento', 0, 0, 'L'); // Agregar el texto
// DISMINUIR X HACIA LA IZQUIERDA AUMENTAR HACIA LA DERECHA




//resumen curricular cuadro
// Encabezado de la tabla
// Tabla HTML


$pdf->SetXY(7.5, 71.9); // Posicionar esta tabla hacia la izquierda
// Encabezado de la tabla
$tbl = '
<table border="1" cellpadding="0" cellspacing="0" style="width: 100%; border-collapse: collapse; font-size: 8px;">





      <tr>
        <th rowspan="3" width="17" style="height: px;"></th>
        <th rowspan="3" width="60" style="height: px;"> </th>
        <th rowspan="3" width="100" style="height: px;"> </th>
        <th rowspan="3" width="100" style="height: px;"> </th>
        <th rowspan="3" width="60" style="height: px;"></th>
        <th rowspan="3" width="17" style="height: px;"> </th>
        <th rowspan="3" width="17" style="height: px;"> </th>
        <th colspan="3" width="53" style="height: 25px;"></th>
  
        <th colspan="6" width="152" style="height: 20px;"> </th>
    </tr>
    <tr>
        <th rowspan="2" width="15" ></th>
        <th rowspan="2" width="15" ></th>
        <th rowspan="2" width="23" ></th> 
        <th colspan="4" width="72" style="height: 60px;"></th>
        <th rowspan="2" width="30" style="height: 20px;"></th>
        <th rowspan="2" width="20" style="height: 20px;"></th>
        <th rowspan="2" width="30" style="height: 20px;"> </th>
    </tr>
    <tr style="height: 10px;">
        <th width="18"></th>
        <th width="18"></th>
        <th width="18"></th>
        <th width="18"></th>
    </tr>



';



if (!empty($rows)) {
// Filas de datos (generar 30 filas dinámicamente)

// Agregar las filas a la tabla


// Agregar las filas a la tabla con datos reales o vacíos
foreach ($rows as $row) {
    $tbl .= '<tr>';
    foreach ($row as $cell) {
        $tbl .= '<td align="center">' . $cell . '</td>';
    }
    $tbl .= '</tr>';
}

$tbl .= '</table>';
// Renderizar la tabla en el PDF
$pdf->writeHTML($tbl, true, false, false, false, '');

} else {
    echo "No hay datos disponibles para generar el PDF.";
}



// Colspan encima de la  tabla
$pdf->SetXY(156.1, 67.3); // Ajustar la posición del colspan
$colspanTable = '
<table border="1" cellpadding="5" style=" font-size: 1px; width: 28.5%;">
    <tr>
       <td colspan="8" style="text-align: center; font-size: 1px;">
            <span style="font-size: 8px; font-weight: bold; "></span>
        </td>
    </tr>
</table>';
$pdf->writeHTMLCell(190, '', '', '', $colspanTable, 0, 1, false, true, '');



//TEXTOS DE LA TABLA



$pdf->SetFont('helvetica', 'B', 10); // Fuente normal y tamaño 10
$pdf->SetXY(30, 214.5); // Ajustar la posición horizontal (X) y vertical (Y)
$pdf->Cell(50, 10, 'Totales', 0, 0, 'L'); // Agregar el texto
// DISMINUIR X HACIA LA IZQUIERDA AUMENTAR HACIA LA DERECHA




//tabla totales
$pdf->SetXY(69.8, 211.3); // Posicionar esta tabla hacia la izquierda

$html = '
<table border="1" cellpadding="1" cellspacing="0" style="font-size:8px; width: 166.5%; border-collapse: collapse; text-align: center;">
    <tr>
        <td style="width: 40%;">Inscritos</td>
        <td style="width: 2.9%;">' . $mostrarTotales['inscritos'][0] . '</td>
        <td style="width: 2.9%;">' . $mostrarTotales['inscritos'][1] . '</td>
        <td style="width: 2.9%;">' . $mostrarTotales['inscritos'][2] . '</td>
        <td style="width: 2.92%;">' . $mostrarTotales['inscritos'][3] . '</td>
        <td style="width: 4.87%;">' . $mostrarTotales['inscritos'][4] . '</td>
        <td style="width: 3.2%;">' . $mostrarTotales['inscritos'][5] . '</td>
        <td style="width: 4.86%;">' . $mostrarTotales['inscritos'][6] . '</td>
    </tr>
    <tr>
        <td style="width: 40%;">Inasistentes</td>
        <td style="width: 2.9%;">' . $mostrarTotales['inasistentes'][0] . '</td>
        <td style="width: 2.9%;">' . $mostrarTotales['inasistentes'][1] . '</td>
        <td style="width: 2.9%;">' . $mostrarTotales['inasistentes'][2] . '</td>
        <td style="width: 2.9%;">' . $mostrarTotales['inasistentes'][3] . '</td>
        <td style="width: 4.9%;">' . $mostrarTotales['inasistentes'][4] . '</td>
        <td style="width: 3.2%;">' . $mostrarTotales['inasistentes'][5] . '</td>
        <td style="width: 4.86%;">' . $mostrarTotales['inasistentes'][6] . '</td>
    </tr>
    <tr>
        <td style="width: 40%;">Aprobados</td>
        <td style="width: 2.9%;">' . $mostrarTotales['aprobados'][0] . '</td>
        <td style="width:2.9%;">' . $mostrarTotales['aprobados'][1] . '</td>
        <td style="width: 2.9%;">' . $mostrarTotales['aprobados'][2] . '</td>
        <td style="width: 2.9%;">' . $mostrarTotales['aprobados'][3] . '</td>
        <td style="width: 4.9%;">' . $mostrarTotales['aprobados'][4] . '</td>
        <td style="width: 3.2%;">' . $mostrarTotales['aprobados'][5] . '</td>
        <td style="width: 4.86%;">' . $mostrarTotales['aprobados'][6] . '</td>
    </tr>
    <tr>
        <td style="width: 40%;">No Aprobados</td>
        <td style="width: 2.9%;">' . $mostrarTotales['no_aprobados'][0] . '</td>
        <td style="width: 2.9%;">' . $mostrarTotales['no_aprobados'][1] . '</td>
        <td style="width: 2.9%;">' . $mostrarTotales['no_aprobados'][2] . '</td>
        <td style="width: 2.9%;">' . $mostrarTotales['no_aprobados'][3] . '</td>
        <td style="width: 4.9%;">' . $mostrarTotales['no_aprobados'][4] . '</td>
        <td style="width: 3.2%;">' . $mostrarTotales['no_aprobados'][5] . '</td>
        <td style="width: 4.86%;">' . $mostrarTotales['no_aprobados'][6] . '</td>
    </tr>
</table>';


$pdf->writeHTML($html, true, false, true, false, '');



//texto encima


// Añadir el texto "V. Profesores por Componentes y Àreas:" encima de la línea
$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 8
$pdf->SetXY(7, 225.2); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, 'V. Profesores por Componentes y Àreas:', 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha







// Colspan del lado izquierdo de la  tabla
$pdf->SetXY(6.5, 211.2); // Ajustar la posición del colspan
$colspanTable = '
<table border="1" cellpadding="5" style=" font-size: 1px; width: 33.2%;  ">
    <tr>
       <td colspan="8" style="text-align: center; font-size: 1px; height: 48.3px;">
            <span style="font-size: 8px; font-weight: bold; "></span>
        </td>
    </tr>
</table>';
$pdf->writeHTMLCell(190, '', '', '', $colspanTable, 0, 1, false, true, '');



//texto 


// Añadir el texto "COMPONENTES Y/O AREAS" encima de la línea
$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 8
$pdf->SetXY(16, 233); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, 'COMPONENTES Y/O AREAS', 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha

$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 8
$pdf->SetXY(72, 231.3); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, 'Apellidos y Nombres del', 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha

$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 8
$pdf->SetXY(83, 234.7); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, 'Profesor', 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha


$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 8
$pdf->SetXY(125, 231.3); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, 'Cèdula de', 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha

$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 8
$pdf->SetXY(125.5, 234.7); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, 'Identidad', 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha


$pdf->SetFont('helvetica', 'B', 7); // Fuente normal y tamaño 8
$pdf->SetXY(7.5, 239.8); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, $materia[0], 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha



$pdf->SetFont('helvetica', 'B', 7); // Fuente normal y tamaño 8
$pdf->SetXY(7.5, 243.2); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, $materia[1], 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha


$pdf->SetFont('helvetica', 'B', 7); // Fuente normal y tamaño 8
$pdf->SetXY(7.5, 246.5); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, $materia[2], 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha


$pdf->SetFont('helvetica', 'B', 7); // Fuente normal y tamaño 8
$pdf->SetXY(7.5, 249.9); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, $materia[3], 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha


$materia[4] = 'COMPONENTE DE PARTICIPACIÓN E INTEGRACIÓN COMUNITARIA';

// Reemplazos para abreviar
$materia[4] = str_replace(
    ['PARTICIPACIÓN', 'INTEGRACIÓN', 'COMUNITARIA'],
    ['PART.', 'INTEG.', ''],
    $materia[4]
);

// Eliminar dobles espacios si quedaron
$materia[4] = preg_replace('/\s+/', ' ', trim($materia[4]));
$pdf->SetXY(7.5, 253.2);
$pdf->Cell(60, 10, $materia[4], 0, 0, 'L');



$pdf->SetFont('helvetica', 'B', 7); // Fuente normal y tamaño 8

$materia[5] = 'COMPONENTE DE IDIOMAS';

// Establecer posición (ajústala si es necesario)
$pdf->SetXY(7.5, 256.5); // X e Y según tu diseño

// Mostrar el valor en el PDF
$pdf->Cell(60, 10, $materia[5], 0, 0, 'L');


$pdf->SetFont('helvetica', 'B', 7); // Fuente normal y tamaño 8
$materia[6] = 'COMPONENTE DE FORMACIÒN LABORAL';

// Establecer posición (ajústala si es necesario)
$pdf->SetXY(7.5, 259.9); // X e Y según tu diseño

// Mostrar el valor en el PDF
$pdf->Cell(60, 10, $materia[6], 0, 0, 'L');


$pdf->SetFont('helvetica', '', 7); // Fuente normal y tamaño 8
//texto profesores
$pdf->SetXY(65.5, 239.8);
$pdf->Cell(60, 10, $profesor_nombre[0], 0, 0, 'L');


$pdf->SetFont('helvetica', '', 7); // Fuente normal y tamaño 8
$pdf->SetXY(114, 239.8);
$pdf->Cell(40, 10, $profesor_cedula[0], 0, 0, 'L');


$pdf->SetFont('helvetica', '', 7); // Fuente normal y tamaño 8
//texto profesores
$pdf->SetXY(65.5, 243.2);
$pdf->Cell(60, 10, $profesor_nombre[1], 0, 0, 'L');

$pdf->SetFont('helvetica', '', 7); // Fuente normal y tamaño 8
$pdf->SetXY(114, 243.2);
$pdf->Cell(40, 10, $profesor_cedula[1], 0, 0, 'L');


$pdf->SetFont('helvetica', '', 7); // Fuente normal y tamaño 8
//texto profesores
$pdf->SetXY(65.5, 246.5);
$pdf->Cell(60, 10, $profesor_nombre[2], 0, 0, 'L');

$pdf->SetFont('helvetica', '', 7); // Fuente normal y tamaño 8
$pdf->SetXY(114, 246.5);
$pdf->Cell(40, 10, $profesor_cedula[2], 0, 0, 'L');


$pdf->SetFont('helvetica', '', 7); // Fuente normal y tamaño 8
//texto profesores
$pdf->SetXY(65.5, 249.9);
$pdf->Cell(60, 10, $profesor_nombre[3], 0, 0, 'L');

$pdf->SetFont('helvetica', '', 7); // Fuente normal y tamaño 8
$pdf->SetXY(114, 249.9);
$pdf->Cell(40, 10, $profesor_cedula[3], 0, 0, 'L');


$pdf->SetFont('helvetica', '', 7); // Fuente normal y tamaño 8
//texto profesores
$pdf->SetXY(65.5, 253.2);
$pdf->Cell(60, 10, $profesor_nombre[4], 0, 0, 'L');


$pdf->SetFont('helvetica', '', 7); // Fuente normal y tamaño 8
$pdf->SetXY(114, 253.2);
$pdf->Cell(40, 10, $profesor_cedula[4], 0, 0, 'L');


$pdf->SetFont('helvetica', '', 7); // Fuente normal y tamaño 8
//texto profesores
$pdf->SetXY(65.5, 256.6);
$pdf->Cell(60, 10, $profesor_nombre[5], 0, 0, 'L');


$pdf->SetFont('helvetica', '', 7); // Fuente normal y tamaño 8
$pdf->SetXY(114, 256.6);
$pdf->Cell(40, 10, $profesor_cedula[5], 0, 0, 'L');


$pdf->SetFont('helvetica', '', 7); // Fuente normal y tamaño 8
//texto profesores
$pdf->SetXY(65.5, 260);
$pdf->Cell(60, 10, $profesor_nombre[6], 0, 0, 'L');


$pdf->SetFont('helvetica', '', 7); // Fuente normal y tamaño 8
$pdf->SetXY(114, 260);
$pdf->Cell(40, 10, $profesor_cedula[6], 0, 0, 'L');

//tabla profesores

$pdf->SetXY(7.5, 232.5); // Posicionar esta tabla hacia la izquierda

$html = '
<table border="1" cellpadding="1" cellspacing="0" style="font-size:6px; width: 100.1%; border-collapse: collapse; text-align: center;">
    <tr>
        <td style="width: 30%; height: 30px; "></td>
        <td style="width: 25%; "></td>
        <td style="width: 20%; "></td>
    </tr>
    <tr>
        <td style="width: 30%;"></td>
        <td style="width: 25%;"></td>
        <td style="width: 20%;">'.'</td>
        
    </tr>
    <tr>
        <td style="width: 30%;"></td>
        <td style="width: 25%;">'.'</td>
        <td style="width: 20%;">'.'</td>
    </tr>
    <tr>
        <td style="width: 30%;"></td>
        <td style="width: 25%;"></td>
        <td style="width: 20%;"></td>
    </tr>
    <tr>
        <td style="width: 30%;"></td>
        <td style="width: 25%;"></td>
        <td style="width: 20%;"></td>
    </tr>
    <tr>
        <td style="width: 30%;"></td>
        <td style="width: 25%;"></td>
        <td style="width: 20%;"></td>
    </tr>
    <tr>
        <td style="width: 30%;"></td>
        <td style="width: 25%;"></td>
        <td style="width: 20%;"></td>
    </tr>

 <tr>
        <td style="width: 30%;"></td>
        <td style="width: 25%;"></td>
        <td style="width: 20%;"></td>
    </tr>

   

</table>
';

$pdf->writeHTML($html, true, false, true, false, '');


//TEXTO DERECHO



$pdf->SetFont('helvetica', 'B', 7); // Fuente normal y tamaño 8
$pdf->SetXY(165, 224.4); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, 'VI. Identificaciòn del Curso', 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha



$pdf->SetFont('helvetica', 'B', 7); // Fuente normal y tamaño 8
$pdf->SetXY(169, 227.1); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, 'PLAN DE ESTUDIO:', 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha




$pdf->SetFont('helvetica', 'B', 5); // Fuente normal y tamaño 8
$pdf->SetXY(151.9, 229.6); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, $plan['plan_estudio'], 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha


$pdf->SetFont('helvetica', 'B', 7); // Fuente normal y tamaño 8
$pdf->SetXY(175, 232); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, 'CODIGO:', 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha

 

$pdf->SetFont('helvetica', 'B', 7); // Fuente normal y tamaño 8
$pdf->SetXY(177, 234.6); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, $plan['codigo_estudio'], 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha




$pdf->SetFont('helvetica', 'B', 7); // Fuente normal y tamaño 8
$pdf->SetXY(164.7, 237.3); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, 'ESTRATEGIA DE ESTUDIO:', 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha




$pdf->SetFont('helvetica', 'B', 7); // Fuente normal y tamaño 8
$pdf->SetXY(164.7, 239.8); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, $plan['estrategia_estudio'], 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha


$pdf->SetFont('helvetica', 'B', 7); // Fuente normal y tamaño 8
$pdf->SetXY(175, 242.5); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, 'PERIODO:', 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha




$pdf->SetFont('helvetica', 'B', 7); // Fuente normal y tamaño 8
$pdf->SetXY(177, 244.7); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, $numeroConvertido, 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha



$pdf->SetFont('helvetica', 'B', 7); // Fuente normal y tamaño 8
$pdf->SetXY(175, 247.5); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, 'SECCION:', 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha




$pdf->SetFont('helvetica', 'B', 7); // Fuente normal y tamaño 8
$pdf->SetXY(179.6, 249.93); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, $seccionSeleccionada['nombre_seccion'], 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha





$pdf->SetFont('helvetica', 'B', 6); // Fuente normal y tamaño 8
$pdf->SetXY(162, 252.6); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, 'N° DE ESTUDIANTES EN ESTA PÀGINA:', 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha




$pdf->SetFont('helvetica', 'B', 7); // Fuente normal y tamaño 8
$pdf->SetXY(179.6, 255.2); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10,  $total_estudiantes, 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha




$pdf->SetFont('helvetica', 'B', 6); // Fuente normal y tamaño 8
$pdf->SetXY(162, 257.8); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, 'N° DE ESTUDIANTES DE LA SECCIÒN:', 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha



$pdf->SetFont('helvetica', 'B', 7); // Fuente normal y tamaño 8
$pdf->SetXY(179.6, 260.3); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10,  $total_estudiantes, 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha




//tabla derecha identificacion de curso

$pdf->SetXY(152.7, 228.2); // Posicionar esta tabla hacia la izquierda

$html = '

<table border="1" cellpadding="1" cellspacing="0" style="font-size:8px; width: 110%; height: 5px; border-collapse: collapse; text-align: center; line-height: 0.7;">


  <tr><td style="text-align: left; width:109.4%; height: 6px; font-size: 7.5px; "> </td></tr>
  <tr><td style="text-align: left; width:109.4%; height: 6px;font-size: 7.5px; "></td></tr>
  <tr><td style="text-align: center; width:109.4%; height: 6px; font-size: 7.5px; "></td></tr>
  <tr><td style="text-align: left; width:109.4%; height: 6px; font-size: 7.5px; "> </td></tr>
  <tr><td style="text-align: center; width:109.4%; height: 6px; font-size: 7.5px; "></td></tr>
  <tr><td style="text-align: left; width:109.4%; height: 6px; font-size: 7.5px; "> </td></tr>
  <tr><td style="text-align: left; width:109.4%; height: 6px; font-size: 7.5px; "></td></tr>
  <tr><td style="text-align: left; width:109.4%; height: 6px; font-size: 7.5px; ">  </td></tr>
  <tr><td style="text-align: left; width:109.4%; height: 6px; font-size: 7.5px; ">  </td></tr>
  <tr><td style="text-align: left; width:109.4%; height: 6px; font-size: 7.5px; ">  </td></tr>
  <tr><td style="text-align: left; width:109.4%; height: 6px; font-size: 7.5px; ">  </td></tr>
  <tr><td style="text-align: left; width:109.4%; height: 6px; font-size: 7.5px; ">  </td></tr>
  <tr><td style="text-align: left; width:109.4%; height: 6px; font-size: 7.5px; ">  </td></tr>
  <tr><td style="text-align: left; width:109.4%; height: 6px; font-size: 7.5px; ">  </td></tr>
  <tr><td style="text-align: left; width:109.4%; height: 6px; font-size: 7.5px; ">  </td></tr>
</table>



';

// Escribe el HTML
$pdf->writeHTML($html, true, false, true, false, '');







// Añadir el texto "VII. Observaciones:" encima de la línea
$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 8
$pdf->SetXY(7, 263.5); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, 'VII. Observaciones:', 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha

// Dibujar una línea al lado derecho del texto
$pdf->SetLineWidth(0.3); // Grosor de la línea
$pdf->Line(35, 270, 210.8, 270); // Coordenadas de inicio y fin de la línea (X1, Y1, X2, Y2)
// Y1 y y2 30 SE Y 30 SE AUMENTA PARA BAJAR Y SE DISMINUYE PARA SUBIR)
// x1 se disminuye para mover hacia la izquierda y x2  y disminuye para mover hacia la izquierda y se aumenta para ir hacia hacia la derecha


















//<tr>
//<td colspan="5" style="font-weight: bold; height: 60px;">COMPONENTE DE IDIOMA</td>
//</tr>




//texto

$pdf->SetFont('helvetica', 'B', 8 ); // Fuente y tamaño del texto
$pdf->SetXY(18, 268.7); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'VIII. Fecha de Remisiòn:', 0, 0, 'L'); // Agrega el texto


$pdf->SetFont('helvetica', 'B', 8 ); // Fuente y tamaño del texto
$pdf->SetXY(26, 272.7); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'Director(a)', 0, 0, 'L'); // Agrega el texto


$pdf->SetFont('helvetica', 'B', 8 ); // Fuente y tamaño del texto
$pdf->SetXY(8, 276.7); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'Apellidos y Nombres', 0, 0, 'L'); // Agrega el texto


$pdf->SetFont('helvetica', 'B', 8 ); // Fuente y tamaño del texto
$pdf->SetXY(8, 280.7); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'DR. JHONNY VILORIA', 0, 0, 'L'); // Agrega el texto



$pdf->SetFont('helvetica', 'B', 8 ); // Fuente y tamaño del texto
$pdf->SetXY(8, 284.8); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'Nùmero de C.I:', 0, 0, 'L'); // Agrega el texto


$pdf->SetFont('helvetica', 'B', 8 ); // Fuente y tamaño del texto
$pdf->SetXY(26, 288.8); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'V-14206691', 0, 0, 'L'); // Agrega el texto

$pdf->SetFont('helvetica', 'B', 8 ); // Fuente y tamaño del texto
$pdf->SetXY(30, 293); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'Firma:', 0, 0, 'L'); // Agrega el texto


$pdf->SetFont('helvetica', 'B', 6 ); // Fuente y tamaño del texto
$pdf->SetXY(8, 295); // Ajusta la posición del texto
$pdf->Cell(60, 10, '  ', 0, 0, 'L'); // Agrega el texto










$pdf->SetXY(7.5, 272); // Posicionar esta tabla hacia la izquierda
// Añadir un espaciado para bajar el contenido

//tablafinal1

$html = '

<table border="1" cellpadding="1" cellspacing="0" style="font-size:8px; width: 50%; border-collapse: collapse; text-align: center; line-height: 0.7;">
  
  <tr><td style="text-align: left; width:57.6%; height: 11px; font-size: 8px; "></td></tr>
  <tr><td style="text-align: left; width:57.6%; height: 11px;font-size: 8px; "></td></tr>
  <tr><td style="text-align: center; width:57.6%; height: 12px; font-size: 8px; "> </td></tr>
  <tr><td style="text-align: left; width:57.6%; height: 11px; font-size: 8px; "></td></tr>
  <tr><td style="text-align: center; width:57.6%; height: 12px; font-size: 8px; "> </td></tr>
  <tr><td style="text-align: left; width:57.6%; height: 11px; font-size: 8px; "> </td></tr>
  <tr><td style="text-align: left; width:57.6%; height: 12px; font-size: 8px; "></td></tr>
  <tr><td style="text-align: left; width:57.6%; height: 13.2px; font-size: 6px; "> </td></tr>
</table>



';

// Escribe el HTML
$pdf->writeHTML($html, true, false, true, false, '');










// Colspan sello 
$pdf->SetXY(62.3, 272.1); // Ajustar la posición del colspan
$colspanTable = '
<table border="1" cellpadding="5" style=" font-size: 3px; width: 23.6%;">
    <tr>
       <td colspan="8" style="text-align: center; height: 93.1px; font-size: 1px;">
            <span style="font-size: 8px; font-weight: bold; "></span>
        </td>
    </tr>
</table>';
$pdf->writeHTMLCell(190, '', '', '', $colspanTable, 0, 1, false, true, '');








//texto

$pdf->SetFont('helvetica', 'B', 8 ); // Fuente y tamaño del texto
$pdf->SetXY(121, 268.7); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'IX. Fecha de Recepciòn:', 0, 0, 'L'); // Agrega el texto


$pdf->SetFont('helvetica', 'B', 8 ); // Fuente y tamaño del texto
$pdf->SetXY(122.5, 272.7); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'Funcionario Receptor', 0, 0, 'L'); // Agrega el texto


$pdf->SetFont('helvetica', 'B', 8 ); // Fuente y tamaño del texto
$pdf->SetXY(111.1, 276.7); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'Apellidos y Nombres', 0, 0, 'L'); // Agrega el texto




$pdf->SetFont('helvetica', 'B', 8 ); // Fuente y tamaño del texto
$pdf->SetXY(111.1, 284.8); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'Nùmero de C.I:', 0, 0, 'L'); // Agrega el texto



$pdf->SetFont('helvetica', 'B', 8 ); // Fuente y tamaño del texto
$pdf->SetXY(132, 293); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'Firma:', 0, 0, 'L'); // Agrega el texto




$pdf->SetXY(110.6, 272.2); // Posicionar esta tabla hacia la izquierda
// Añadir un espaciado para bajar el contenido

//tablafinal1

$html = '

<table border="1" cellpadding="1" cellspacing="0" style="font-size:8px; width: 107.5%; border-collapse: collapse; text-align: center; line-height: 0.7;">


  <tr><td style="text-align: left; width:57%; height: 11px; font-size: 8px; "> </td></tr>
  <tr><td style="text-align: left; width:57%; height: 11px;font-size: 8px; "></td></tr>
  <tr><td style="text-align: center; width:57%; height: 12px; font-size: 8px; "></td></tr>
  <tr><td style="text-align: left; width:57%; height: 11px; font-size: 8px; "> </td></tr>
  <tr><td style="text-align: center; width:57%; height: 12px; font-size: 8px; "></td></tr>
  <tr><td style="text-align: left; width:57%; height: 11px; font-size: 8px; "> </td></tr>
  <tr><td style="text-align: left; width:57%; height: 12px; font-size: 8px; "></td></tr>
  <tr><td style="text-align: left; width:57%; height: 13px; font-size: 6px; ">  </td></tr>
</table>



';

// Escribe el HTML
$pdf->writeHTML($html, true, false, true, false, '');




// Colspan sello 
$pdf->SetXY(165, 272.2); // Ajustar la posición del colspan
$colspanTable = '
<table border="1" cellpadding="5" style=" font-size: 3px; width: 23.6%;">
    <tr>
       <td colspan="8" style="text-align: center; height: 93.1x; font-size: 1px;">
            <span style="font-size: 8px; font-weight: bold; "></span>
        </td>
    </tr>
</table>';
$pdf->writeHTMLCell(190, '', '', '', $colspanTable, 0, 1, false, true, '');


//sellos
$pdf->SetFont('helvetica', 'B', 8 ); // Fuente y tamaño del texto
$pdf->SetXY(71.7, 283); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'SELLO DE PLANTEL', 0, 0, 'L'); // Agrega el texto


$pdf->SetFont('helvetica', 'B', 8 ); // Fuente y tamaño del texto
$pdf->SetXY(174.5, 283); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'SELLO DE LA ZONA', 0, 0, 'L'); // Agrega el texto


// Salida del PDF
ob_end_clean(); // Limpia el búfer de salida antes de enviar el PDF
$pdf->Output('resumen_curricular.pdf', 'I');

exit;
echo '<script>window.onload = function() { window.print(); }</script>';
?>