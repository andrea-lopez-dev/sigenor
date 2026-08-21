<?php
require_once '../../vendor/autoload.php';



$servername = "localhost"; // Cambia esto si tu servidor es diferente
$username = "root";
$password = "";
$database = "sigenor";

// Crear conexión
$conexion = new mysqli($servername, $username, $password, $database);

// Verificar conexión
// Iniciar buffer de salida para evitar problemas con TCPDF
ob_start();

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

$id_estudiante = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_estudiante > 0) {
    // Consulta para obtener los datos del estudiante y los planteles anexados
    $sql = "SELECT 
                estudiantes.id_estudiante, estudiantes.cedula, estudiantes.nombres, estudiantes.apellidos, estudiantes.edad, 
                estudiantes.sexo, estudiantes.fecha_nacimiento, estudiantes.direccion, estudiantes.tlf_estudiante, 
                estudiantes.correo, estudiantes.foto, estudiantes.lugar_nacimiento, estudiantes.entidad_federal, estudiantes.fecha, estudiantes.estado, 
                planteles.id_plantel, planteles.codigo, planteles.nombre AS nombre_plantel, planteles.direccion_plantel, planteles.localidad,
                planteles.telefono, planteles.municipio, planteles.entidad_federal AS planteles_entidad, planteles.zona_educativa, 
                planteles.director, planteles.cedula_director, planteles.fecha, planteles.numero_plantel, 
                periodos.id_periodo, periodos.numero_periodo, periodos.nombre_periodo  
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
    $planteles_id = [];
    $nombre_periodo = [];
    $numero_periodo = [];
    $periodos_id = [];

    if ($resultado->num_rows > 0) {
        while ($fila = $resultado->fetch_assoc()) {
            $estudiante = $fila;

            // Evitar duplicados usando `id_plantel` como clave única
            if (!empty($fila['nombre_plantel']) && !isset($planteles_id[$fila['id_plantel']])) {
                $planteles_id[$fila['id_plantel']] = true;
                $planteles_unicos[] = [
                    'nombre' => $fila['nombre_plantel'],
                    'localidad' => $fila['localidad'],
                    'entidad_federal' => $fila['planteles_entidad']
                ];
            }

            if (!empty($fila['nombre_periodo']) && !isset($periodos_id[$fila['numero_periodo']])) {
                $periodos_id[$fila['id_periodo']] = true;
                $numero_periodo[] = [
                    'numero_periodo' => $fila['numero_periodo']
                ];
                $periodos_id[$fila['nombre_periodo']] = true;
                $nombre_periodo[] = [
                    'nombre_periodo' => $fila['nombre_periodo']
                ];
            }
        }
    } else {
        die("No se encontraron datos para este estudiante.");
    }

    $stmt->close();
$sql_calificaciones = "SELECT 
    calificaciones.id_calificacion, 
    calificaciones.id_estudiante, 
    asignaturas.id_curso, 
    asignaturas.nombre_curso,
    planteles.id_plantel,
    planteles.numero_plantel, 
    periodos.id_periodo, 
    periodos.nombre_periodo,
    periodos.numero_periodo,
    calificaciones.calificacion AS calificacion, 
    calificaciones.calificacion_letras AS calificacion_letras, 
    calificaciones.`T-E` AS te, 
    calificaciones.mes AS mes, 
    calificaciones.año AS anio
FROM calificaciones
INNER JOIN estudiantes ON calificaciones.id_estudiante = estudiantes.id_estudiante
INNER JOIN asignaturas ON calificaciones.id_curso = asignaturas.id_curso
INNER JOIN planteles ON calificaciones.id_plantel = planteles.id_plantel
INNER JOIN periodos ON calificaciones.id_periodo = periodos.id_periodo
WHERE estudiantes.id_estudiante = ?
ORDER BY periodos.numero_periodo ASC, asignaturas.id_curso ASC";

$stmt = $conexion->prepare($sql_calificaciones);
$stmt->bind_param("i", $id_estudiante);
$stmt->execute();
$resultado = $stmt->get_result();

$nombre_curso = [];

if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $nombre_curso[] = [
            'id_calificacion' => $fila['id_calificacion'],
            'id_curso' => $fila['id_curso'],
            'id_periodo' => $fila['id_periodo'],
            'numero_plantel' => $fila['numero_plantel'],
            'nombre_periodo' => $fila['nombre_periodo'],
            'numero_periodo' => $fila['numero_periodo'],
            'nombre_curso' => $fila['nombre_curso'],
            'calificacion' => $fila['calificacion'],
            'calificacion_letras' => $fila['calificacion_letras'],
            'te' => $fila['te'],
            'mes' => $fila['mes'],
            'anio' => $fila['anio']
        ];
    }
}


$periodo1 = [];
$periodo2 = [];
$periodo3 = [];
$periodo4 = [];
$periodo5 = [];
$periodo6 = [];




foreach ($nombre_curso as $item) {
    switch ($item['numero_periodo']) {
        case 1:
            $periodo1[] = $item;
            break;
        case 2:
            $periodo2[] = $item;
            break;
        case 3:
            $periodo3[] = $item;
            break;
        case 4:
            $periodo4[] = $item;
            break;
        case 5:
            $periodo5[] = $item;
            break;
        case 6:
            $periodo6[] = $item;
            break;
    }
}

$index = 4; // índice que quieres mostrar, verifica que exista

// PERIODO 1
if (isset($periodo1[$index])) {
    $item = $periodo1[$index];
    
    $nomb = !empty($item['nombre_curso']) ? $item['nombre_curso'] : '***';
    $califica = !empty($item['calificacion_letras']) ? strtoupper(trim($item['calificacion_letras'])) : '***';
    $te = !empty($item['te']) ? $item['te'] : '***';
    $mesx = !empty($item['mes']) ? $item['mes'] : '***';
    $anio = !empty($item['anio']) ? $item['anio'] : '***';
    $numero_plantel = !empty($item['numero_plantel']) ? $item['numero_plantel'] : '***';
} else {
    $nomb = $califica = $te = $mesx = $anio = $numero_plantel = '***';
}

// PERIODO 2
if (isset($periodo2[$index])) {
    $item = $periodo2[$index];

    $nomb2 = !empty($item['nombre_curso']) ? $item['nombre_curso'] : '***';
    $califica2 = !empty($item['calificacion_letras']) ? strtoupper(trim($item['calificacion_letras'])) : '***';
    $te2 = !empty($item['te']) ? $item['te'] : '***';
    $mesx2 = !empty($item['mes']) ? $item['mes'] : '***';
    $anio2 = !empty($item['anio']) ? $item['anio'] : '***';
    $numero_plantel2 = !empty($item['numero_plantel']) ? $item['numero_plantel'] : '***';
} else {
    $nomb2 = $califica2 = $te2 = $mesx2 = $anio2 = $numero_plantel2 = '***';
}

// PERIODO 3
if (isset($periodo3[$index])) {
    $item = $periodo3[$index];

    $nomb3 = !empty($item['nombre_curso']) ? $item['nombre_curso'] : '***';
    $califica3 = !empty($item['calificacion_letras']) ? strtoupper(trim($item['calificacion_letras'])) : '***';
    $te3 = !empty($item['te']) ? $item['te'] : '***';
    $mesx3 = !empty($item['mes']) ? $item['mes'] : '***';
    $anio3 = !empty($item['anio']) ? $item['anio'] : '***';
    $numero_plantel3 = !empty($item['numero_plantel']) ? $item['numero_plantel'] : '***';
} else {
    $nomb3 = $califica3 = $te3 = $mesx3 = $anio3 = $numero_plantel3 = '***';
}

// PERIODO 4
if (isset($periodo4[$index])) {
    $item = $periodo4[$index];

    $nomb4 = !empty($item['nombre_curso']) ? $item['nombre_curso'] : '***';
    $califica4 = !empty($item['calificacion_letras']) ? strtoupper(trim($item['calificacion_letras'])) : '***';
    $te4 = !empty($item['te']) ? $item['te'] : '***';
    $mesx4 = !empty($item['mes']) ? $item['mes'] : '***';
    $anio4 = !empty($item['anio']) ? $item['anio'] : '***';
    $numero_plantel4 = !empty($item['numero_plantel']) ? $item['numero_plantel'] : '***';
} else {
    $nomb4 = $califica4 = $te4 = $mesx4 = $anio4 = $numero_plantel4 = '***';
}

// PERIODO 5
if (isset($periodo5[$index])) {
    $item = $periodo5[$index];

    $nomb5 = !empty($item['nombre_curso']) ? $item['nombre_curso'] : '***';
    $califica5 = !empty($item['calificacion_letras']) ? strtoupper(trim($item['calificacion_letras'])) : '***';
    $te5 = !empty($item['te']) ? $item['te'] : '***';
    $mesx5 = !empty($item['mes']) ? $item['mes'] : '***';
    $anio5 = !empty($item['anio']) ? $item['anio'] : '***';
    $numero_plantel5 = !empty($item['numero_plantel']) ? $item['numero_plantel'] : '***';
} else {
    $nomb5 = $califica5 = $te5 = $mesx5 = $anio5 = $numero_plantel5 = '***';
}

// PERIODO 6
if (isset($periodo6[$index])) {
    $item = $periodo6[$index];

    $nomb6 = !empty($item['nombre_curso']) ? $item['nombre_curso'] : '***';
    $califica6 = !empty($item['calificacion_letras']) ? strtoupper(trim($item['calificacion_letras'])) : '***';
    $te6 = !empty($item['te']) ? $item['te'] : '***';
    $mesx6 = !empty($item['mes']) ? $item['mes'] : '***';
    $anio6 = !empty($item['anio']) ? $item['anio'] : '***';
    $numero_plantel6 = !empty($item['numero_plantel']) ? $item['numero_plantel'] : '***';
} else {
    $nomb6 = $califica6 = $te6 = $mesx6 = $anio6 = $numero_plantel6 = '***';
}

// Array de todos los periodos para facilitar el manejo
$periodos = [$periodo1, $periodo2, $periodo3, $periodo4, $periodo5, $periodo6];

// Función que devuelve todas las materias en la posición $pos, ordenadas por número de periodo ascendente
function obtenerMateriasOrdenadasPorPeriodo($pos) {
    global $periodos;
    $materias = [];

    // Recorremos cada periodo
    foreach ($periodos as $periodo) {
        if (isset($periodo[$pos]) && !empty($periodo[$pos])) {
            $materias[] = $periodo[$pos];
        }
    }

    // Ordenar materias por numero_periodo ascendente
    usort($materias, function($a, $b) {
        return $b['numero_periodo'] <=> $a['numero_periodo'];
    });

    return $materias;
}

$posicion = 5; // posición que quieres consultar

$materiasOrdenadas = obtenerMateriasOrdenadasPorPeriodo($posicion);
foreach ($materiasOrdenadas as $materia) { } 


function obtenerMateriasOrdenadasPorPeriodos($pos) {
    global $periodos;
    $materias1 = [];

    // Recorremos cada periodo
    foreach ($periodos as $periodo) {
        if (isset($periodo[$pos]) && !empty($periodo[$pos])) {
            $materias1[] = $periodo[$pos];
        }
    }

    // Ordenar materias por numero_periodo ascendente
    usort($materias1, function($a, $b) {
        return $a['numero_periodo'] <=> $b['numero_periodo'];
    });

    return $materias1;
}

$posicion = 6; // posición que quieres consultar

$materiasOrdenadas1 = obtenerMateriasOrdenadasPorPeriodos($posicion);
foreach ($materiasOrdenadas1 as $materia2) { } 


function rellenarPeriodo(&$periodo, $cantidad = 8) {
    $relleno = [
        'nombre_periodo' => '',
        'numero_plantel' => '',
        'nombre_curso' => '',
        'calificacion' => '',
        'calificacion_letras' => '',
        'te' => '',
        'mes' => '',
        'anio' => ''
    ];

    while (count($periodo) < $cantidad) {
        $periodo[] = $relleno;
    }
}


rellenarPeriodo($periodo1);
rellenarPeriodo($periodo2);
rellenarPeriodo($periodo3);
rellenarPeriodo($periodo4);
rellenarPeriodo($periodo5);
rellenarPeriodo($periodo6);

// Asegurar que haya exactamente 6 cursos (llenar con ***** si hay menos)


if (empty($nombre_curso)) {
    die("No se encontraron calificaciones para este estudiante.");
}

$stmt->close();
$conexion->close();

// Ordenar por numero_periodo ASC y luego por id_curso ASC
usort($nombre_curso, function($a, $b) {
    return [$a['numero_periodo'], $a['id_curso']] <=> [$b['numero_periodo'], $b['id_curso']];
});


} else {
    die("ID de estudiante no válido.");
}
// Asegurar que haya exactamente 6 cursos (llenar con ***** si hay menos)
while (count($nombre_curso) < 8) {
    $nombre_curso[] = [
        'numero_plantel' => "*****",
            'nombre_periodo' => "*****",
            'numero_periodo' => "*****",
            'nombre_curso' => "*****",
            'calificacion' => "*****",
            'calificacion_letras' => "*****",
            'te' => "*****",
            'mes' => "*****",
            'anio' => "*****"
    ];
}



// Asegurar que haya exactamente 5 planteles (llenar con ***** si hay menos)
while (count($planteles_unicos) < 5) {
    $planteles_unicos[] = [
        'nombre' => "*****",
        'localidad' => "*****",
        'entidad_federal' => "*****"
    ];
}

// Asegurar que haya exactamente 6 periodos (llenar con ***** si hay menos)
while (count($nombre_periodo) < 6) {
    $nombre_periodo[] = [
        'nombre_periodo' => "*****"
    ];
}


// Limpiar buffer de salida antes de usar TCPDF
ob_end_clean();
// Crear un array de planteles únicos

// Si hay más de 5 planteles, limitar la lista
$planteles_unicos = array_slice($planteles_unicos, 0, 5);

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


// Desactiva la visualización directa de errores al usuario
ini_set('display_errors', 0);
error_reporting(E_ALL);

$isGeneratingPDF = defined('K_TCPDF_EXTERNAL_CONFIG'); // Detecta si TCPDF está en uso

set_error_handler(function($errno, $errstr, $errfile, $errline) use ($isGeneratingPDF) {
    handleFriendlyError("Error PHP: $errstr en $errfile línea $errline", $isGeneratingPDF);
    exit;
});

set_exception_handler(function($exception) use ($isGeneratingPDF) {
    handleFriendlyError("Excepción: " . $exception->getMessage(), $isGeneratingPDF);
    exit;
});

register_shutdown_function(function() use ($isGeneratingPDF) {
    $error = error_get_last();
    if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        handleFriendlyError("Error fatal: {$error['message']} en {$error['file']} línea {$error['line']}", $isGeneratingPDF);
        exit;
    }
});

function handleFriendlyError($debugMessage, $isPDF = false) {
    if ($isPDF) {
        // Si estás generando un PDF con TCPDF, detén la generación y muestra un mensaje dentro del PDF
        require_once('tcpdf_include.php');
        require_once '../../vendor/autoload.php';
        $pdf = new TCPDF();
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Write(0, "Ocurrió un error al generar el documento.\n\nPor favor, llene todos los campos necesarios o verifique los datos ingresados.");
        $pdf->Output('error.pdf', 'D');
    } else {
        // Error en entorno web (HTML)
        echo '<div class="alert alert-danger" role="alert">
                Por favor llene todos los campos necesarios o verifique que no haya campos vacios.
              </div>';
    }

    // Opcional: registrar error en archivo
    file_put_contents('logs/error_log.txt', date('[Y-m-d H:i:s] ') . $debugMessage . PHP_EOL, FILE_APPEND);
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
$pdf->SetTitle('Certificación de Calificaciones');
$pdf->SetSubject('Certificación de Calificaciones');
$pdf->SetKeywords('Certificación, Calificaciones, Venezuela');

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



// Añadir el texto "CERTIFICACIÓN DE CALIFICACIONES" en la parte superior derecha
$pdf->SetFont('helvetica', 'B', 11); // Fuente en negrita y tamaño 12
$pdf->SetXY(120, 8); // Ajustar la posición horizontal (X) más hacia la izquierda
$pdf->Cell(0, 10, 'CERTIFICACIÓN DE CALIFICACIONES', 0, 1, 'L'); // Agregar el texto

// Dibujar una línea más pegada al texto
$pdf->SetLineWidth(0.3); // Grosor de la línea
$pdf->Line(121, 15, 193, 15); // Ajustar la línea para que coincida con la nueva posición del texto

// Añadir el texto "Código del Formato: EMGMJAA" centrado debajo de "CERTIFICACIÓN DE CALIFICACIONES"
$pdf->SetFont('helvetica', 'B', 9); // Fuente normal y tamaño 10
$pdf->SetXY(117, 13); // Ajustar la posición vertical (Y) debajo de la línea
$pdf->Cell(0, 10, 'Código del Formato: EMGMJAA', 0, 1, 'C'); // Texto centrado

// Añadir el texto "I. Código del Plan de Estudio:" debajo de "Código del Formato: EMGMJAA"
$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 10
$pdf->SetXY(115, 18); // Ajustar la posición horizontal (X) y vertical (Y)
$pdf->Cell(50, 10, 'I. Código del Plan de Estudio:', 0, 0, 'L'); // Agregar el texto
// DISMINUIR X HACIA LA IZQUIERDA AUMENTAR HACIA LA DERECHA


// Añadir el texto "31058" encima de la línea
$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 8
$pdf->SetXY(177, 18); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(0, 10, '31058', 0, 0, 'L'); // Agregar el texto
// Disminuir Y y para bajar y aumentar para subir
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha

// Dibujar una línea al lado derecho del texto
$pdf->SetLineWidth(0.3); // Grosor de la línea
$pdf->Line(156, 25, 210, 25); // Coordenadas de inicio y fin de la línea (X1, Y1, X2, Y2)
// 30 SE Y 30 SE AUMENTA PARA BAJAR Y SE DISMINUYE PARA SUBIR)


// Añadir el texto "Lugar y Fecha de Expediciòn:"
$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 10
$pdf->SetXY(115, 23); // Ajustar la posición horizontal (X) y vertical (Y)
$pdf->Cell(50, 10, 'Lugar y Fecha de Expediciòn:', 0, 0, 'L'); // Agregar el texto
// DISMINUIR X HACIA LA IZQUIERDA AUMENTAR HACIA LA DERECHA
// Disminuir Y para subir y aumentar para bajar

// Añadir el texto "LUGAR Y FECHA DE EMISIÒN" encima de la línea
$pdf->SetFont('helvetica', '', 8); // Fuente normal y tamaño 8
$pdf->SetXY(159, 23); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, $lugar_fecha, 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha

// Dibujar una línea al lado derecho del texto
$pdf->SetLineWidth(0.3); // Grosor de la línea
$pdf->Line(156, 30, 210, 30); // Coordenadas de inicio y fin de la línea (X1, Y1, X2, Y2)
// 30 SE Y 30 SE AUMENTA PARA BAJAR Y SE DISMINUYE PARA SUBIR)

// Añadir el texto "II. Datos del Plantel o Zona Educativa que emite la certificaciòn:" debajo de "Código del Formato: EMGMJAA"
$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 10
$pdf->SetXY(7, 23); // Ajustar la posición horizontal (X) y vertical (Y)
$pdf->Cell(50, 10, 'II. Datos del Plantel o Zona Educativa que emite la certificaciòn:', 0, 0, 'L'); // Agregar el texto
// DISMINUIR X HACIA LA IZQUIERDA AUMENTAR HACIA LA DERECHA
// Disminuir Y para subir y aumentar para bajar


// Añadir el texto "Código:"
$pdf->SetFont('helvetica', 'B', 9); // Fuente normal y tamaño 10
$pdf->SetXY(7, 28); // Ajustar la posición horizontal (X) y vertical (Y)
$pdf->Cell(50, 10, 'Código:', 0, 0, 'L'); // Agregar el texto
// DISMINUIR X HACIA LA IZQUIERDA AUMENTAR HACIA LA DERECHA


// Añadir el texto "S2033N2313" encima de la línea
$pdf->SetFont('helvetica', 'B', 9); // Fuente normal y tamaño 8
$pdf->SetXY(20, 28); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, 'S2033N2313', 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha

// Dibujar una línea al lado derecho del texto
$pdf->SetLineWidth(0.3); // Grosor de la línea
$pdf->Line(20.6, 34.9, 40, 34.9); // Coordenadas de inicio y fin de la línea (X1, Y1, X2, Y2)
// Y1 y y2 30 SE Y 30 SE AUMENTA PARA BAJAR Y SE DISMINUYE PARA SUBIR)
// x1 se disminuye para mover hacia la izquierda y x2  y se aumenta para la izquierda y se disminuye hacia la derecha


// Añadir el texto "Nombre:"
$pdf->SetFont('helvetica', 'B', 9); // Fuente normal y tamaño 10
$pdf->SetXY(48, 28); // Ajustar la posición horizontal (X) y vertical (Y)
$pdf->Cell(50, 10, 'Nombre:', 0, 0, 'L'); // Agregar el texto
// DISMINUIR X HACIA LA IZQUIERDA AUMENTAR HACIA LA DERECHA


// Añadir el texto "UNIDAD EDUCATIVA NOCTURNA BR. RAFAEL RANGEL" encima de la línea
$pdf->SetFont('helvetica', 'B', 9); // Fuente normal y tamaño 8
$pdf->SetXY(62, 28); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, 'UNIDAD EDUCATIVA NOCTURNA BR. RAFAEL RANGEL', 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha

// Dibujar una línea al lado derecho del texto
$pdf->SetLineWidth(0.3); // Grosor de la línea
$pdf->Line(148.4, 34.9, 63, 34.9); // Coordenadas de inicio y fin de la línea (X1, Y1, X2, Y2)
// Y1 y y2 30 SE Y 30 SE AUMENTA PARA BAJAR Y SE DISMINUYE PARA SUBIR)
// x1 se disminuye para mover hacia la izquierda y x2  y disminuye para mover hacia la izquierda y se aumenta para ir hacia hacia la derecha

// Añadir el texto "Direcciòn:"
$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 10
$pdf->SetXY(7, 33); // Ajustar la posición horizontal (X) y vertical (Y)
$pdf->Cell(50, 10, 'Direcciòn:', 0, 0, 'L'); // Agregar el texto
// DISMINUIR X HACIA LA IZQUIERDA AUMENTAR HACIA LA DERECHA


// Añadir el texto "SECTOR PANAMERICANO CALLE 69B No. 90-36" encima de la línea
$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 8
$pdf->SetXY(50, 32.5); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, 'SECTOR PANAMERICANO CALLE 69B No. 90-36', 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha

// Dibujar una línea al lado derecho del texto
$pdf->SetLineWidth(0.3); // Grosor de la línea
$pdf->Line(143, 39.5, 22.5, 39.5); // Coordenadas de inicio y fin de la línea (X1, Y1, X2, Y2)
// Y1 y y2 30 SE Y 30 SE AUMENTA PARA BAJAR Y SE DISMINUYE PARA SUBIR)
// x1 se disminuye para mover hacia la izquierda y x2  y disminuye para mover hacia la izquierda y se aumenta para ir hacia hacia la derecha

// Añadir el texto "Telèfono:"
$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 10
$pdf->SetXY(143, 32.5); // Ajustar la posición horizontal (X) y vertical (Y)
$pdf->Cell(50, 10, 'Telèfono:', 0, 0, 'L'); // Agregar el texto
// DISMINUIR X HACIA LA IZQUIERDA AUMENTAR HACIA LA DERECHA


// Añadir el texto "0424-6787968" encima de la línea
$pdf->SetFont('helvetica', '', 8); // Fuente normal y tamaño 8
$pdf->SetXY(172, 32.5); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, '0424-6787968', 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha

// Dibujar una línea al lado derecho del texto
$pdf->SetLineWidth(0.3); // Grosor de la línea
$pdf->Line(156.5, 39.5, 210, 39.5); // Coordenadas de inicio y fin de la línea (X1, Y1, X2, Y2)
// Y1 y y2 30 SE Y 30 SE AUMENTA PARA BAJAR Y SE DISMINUYE PARA SUBIR)
// x1 se disminuye para mover hacia la izquierda y x2  y disminuye para mover hacia la izquierda y se aumenta para ir hacia hacia la derecha

// Añadir el texto "Municipio:"
$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 10
$pdf->SetXY(7, 38); // Ajustar la posición horizontal (X) y vertical (Y)
$pdf->Cell(50, 10, 'Municipio:', 0, 0, 'L'); // Agregar el texto
// DISMINUIR X HACIA LA IZQUIERDA AUMENTAR HACIA LA DERECHA


// Añadir el texto "MARACAIBO" encima de la línea
$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 8
$pdf->SetXY(40, 37.5); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, 'MARACAIBO', 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha

// Dibujar una línea al lado derecho del texto
$pdf->SetLineWidth(0.3); // Grosor de la línea
$pdf->Line(77.8, 44.5, 23, 44.5); // Coordenadas de inicio y fin de la línea (X1, Y1, X2, Y2)
// Y1 y y2 30 SE Y 30 SE AUMENTA PARA BAJAR Y SE DISMINUYE PARA SUBIR)
// x1 se AUMENTA para mover hacia la derecha y se disminuye para ir hacia la izquierda y x2  y disminuye para mover hacia la izquierda y se aumenta para ir hacia hacia la derecha

// Añadir el texto "Entidad Federal:"
$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 10
$pdf->SetXY(77, 38); // Ajustar la posición horizontal (X) y vertical (Y)
$pdf->Cell(50, 10, 'Entidad Federal:', 0, 0, 'L'); // Agregar el texto
// DISMINUIR X HACIA LA IZQUIERDA AUMENTAR HACIA LA DERECHA


// Añadir el texto "ZULIA" encima de la línea
$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 8
$pdf->SetXY(100, 37.5); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, 'ZULIA', 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha

// Dibujar una línea al lado derecho del texto
$pdf->SetLineWidth(0.3); // Grosor de la línea
$pdf->Line(110, 44.5, 100, 44.5); // Coordenadas de inicio y fin de la línea (X1, Y1, X2, Y2)
// Y1 y y2 30 SE Y 30 SE AUMENTA PARA BAJAR Y SE DISMINUYE PARA SUBIR)
// x1 se AUMENTA para mover hacia la derecha y se disminuye para ir hacia la izquierda y x2  y disminuye para mover hacia la izquierda y se aumenta para ir hacia hacia la derecha

// Añadir el texto "Zona Educativa:"
$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 10
$pdf->SetXY(134, 38); // Ajustar la posición horizontal (X) y vertical (Y)
$pdf->Cell(50, 10, 'Zona Educativa:', 0, 0, 'L'); // Agregar el texto
// DISMINUIR X HACIA LA IZQUIERDA AUMENTAR HACIA LA DERECHA


// Añadir el texto "ZULIA" encima de la línea
$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 8
$pdf->SetXY(176, 37.5); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, 'ZULIA', 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha

// Dibujar una línea al lado derecho del texto
$pdf->SetLineWidth(0.3); // Grosor de la línea
$pdf->Line(156.5, 44.5, 210, 44.5); // Coordenadas de inicio y fin de la línea (X1, Y1, X2, Y2)
// Y1 y y2 30 SE Y 30 SE AUMENTA PARA BAJAR Y SE DISMINUYE PARA SUBIR)
// x1 se disminuye para mover hacia la izquierda y x2  y disminuye para mover hacia la izquierda y se aumenta para ir hacia hacia la derecha

// Añadir el texto "III. Datos de Identificaciòn del Estudiante:"
$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 10
$pdf->SetXY(7, 43); // Ajustar la posición horizontal (X) y vertical (Y)
$pdf->Cell(50, 10, 'III. Datos de Identificaciòn del Estudiante:', 0, 0, 'L'); // Agregar el texto
// DISMINUIR X HACIA LA IZQUIERDA AUMENTAR HACIA LA DERECHA

// Añadir el texto "Cèdula de Identidad:"
$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 10
$pdf->SetXY(7, 48); // Ajustar la posición horizontal (X) y vertical (Y)
$pdf->Cell(50, 10, 'Cèdula de Identidad:', 0, 0, 'L'); // Agregar el texto
// DISMINUIR X HACIA LA IZQUIERDA AUMENTAR HACIA LA DERECHA

// Añadir el texto "Nro de cèdula del estudiante" encima de la línea
$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 8
$pdf->SetXY(50, 48); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10,  $estudiante['cedula'], 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha

// Dibujar una línea al lado derecho del texto
$pdf->SetLineWidth(0.3); // Grosor de la línea
$pdf->Line(36, 55, 105, 55); // Coordenadas de inicio y fin de la línea (X1, Y1, X2, Y2)
// Y1 y y2 30 SE Y 30 SE AUMENTA PARA BAJAR Y SE DISMINUYE PARA SUBIR)
// x1 se disminuye para mover hacia la izquierda y x2  y disminuye para mover hacia la izquierda y se aumenta para ir hacia hacia la derecha

// Añadir el texto "Fecha de Nacimiento:"
$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 10
$pdf->SetXY(105, 48); // Ajustar la posición horizontal (X) y vertical (Y)
$pdf->Cell(50, 10, 'Fecha de Nacimiento:', 0, 0, 'L'); // Agregar el texto
// DISMINUIR X HACIA LA IZQUIERDA AUMENTAR HACIA LA DERECHA


$pdf->SetFont('helvetica', 'B', 8); // Volver a fuente normal
$pdf->SetXY(160, 48); // Ajustar la posición para la fecha
$pdf->Cell(60, 10, $fecha_formateada, 0, 0, 'L'); // Mostrar fecha en formato DD-MM-YYYY


// Dibujar una línea al lado derecho del texto
$pdf->SetLineWidth(0.3); // Grosor de la línea
$pdf->Line(136, 55, 210, 55); // Coordenadas de inicio y fin de la línea (X1, Y1, X2, Y2)
// Y1 y y2 30 SE Y 30 SE AUMENTA PARA BAJAR Y SE DISMINUYE PARA SUBIR)
// x1 se disminuye para mover hacia la izquierda y x2  y disminuye para mover hacia la izquierda y se aumenta para ir hacia hacia la derecha

// Añadir el texto "Apellidos:"
$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 10
$pdf->SetXY(7, 53); // Ajustar la posición horizontal (X) y vertical (Y)
$pdf->Cell(50, 10, 'Apellidos:', 0, 0, 'L'); // Agregar el texto
// DISMINUIR X HACIA LA IZQUIERDA AUMENTAR HACIA LA DERECHA

// Añadir el texto "DATOS DEL ESTUDIANTE" encima de la línea
$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 8
$pdf->SetXY(43, 53); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10,  $estudiante['apellidos'], 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha

// Dibujar una línea al lado derecho del texto
$pdf->SetLineWidth(0.3); // Grosor de la línea
$pdf->Line(22, 60, 105, 60); // Coordenadas de inicio y fin de la línea (X1, Y1, X2, Y2)
// Y1 y y2 30 SE Y 30 SE AUMENTA PARA BAJAR Y SE DISMINUYE PARA SUBIR)
// x1 se disminuye para mover hacia la izquierda y x2  y disminuye para mover hacia la izquierda y se aumenta para ir hacia hacia la derecha

// Añadir el texto "Nombres:"
$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 10
$pdf->SetXY(105, 53); // Ajustar la posición horizontal (X) y vertical (Y)
$pdf->Cell(50, 10, 'Nombres:', 0, 0, 'L'); // Agregar el texto
// DISMINUIR X HACIA LA IZQUIERDA AUMENTAR HACIA LA DERECHA


// Añadir el texto "DATOS DEL ESTUDIANTE" encima de la línea
$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 8
$pdf->SetXY(155, 53); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, $estudiante['nombres'], 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha

// Dibujar una línea al lado derecho del texto
$pdf->SetLineWidth(0.3); // Grosor de la línea
$pdf->Line(120, 60, 210, 60); // Coordenadas de inicio y fin de la línea (X1, Y1, X2, Y2)
// Y1 y y2 30 SE Y 30 SE AUMENTA PARA BAJAR Y SE DISMINUYE PARA SUBIR)
// x1 se disminuye para mover hacia la izquierda y x2  y disminuye para mover hacia la izquierda y se aumenta para ir hacia hacia la derecha

// Añadir el texto "Lugar de Nacimiento:"
$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 10
$pdf->SetXY(7, 58); // Ajustar la posición horizontal (X) y vertical (Y)
$pdf->Cell(50, 10, 'Lugar de Nacimiento:', 0, 0, 'L'); // Agregar el texto
// DISMINUIR X HACIA LA IZQUIERDA AUMENTAR HACIA LA DERECHA

// Añadir el texto "INGRESAR" encima de la línea
$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 8
$pdf->SetXY(60, 58); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10,  $estudiante['lugar_nacimiento'], 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha

// Dibujar una línea al lado derecho del texto
$pdf->SetLineWidth(0.3); // Grosor de la línea
$pdf->Line(37, 65, 105, 65); // Coordenadas de inicio y fin de la línea (X1, Y1, X2, Y2)
// Y1 y y2 30 SE Y 30 SE AUMENTA PARA BAJAR Y SE DISMINUYE PARA SUBIR)
// x1 se disminuye para mover hacia la izquierda y x2  y disminuye para mover hacia la izquierda y se aumenta para ir hacia hacia la derecha

// Añadir el texto "Entidad Federal o Paìs:"
$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 10
$pdf->SetXY(105, 58); // Ajustar la posición horizontal (X) y vertical (Y)
$pdf->Cell(50, 10, 'Entidad Federal o Paìs:', 0, 0, 'L'); // Agregar el texto
// DISMINUIR X HACIA LA IZQUIERDA AUMENTAR HACIA LA DERECHA


// Añadir el texto "INGRESAR" encima de la línea
$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 8
$pdf->SetXY(155, 58); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10,  $estudiante['entidad_federal'], 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha

// Dibujar una línea al lado derecho del texto
$pdf->SetLineWidth(0.3); // Grosor de la línea
$pdf->Line(137, 65, 210, 65); // Coordenadas de inicio y fin de la línea (X1, Y1, X2, Y2)
// Y1 y y2 30 SE Y 30 SE AUMENTA PARA BAJAR Y SE DISMINUYE PARA SUBIR)
// x1 se disminuye para mover hacia la izquierda y x2  y disminuye para mover hacia la izquierda y se aumenta para ir hacia hacia la derecha

// Añadir el texto "IV. Planteles donde cursò estudios:"
$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 10
$pdf->SetXY(7, 63); // Ajustar la posición horizontal (X) y vertical (Y)
$pdf->Cell(50, 10, 'IV. Planteles donde cursò estudios:', 0, 0, 'L'); // Agregar el texto
// DISMINUIR X HACIA LA IZQUIERDA AUMENTAR HACIA LA DERECHA



// Añadir un espaciado para bajar el contenido
$pdf->Ln(8); // Espaciado adicional para bajar el contenido





// Añadir el texto "INGRESAR" encima de la línea
$pdf->SetFont('helvetica', 'B', 7); // Fuente normal y tamaño 8
$pdf->SetXY(13, 73.5); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10,  $planteles_unicos[0]['nombre'], 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha

// Añadir el texto "INGRESAR" encima de la línea
$pdf->SetFont('helvetica', 'B', 7); // Fuente normal y tamaño 8
$pdf->SetXY(74, 73.5); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10,  $planteles_unicos[0]['localidad'], 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha

// Añadir el texto "INGRESAR" encima de la línea
$pdf->SetFont('helvetica', 'B', 7); // Fuente normal y tamaño 8
$pdf->SetXY(101.5, 73.5); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10,  substr($planteles_unicos[0]['entidad_federal'], 0, 2), 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha


// Añadir el texto "INGRESAR" encima de la línea
$pdf->SetFont('helvetica', 'B', 7); // Fuente normal y tamaño 8
$pdf->SetXY(13, 77.5); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10,  $planteles_unicos[1]['nombre'], 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha


// Añadir el texto "INGRESAR" encima de la línea
$pdf->SetFont('helvetica', 'B', 7); // Fuente normal y tamaño 8
$pdf->SetXY(74, 77.5); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10,  $planteles_unicos[1]['localidad'], 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha


// Añadir el texto "INGRESAR" encima de la línea
$pdf->SetFont('helvetica', 'B', 7); // Fuente normal y tamaño 8
$pdf->SetXY(101.5, 77.5); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10,  substr($planteles_unicos[1]['entidad_federal'], 0, 2), 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha


// Añadir el texto "INGRESAR" encima de la línea
$pdf->SetFont('helvetica', 'B', 7); // Fuente normal y tamaño 8
$pdf->SetXY(114, 69.2); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10,  $planteles_unicos[2]['nombre'], 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha



// Añadir el texto "INGRESAR" encima de la línea
$pdf->SetFont('helvetica', 'B', 7); // Fuente normal y tamaño 8
$pdf->SetXY(176, 69.2); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10,  $planteles_unicos[2]['localidad'], 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha


// Añadir el texto "INGRESAR" encima de la línea
$pdf->SetFont('helvetica', 'B', 7); // Fuente normal y tamaño 8
$pdf->SetXY(203.6, 69.2); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10,  substr($planteles_unicos[2]['entidad_federal'], 0, 2), 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha



// Añadir el texto "INGRESAR" encima de la línea
$pdf->SetFont('helvetica', 'B', 7); // Fuente normal y tamaño 8
$pdf->SetXY(114, 73.5); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10,  $planteles_unicos[3]['nombre'], 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha


// Añadir el texto "INGRESAR" encima de la línea
$pdf->SetFont('helvetica', 'B', 7); // Fuente normal y tamaño 8
$pdf->SetXY(176, 73.5); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10,  $planteles_unicos[3]['localidad'], 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha


// Añadir el texto "INGRESAR" encima de la línea
$pdf->SetFont('helvetica', 'B', 7); // Fuente normal y tamaño 8
$pdf->SetXY(203.6, 73.5); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10,  substr($planteles_unicos[3]['entidad_federal'], 0, 2), 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha


// Añadir el texto "INGRESAR" encima de la línea
$pdf->SetFont('helvetica', 'B', 7); // Fuente normal y tamaño 8
$pdf->SetXY(114, 77.5); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10,  $planteles_unicos[4]['nombre'], 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha



// Añadir el texto "INGRESAR" encima de la línea
$pdf->SetFont('helvetica', 'B', 7); // Fuente normal y tamaño 8
$pdf->SetXY(176, 77.5); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10,  $planteles_unicos[4]['localidad'], 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha


// Añadir el texto "INGRESAR" encima de la línea
$pdf->SetFont('helvetica', 'B', 7); // Fuente normal y tamaño 8
$pdf->SetXY(203.6, 77.5); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10,  substr($planteles_unicos[4]['entidad_federal'], 0, 2), 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha



$pdf->SetXY(6.5, 72); // Posicionar esta tabla hacia la izquierda
$table1 = '
<table border="1" cellpadding="1" style="width: 100%; float: left;">

     <tr>
           <th style="text-align: center; width: 16px;">N°</th>
           
        <th style="text-align: center; width: 58.4%; height:12px;">Nombre del Plantel</th>
        <th style="text-align: center; width: 100px;">Localidad</th>
        <th style="text-align: center; width: 25px;">E.F.</th>
          
    </tr>
    <tr>
       <td style="text-align: center;">1</td>
        <td style="text-align: center; height:12px;">    </td>
        <td style="text-align: center;">    </td>
        <td style="text-align: center;"> </td>

    </tr>
    <tr>
        <td style="text-align: center;">2</td>
        <td style="text-align: center; height:11.9px;">    </td>
        <td style="text-align: center;">    </td>
        <td style="text-align: center;"> </td>
    </tr>
    
</table>';
$pdf->writeHTMLCell(90, '', '', '', $table1, 0, 0, false, true, '');

// Añadir el texto "COMPONENTES" encima de la línea
$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 8
$pdf->SetXY(100, 81.9); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, 'COMPONENTES', 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha

// Colspan debajo de ambas tablas
$pdf->SetXY(6.5, 84.6); // Ajustar la posición del colspan
$colspanTable = '
<table border="1" cellpadding="5" style=" font-size: 3px; width: 107.9%;">
    <tr>
       <td colspan="8" style="text-align: center; font-size: 1px;">
            <span style="font-size: 8px; font-weight: bold; "></span>
        </td>
    </tr>
</table>';
$pdf->writeHTMLCell(190, '', '', '', $colspanTable, 0, 1, false, true, '');

// -------------------------------
// Tabla 2: 4 filas y 3 columnas
// -------------------------------
$pdf->SetXY(107.5, 67.7); // Posicionar la tabla encima y al lado derecho
$table2 = '
<table border="1" cellpadding="1" style="width: 100%; float: left;">

     <tr>
           <th style="text-align: center; width: 16px;">N°</th>
           
        <th style="text-align: center;  width: 60%;">Nombre del Plantel</th>
        <th style="text-align: center; width: 100px;">Localidad</th>
        <th style="text-align: center; width: 23px;">E.F.</th>
          
    </tr>
    <tr>
         <td style="text-align: center; font-size:7px;">3</td>
        <td style="text-align: center;">    </td>
        <td style="text-align: center;">    </td>
        <td style="text-align: center;"> </td>
    </tr>
    <tr>
      <td style="text-align: center; font-size:7px;">4</td>
        <td style="text-align: center;">    </td>
        <td style="text-align: center;">    </td>
        <td style="text-align: center;"> </td>
    </tr>
    <tr>
       <td style="text-align: center; font-size:7px;">5</td>
        <td style="text-align: center;">    </td>
        <td style="text-align: center;">    </td>
        <td style="text-align: center;"> </td>
    </tr>

</table>';
$pdf->writeHTMLCell(90, '', '', '', $table2, 0, 1, false, true, '');

// Añadir el texto "V. Pensum de estudio:" encima de la línea
$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 8
$pdf->SetXY(6.5, 86.5); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, 'V. Pensum de estudio:', 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha



//PERIODO 1º

$pdf->SetFont('helvetica', 'B', 8); // Fuente y tamaño del texto

$pdf->SetXY(8, 91.2); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'PERIODO:'.$periodo1[0]['nombre_periodo'], 0, 0, 'L'); // Agrega el texto




$pdf->SetFont('helvetica', 'B', 6 ); // Fuente y tamaño del texto
$pdf->StartTransform(); // Inicia la transformación
$pdf->Rotate(90, 100, 90); // Rota el texto -90 grados hacia la izquierda en el punto (100, 90)
$pdf->SetXY(82.4, 90); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'Plantel', 0, 0, 'L'); // Agrega el texto
$pdf->StopTransform(); // Finaliza la transformación
$pdf->SetFont('helvetica', 'B', 8); // Restaurar el tamaño original de la fuente

$pdf->SetFont('helvetica', 'B', 8 ); // Fuente y tamaño del texto
$pdf->SetXY(79.6, 98); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'T-E', 0, 0, 'L'); // Agrega el texto


// Texto AREAS DE FORMACION
$pdf->SetFont('helvetica', 'B', 8 ); // Fuente y tamaño del texto
$pdf->SetXY(15, 98); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'AREAS DE FORMACION', 0, 0, 'L'); // Agrega el texto



// Tabla 1 periodos

//prueba1
$pdf->SetCellPaddings(0, 0.9, 0, 0); // Ajusta el padding superior a 5 unidades

$pdf->Ln(30); // Espaciado adicional para bajar el contenido
$pdf->SetXY(7.5, 98.5); // Posicionar esta tabla hacia la izquierda
// Añadir un espaciado para bajar el contenido

$html = '
<table border="1" cellpadding="1" style="width: 100%; float: left; border-collapse: collapse;">


  <tr>
    <th rowspan="2" style="width: 140px; vertical-align: middle; font-size: 8px; border: 1px solid black; text-align: center; padding: 2px;"> </th>
    <th colspan="2" style="width: 65px; height: 13px; border: 1px solid black; text-align: center; vertical-align: middle; padding: 2px;"><b>Calificación</b></th>
    <th rowspan="2" style="width: 18px; vertical-align: middle; border: 1px solid black; text-align: center; padding: 2px;"><b></b></th>
    <th colspan="2" style="width: 45px; border: 1px solid black; text-align: center; padding: 2px;"><b>Fecha</b></th>
    <th rowspan="2" style="width: 16.4px;  text-align: center; border: 1px solid black; padding: 2px;"><b></b></th>
</tr>
<tr>
    <th style="width: 20px; border: 1px solid black; text-align: center; vertical-align: middle; padding: 2px;">N°</th>
    <th style="width: 45px; border: 1px solid black; text-align: center; vertical-align: middle; padding: 2px;">Letras</th>
    <th style="width: 22px; border: 1px solid black; text-align: center; vertical-align: middle; padding: 2px;">Mes</th>
    <th style="width: 23px; border: 1px solid black; text-align: center; vertical-align: middle; padding: 2px;">Año</th>
</tr>

  <tr>
    <td style="text-align: left; height: 5px; font-size: 7px; border: 1px solid black; padding: 5px;" class="custom-height">'.
    $periodo1[0]['nombre_curso'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.
    $periodo1[0]['calificacion'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.
    $periodo1[0]['calificacion_letras'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.
    $periodo1[0]['te'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.
    $periodo1[0]['mes'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.
    $periodo1[0]['anio'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.
    $periodo1[0]['numero_plantel'].'</td>
  </tr>
  <tr>
    <td style="text-align: left; height: 5px; font-size: 7px; border: 1px solid black; padding: 5px;" class="custom-height">'.$periodo1[1]['nombre_curso'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'. $periodo1[1]['calificacion'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'. $periodo1[1]['calificacion_letras'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'. $periodo1[1]['te'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'. $periodo1[1]['mes'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'. $periodo1[1]['anio'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'. $periodo1[1]['numero_plantel'].'</td>
  </tr>
   <tr>
    <td style="text-align: left; height: 5px; font-size: 7px; border: 1px solid black; padding: 5px;" class="custom-height">'.$periodo1[2]['nombre_curso'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'. $periodo1[2]['calificacion'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'. $periodo1[2]['calificacion_letras'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'. $periodo1[2]['te'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'. $periodo1[2]['mes'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'. $periodo1[2]['anio'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'. $periodo1[2]['numero_plantel'].'</td>
  </tr>
   <tr>
    <td style="text-align: left; height: 5px; font-size: 7px; border: 1px solid black; padding: 5px;" class="custom-height">'.$periodo1[3]['nombre_curso'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'. $periodo1[3]['calificacion'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'. $periodo1[3]['calificacion_letras'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'. $periodo1[3]['te'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'. $periodo1[3]['mes'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'. $periodo1[3]['anio'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'. $periodo1[3]['numero_plantel'].'</td>
  </tr>

  <!-- Aprobado / No Aprobado -->
  <tr>
    <td style="text-align: left;  font-size: 7px; border: 1px solid black; vertical-align: middle; padding: 5px;" rowspan="2">'.$nomb.'</td>
    <td colspan="2" style="width: 65px; height: 5px; font-size: 7px; border: 1px solid black; text-align: left; vertical-align: middle; padding: 5px;">APROBADO</td>
   <td style="text-align: center;">'.($califica === 'APROBADO' ? htmlspecialchars($te) : '***').'</td>
            <td style="text-align: center;">'.($califica === 'APROBADO' ? htmlspecialchars($mesx) : '***').'</td>
            <td style="text-align: center;">'.($califica === 'APROBADO' ? htmlspecialchars($anio) : '***').'</td>
            <td style="text-align: center;">'.($califica === 'APROBADO' ? htmlspecialchars($numero_plantel) : '***').'</td>
  </tr>
  <tr>
    <td colspan="2" style="width: 65px; height: 5px; font-size: 7px; border: 1px solid black; text-align: left; vertical-align: middle; padding: 5px;">NO APROBADO</td>
     <td style="text-align: center;">'.($califica !== 'APROBADO' ? htmlspecialchars($te) : '***').'</td>
            <td style="text-align: center;">'.($califica !== 'APROBADO' ? htmlspecialchars($mesx) : '***').'</td>
            <td style="text-align: center;">'.($califica !== 'APROBADO' ? htmlspecialchars($anio) : '***').'</td>
            <td style="text-align: center;">'.($califica !== 'APROBADO' ? htmlspecialchars($numero_plantel) : '***').'</td>
  </tr>
</table>';



$pdf->writeHTML($html, true, false, true, false, ''); // Escribe la tabla en el PDF



// Colspan debajo de ambas tablas
$pdf->SetXY(7.5, 92.9); // Ajustar la posición del colspan
$colspanTable = '
<table border="1" cellpadding="5" style=" font-size: 3px; width: 52.8%;">
    <tr>
       <td colspan="5" style="text-align: center; font-size: 1px;">
            <span style="font-size: 8px; font-weight: bold; "></span>
        </td>
    </tr>
</table>';
$pdf->writeHTMLCell(190, '', '', '', $colspanTable, 0, 1, false, true, '');






// PERIODO 2º 

$pdf->SetFont('helvetica', 'B', 8); // Fuente y tamaño del texto


$pdf->SetXY(111.3, 91.2); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'PERIODO:'.$periodo2[1]['nombre_periodo'], 0, 0, 'L'); // Agrega el texto



$pdf->SetFont('helvetica', 'B', 6 ); // Fuente y tamaño del texto
$pdf->StartTransform(); // Inicia la transformación
$pdf->Rotate(90, 100, 90); // Rota el texto -90 grados hacia la izquierda en el punto (100, 90)
$pdf->SetXY(83.3, 193); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'Plantel', 0, 0, 'L'); // Agrega el texto
$pdf->StopTransform(); // Finaliza la transformación
$pdf->SetFont('helvetica', 'B', 8); // Restaurar el tamaño original de la fuente

$pdf->SetFont('helvetica', 'B', 8 ); // Fuente y tamaño del texto
$pdf->SetXY(183.6, 98); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'T-E', 0, 0, 'L'); // Agrega el texto



// Texto AREAS DE FORMACION
$pdf->SetFont('helvetica', 'B', 8 ); // Fuente y tamaño del texto
$pdf->SetXY(118, 98); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'AREAS DE FORMACION', 0, 0, 'L'); // Agrega el texto


//prueba2
$pdf->SetCellPaddings(0, 0.9, 0, 0); // Ajusta el padding superior a 5 unidades

$pdf->Ln(30); // Espaciado adicional para bajar el contenido
$pdf->SetXY(110.5, 98.5); // Posicionar esta tabla hacia la izquierda
// Añadir un espaciado para bajar el contenido




$html = '
<table border="1" cellpadding="1" style="width: 100%; float: left; border-collapse: collapse;">


  <tr>
    <th rowspan="2" style="width: 140px; vertical-align: middle; font-size: 8px; border: 1px solid black; text-align: center; padding: 2px;"> </th>
    <th colspan="2" style="width: 65px; height: 13px; border: 1px solid black; text-align: center; vertical-align: middle; padding: 2px;"><b>Calificación</b></th>
    <th rowspan="2" style="width: 18px; vertical-align: middle; border: 1px solid black; text-align: center; padding: 2px;"><b> </b></th>
    <th colspan="2" style="width: 45px; border: 1px solid black; text-align: center; padding: 2px;"><b>Fecha</b></th>
    <th rowspan="2" style="width: 16.4px;  text-align: center; border: 1px solid black; padding: 2px;"><b></b></th>
</tr>
<tr>
    <th style="width: 20px; border: 1px solid black; text-align: center; vertical-align: middle; padding: 2px;">N°</th>
    <th style="width: 45px; border: 1px solid black; text-align: center; vertical-align: middle; padding: 2px;">Letras</th>
    <th style="width: 22px; border: 1px solid black; text-align: center; vertical-align: middle; padding: 2px;">Mes</th>
    <th style="width: 23px; border: 1px solid black; text-align: center; vertical-align: middle; padding: 2px;">Año</th>
</tr>

  <tr>
    <td style="text-align: left; height: 5px; font-size: 7px; border: 1px solid black; padding: 5px;" class="custom-height">'.$periodo2[0]['nombre_curso'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo2[0]['calificacion'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo2[0]['calificacion_letras']. '</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo2[0]['te'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo2[0]['mes'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo2[0]['anio']. '</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo2[0]['numero_plantel'].'</td>
  </tr>
  <tr>
    <td style="text-align: left; height: 5px; font-size: 7px; border: 1px solid black; padding: 5px;" class="custom-height">'.$periodo2[1]['nombre_curso'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo2[1]['calificacion'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo2[1]['calificacion_letras'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo2[1]['te'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo2[1]['mes'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo2[1]['anio'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo2[1]['numero_plantel'].'</td>
  </tr>
  <tr>
    <td style="text-align: left; height: 5px; font-size: 7px; border: 1px solid black; padding: 5px;" class="custom-height">'.$periodo2[2]['nombre_curso'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo2[2]['calificacion'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo2[2]['calificacion_letras'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo2[2]['te']. '</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo2[2]['mes'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo2[2]['anio'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo2[2]['numero_plantel'].'</td>
  </tr>
  <tr>
    <td style="text-align: left; height: 5px; font-size: 7px; border: 1px solid black; padding: 5px;" class="custom-height">'.$periodo2[3]['nombre_curso'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo2[3]['calificacion'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo2[3]['calificacion_letras'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo2[3]['te'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo2[3]['mes'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo2[3]['anio'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo2[3]['numero_plantel'].'</td>
  </tr>

  <!-- Aprobado / No Aprobado -->
   <tr>
    <td style="text-align: left;  font-size: 7px; border: 1px solid black; vertical-align: middle; padding: 5px;" rowspan="2">'.$nomb2.'</td>
    <td colspan="2" style="width: 65px; height: 5px; font-size: 7px; border: 1px solid black; text-align: left; vertical-align: middle; padding: 5px;">APROBADO</td>
   <td style="text-align: center;">'.($califica2 === 'APROBADO' ? htmlspecialchars($te2) : '***').'</td>
            <td style="text-align: center;">'.($califica2 === 'APROBADO' ? htmlspecialchars($mesx2) : '***').'</td>
            <td style="text-align: center;">'.($califica2 === 'APROBADO' ? htmlspecialchars($anio2) : '***').'</td>
            <td style="text-align: center;">'.($califica2 === 'APROBADO' ? htmlspecialchars($numero_plantel2) : '***').'</td>
  </tr>
  <tr>
    <td colspan="2" style="width: 65px; height: 5px; font-size: 7px; border: 1px solid black; text-align: left; vertical-align: middle; padding: 5px;">NO APROBADO</td>
     <td style="text-align: center;">'.($califica2 !== 'APROBADO' ? htmlspecialchars($te2) : '***').'</td>
            <td style="text-align: center;">'.($califica2 !== 'APROBADO' ? htmlspecialchars($mesx2) : '***').'</td>
            <td style="text-align: center;">'.($califica2 !== 'APROBADO' ? htmlspecialchars($anio2) : '***').'</td>
            <td style="text-align: center;">'.($califica2 !== 'APROBADO' ? htmlspecialchars($numero_plantel2) : '***').'</td>
  </tr>
</table>';


$pdf->writeHTML($html, true, false, true, false, ''); // Escribe la tabla en el PDF




// Colspan debajo de ambas tablas
$pdf->SetXY(110.5, 92.9); // Ajustar la posición del colspan
$colspanTable = '
<table border="1" cellpadding="5" style=" font-size: 3px; width: 52.8%;">
    <tr>
       <td colspan="8" style="text-align: center; font-size: 1px;">
            <span style="font-size: 8px; font-weight: bold; "></span>
        </td>
    </tr>
</table>';
$pdf->writeHTMLCell(190, '', '', '', $colspanTable, 0, 1, false, true, '');




// PERIODO 3º

$pdf->SetFont('helvetica', 'B', 8); // Fuente y tamaño del texto

$pdf->SetXY(8, 130); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'PERIODO:'.$periodo3[0]['nombre_periodo'], 0, 0, 'L'); // Agrega el texto




$pdf->SetFont('helvetica', 'B', 6 ); // Fuente y tamaño del texto
$pdf->StartTransform(); // Inicia la transformación
$pdf->Rotate(90, 100, 90); // Rota el texto -90 grados hacia la izquierda en el punto (100, 90)
$pdf->SetXY(44.5, 90); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'Plantel', 0, 0, 'L'); // Agrega el texto
$pdf->StopTransform(); // Finaliza la transformación
$pdf->SetFont('helvetica', 'B', 8); // Restaurar el tamaño original de la fuente



$pdf->SetFont('helvetica', 'B', 8 ); // Fuente y tamaño del texto
$pdf->SetXY(80.6, 136.8); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'T-E', 0, 0, 'L'); // Agrega el texto



// Texto AREAS DE FORMACION
$pdf->SetFont('helvetica', 'B', 8 ); // Fuente y tamaño del texto
$pdf->SetXY(15, 136.7); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'AREAS DE FORMACION', 0, 0, 'L'); // Agrega el texto




$pdf->SetXY(7.5, 137.3); // Posicionar esta tabla hacia la izquierda
// Añadir un espaciado para bajar el contenido

//prueba3
$html = '
<table border="1" cellpadding="1" style="width: 100%; float: left; border-collapse: collapse;">


  <tr>
    <th rowspan="2" style="width: 140px; vertical-align: middle; font-size: 8px; border: 1px solid black; text-align: center; padding: 2px;"> </th>
    <th colspan="2" style="width: 65px; height: 13px; border: 1px solid black; text-align: center; vertical-align: middle; padding: 2px;"><b>Calificación</b></th>
    <th rowspan="2" style="width: 18px; vertical-align: middle; border: 1px solid black; text-align: center; padding: 2px;"><b> </b></th>
    <th colspan="2" style="width: 45px; border: 1px solid black; text-align: center; padding: 2px;"><b>Fecha</b></th>
    <th rowspan="2" style="width: 16.4px;  text-align: center; border: 1px solid black; padding: 2px;"><b></b></th>
</tr>
<tr>
    <th style="width: 20px; border: 1px solid black; text-align: center; vertical-align: middle; padding: 2px;">N°</th>
    <th style="width: 45px; border: 1px solid black; text-align: center; vertical-align: middle; padding: 2px;">Letras</th>
    <th style="width: 22px; border: 1px solid black; text-align: center; vertical-align: middle; padding: 2px;">Mes</th>
    <th style="width: 23px; border: 1px solid black; text-align: center; vertical-align: middle; padding: 2px;">Año</th>
</tr>

  <tr>
    <td style="text-align: left; height: 5px; font-size: 7px; border: 1px solid black; padding: 5px;" class="custom-height">'.$periodo3[0]['nombre_curso'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo3[0]['calificacion'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo3[0]['calificacion_letras'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo3[0]['te'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo3[0]['mes'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo3[0]['anio'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo3[0]['numero_plantel'].'</td>
  </tr>
  <tr>
    <td style="text-align: left; height: 5px; font-size: 7px; border: 1px solid black; padding: 5px;" class="custom-height">'.$periodo3[1]['nombre_curso'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo3[1]['calificacion'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo3[1]['calificacion_letras'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo3[1]['te'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo3[1]['mes'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo3[1]['anio'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo3[1]['numero_plantel'].'</td>
  </tr>
  <tr>
    <td style="text-align: left; height: 5px; font-size: 7px; border: 1px solid black; padding: 5px;" class="custom-height">'.$periodo3[2]['nombre_curso'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo3[2]['calificacion'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo3[2]['calificacion_letras'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo3[2]['te'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo3[2]['mes'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo3[2]['anio'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo3[2]['numero_plantel'].'</td>
  </tr>
  <tr>
    <td style="text-align: left; height: 5px; font-size: 7px; border: 1px solid black; padding: 5px;" class="custom-height">'.$periodo3[3]['nombre_curso'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo3[3]['calificacion'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo3[3]['calificacion_letras'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo3[3]['te'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo3[3]['mes'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo3[3]['anio'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo3[3]['numero_plantel'].'</td>
  </tr>

  <!-- Aprobado / No Aprobado -->
   <tr>
    <td style="text-align: left;  font-size: 7px; border: 1px solid black; vertical-align: middle; padding: 5px;" rowspan="2">'.$nomb3.'</td>
    <td colspan="2" style="width: 65px; height: 5px; font-size: 7px; border: 1px solid black; text-align: left; vertical-align: middle; padding: 5px;">APROBADO</td>
   <td style="text-align: center;">'.($califica3 === 'APROBADO' ? htmlspecialchars($te3) : '***').'</td>
            <td style="text-align: center;">'.($califica3 === 'APROBADO' ? htmlspecialchars($mesx3) : '***').'</td>
            <td style="text-align: center;">'.($califica3 === 'APROBADO' ? htmlspecialchars($anio3) : '***').'</td>
            <td style="text-align: center;">'.($califica3 === 'APROBADO' ? htmlspecialchars($numero_plantel3) : '***').'</td>
  </tr>
  <tr>
    <td colspan="2" style="width: 65px; height: 5px; font-size: 7px; border: 1px solid black; text-align: left; vertical-align: middle; padding: 5px;">NO APROBADO</td>
     <td style="text-align: center;">'.($califica3 !== 'APROBADO' ? htmlspecialchars($te3) : '***').'</td>
            <td style="text-align: center;">'.($califica3 !== 'APROBADO' ? htmlspecialchars($mesx3) : '***').'</td>
            <td style="text-align: center;">'.($califica3 !== 'APROBADO' ? htmlspecialchars($anio3) : '***').'</td>
            <td style="text-align: center;">'.($califica3 !== 'APROBADO' ? htmlspecialchars($numero_plantel3) : '***').'</td>
  </tr>
</table>';



$pdf->writeHTML($html, true, false, true, false, ''); // Escribe la tabla en el PDF




// Colspan debajo de ambas tablas
$pdf->SetXY(7.5, 131.8); // Ajustar la posición del colspan
$colspanTable = '
<table border="1" cellpadding="5" style=" font-size: 3px; width: 52.8%;">
    <tr>
       <td colspan="8" style="text-align: center; font-size: 1px;">
            <span style="font-size: 8px; font-weight: bold; "></span>
        </td>
    </tr>
</table>';
$pdf->writeHTMLCell(190, '', '', '', $colspanTable, 0, 1, false, true, '');






// PERIDO 4º

$pdf->SetFont('helvetica', 'B', 8); // Fuente y tamaño del texto

$pdf->SetXY(111.3, 130); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'PERIODO:'.$periodo4[0]['nombre_periodo'], 0, 0, 'L'); // Agrega el texto



$pdf->SetFont('helvetica', 'B', 6 ); // Fuente y tamaño del texto
$pdf->StartTransform(); // Inicia la transformación
$pdf->Rotate(90, 100, 90); // Rota el texto -90 grados hacia la izquierda en el punto (100, 90)
$pdf->SetXY(44.6, 193); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'Plantel', 0, 0, 'L'); // Agrega el texto
$pdf->StopTransform(); // Finaliza la transformación
$pdf->SetFont('helvetica', 'B', 8); // Restaurar el tamaño original de la fuente



$pdf->SetFont('helvetica', 'B', 8 ); // Fuente y tamaño del texto
$pdf->SetXY(183.6, 136.8); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'T-E', 0, 0, 'L'); // Agrega el texto

// Texto AREAS DE FORMACION
$pdf->SetFont('helvetica', 'B', 8 ); // Fuente y tamaño del texto
$pdf->SetXY(118, 136.8); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'AREAS DE FORMACION', 0, 0, 'L'); // Agrega el texto




$pdf->SetXY(110.5, 137.3); // Posicionar esta tabla hacia la izquierda
// Añadir un espaciado para bajar el contenido

//prueba4
$html = '
<table border="1" cellpadding="1" style="width: 100%; float: left; border-collapse: collapse;">


  <tr>
    <th rowspan="2" style="width: 140px; vertical-align: middle; font-size: 8px; border: 1px solid black; text-align: center; padding: 2px;"> </th>
    <th colspan="2" style="width: 65px; height: 13px; border: 1px solid black; text-align: center; vertical-align: middle; padding: 2px;"><b>Calificación</b></th>
    <th rowspan="2" style="width: 18px; vertical-align: middle; border: 1px solid black; text-align: center; padding: 2px;"> </th>
    <th colspan="2" style="width: 45px; border: 1px solid black; text-align: center; padding: 2px;"><b>Fecha</b></th>
    <th rowspan="2" style="width: 16.4px;  text-align: center; border: 1px solid black; padding: 2px;"><b></b></th>
</tr>
<tr>
    <th style="width: 20px; border: 1px solid black; text-align: center; vertical-align: middle; padding: 2px;">N°</th>
    <th style="width: 45px; border: 1px solid black; text-align: center; vertical-align: middle; padding: 2px;">Letras</th>
    <th style="width: 22px; border: 1px solid black; text-align: center; vertical-align: middle; padding: 2px;">Mes</th>
    <th style="width: 23px; border: 1px solid black; text-align: center; vertical-align: middle; padding: 2px;">Año</th>
</tr>

  <tr>
    <td style="text-align: left; height: 5px; font-size: 7px; border: 1px solid black; padding: 5px;" class="custom-height">'.$periodo4[0]['nombre_curso'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo4[0]['calificacion'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo4[0]['calificacion_letras'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo4[0]['te'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo4[0]['mes'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo4[0]['anio'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo4[0]['numero_plantel'].'</td>
  </tr>
  <tr>
    <td style="text-align: left; height: 5px; font-size: 7px; border: 1px solid black; padding: 5px;" class="custom-height">'.$periodo4[1]['nombre_curso'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo4[1]['calificacion'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo4[1]['calificacion_letras'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo4[1]['te'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo4[1]['mes'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo4[1]['anio'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo4[1]['numero_plantel'].'</td>
  </tr>
  <tr>
    <td style="text-align: left; height: 5px; font-size: 7px; border: 1px solid black; padding: 5px;" class="custom-height">'.$periodo4[2]['nombre_curso'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo4[2]['calificacion'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo4[2]['calificacion_letras'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo4[2]['te'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo4[2]['mes'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo4[2]['anio'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo4[2]['numero_plantel'].'</td>
  </tr>
  <tr>
    <td style="text-align: left; height: 5px; font-size: 7px; border: 1px solid black; padding: 5px;" class="custom-height">'.$periodo4[3]['nombre_curso'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo4[3]['calificacion'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo4[3]['calificacion_letras'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo4[3]['te'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo4[3]['mes'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo4[3]['anio'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo4[3]['numero_plantel'].'</td>
  </tr>

  <!-- Aprobado / No Aprobado -->
  <tr>
    <td style="text-align: left;  font-size: 7px; border: 1px solid black; vertical-align: middle; padding: 5px;" rowspan="2">'.$nomb4.'</td>
    <td colspan="2" style="width: 65px; height: 5px; font-size: 7px; border: 1px solid black; text-align: left; vertical-align: middle; padding: 5px;">APROBADO</td>
   <td style="text-align: center;">'.($califica4 === 'APROBADO' ? htmlspecialchars($te4) : '***').'</td>
            <td style="text-align: center;">'.($califica4 === 'APROBADO' ? htmlspecialchars($mesx4) : '***').'</td>
            <td style="text-align: center;">'.($califica4 === 'APROBADO' ? htmlspecialchars($anio4) : '***').'</td>
            <td style="text-align: center;">'.($califica4 === 'APROBADO' ? htmlspecialchars($numero_plantel4) : '***').'</td>
  </tr>
  <tr>
    <td colspan="2" style="width: 65px; height: 5px; font-size: 7px; border: 1px solid black; text-align: left; vertical-align: middle; padding: 5px;">NO APROBADO</td>
     <td style="text-align: center;">'.($califica4 !== 'APROBADO' ? htmlspecialchars($te4) : '***').'</td>
            <td style="text-align: center;">'.($califica4 !== 'APROBADO' ? htmlspecialchars($mesx4) : '***').'</td>
            <td style="text-align: center;">'.($califica4 !== 'APROBADO' ? htmlspecialchars($anio4) : '***').'</td>
            <td style="text-align: center;">'.($califica4 !== 'APROBADO' ? htmlspecialchars($numero_plantel4) : '***').'</td>
  </tr>
</table>';



$pdf->writeHTML($html, true, false, true, false, ''); // Escribe la tabla en el PDF



// Colspan debajo de ambas tablas
$pdf->SetXY(110.5, 131.8); // Ajustar la posición del colspan
$colspanTable = '
<table border="1" cellpadding="5" style=" font-size: 3px; width: 52.8%;">
    <tr>
       <td colspan="8" style="text-align: center; font-size: 1px;">
            <span style="font-size: 8px; font-weight: bold; "></span>
        </td>
    </tr>
</table>';
$pdf->writeHTMLCell(190, '', '', '', $colspanTable, 0, 1, false, true, '');



// PERIODO 5º

$pdf->SetFont('helvetica', 'B', 8); // Fuente y tamaño del texto

$pdf->SetXY(8, 168.7); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'PERIODO:'.$periodo5[0]['nombre_periodo'], 0, 0, 'L'); // Agrega el texto




$pdf->SetFont('helvetica', 'B', 6 ); // Fuente y tamaño del texto
$pdf->StartTransform(); // Inicia la transformación
$pdf->Rotate(90, 100, 90); // Rota el texto -90 grados hacia la izquierda en el punto (100, 90)
$pdf->SetXY(5.7, 90); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'Plantel', 0, 0, 'L'); // Agrega el texto
$pdf->StopTransform(); // Finaliza la transformación
$pdf->SetFont('helvetica', 'B', 8); // Restaurar el tamaño original de la fuente


$pdf->SetFont('helvetica', 'B', 8 ); // Fuente y tamaño del texto
$pdf->SetXY(80.6, 175.8); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'T-E', 0, 0, 'L'); // Agrega el texto



// Texto AREAS DE FORMACION
$pdf->SetFont('helvetica', 'B', 8 ); // Fuente y tamaño del texto
$pdf->SetXY(15, 175.5); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'AREAS DE FORMACION', 0, 0, 'L'); // Agrega el texto




$pdf->SetXY(7.5, 176.2); // Posicionar esta tabla hacia la izquierda
// Añadir un espaciado para bajar el contenido

//prueba5
$html = '
<table border="1" cellpadding="1" style="width: 100%; float: left; border-collapse: collapse;">


  <tr>
    <th rowspan="2" style="width: 140px; vertical-align: middle; font-size: 8px; border: 1px solid black; text-align: center; padding: 2px;"> </th>
    <th colspan="2" style="width: 65px; height: 13px; border: 1px solid black; text-align: center; vertical-align: middle; padding: 2px;"><b>Calificación</b></th>
    <th rowspan="2" style="width: 18px; vertical-align: middle; border: 1px solid black; text-align: center; padding: 2px;"> </th>
    <th colspan="2" style="width: 45px; border: 1px solid black; text-align: center; padding: 2px;"><b>Fecha</b></th>
    <th rowspan="2" style="width: 16..7px;  text-align: center; border: 1px solid black; padding: 2px;"><b></b></th>
</tr>
<tr>
    <th style="width: 20px; border: 1px solid black; text-align: center; vertical-align: middle; padding: 2px;">N°</th>
    <th style="width: 45px; border: 1px solid black; text-align: center; vertical-align: middle; padding: 2px;">Letras</th>
    <th style="width: 22px; border: 1px solid black; text-align: center; vertical-align: middle; padding: 2px;">Mes</th>
    <th style="width: 23px; border: 1px solid black; text-align: center; vertical-align: middle; padding: 2px;">Año</th>
</tr>

  <tr>
    <td style="text-align: left; height: 5px; font-size: 7px; border: 1px solid black; padding: 5px;" class="custom-height">'.$periodo5[0]['nombre_curso'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo5[0]['calificacion'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo5[0]['calificacion_letras'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo5[0]['te'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo5[0]['mes'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo5[0]['anio'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo5[0]['numero_plantel'].'</td>
  </tr>
  <tr>
    <td style="text-align: left; height: 5px; font-size: 7px; border: 1px solid black; padding: 5px;" class="custom-height">'.$periodo5[1]['nombre_curso'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo5[1]['calificacion'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo5[1]['calificacion_letras'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo5[1]['te'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo5[1]['mes'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo5[1]['anio'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo5[1]['numero_plantel'].'</td>
  </tr>
  <tr>
    <td style="text-align: left; height: 5px; font-size: 7px; border: 1px solid black; padding: 5px;" class="custom-height">'.$periodo5[2]['nombre_curso'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo5[2]['calificacion'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo5[2]['calificacion_letras'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo5[2]['te'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo5[2]['mes'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo5[2]['anio'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo5[2]['numero_plantel'].'</td>
  </tr>
  <tr>
    <td style="text-align: left; height: 5px; font-size: 7px; border: 1px solid black; padding: 5px;" class="custom-height">'.$periodo5[3]['nombre_curso'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo5[3]['calificacion'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo5[3]['calificacion_letras'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo5[3]['te'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo5[3]['mes'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo5[3]['anio'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo5[3]['numero_plantel'].'</td>
  </tr>

  <!-- Aprobado / No Aprobado -->
 <tr>
    <td style="text-align: left;  font-size: 7px; border: 1px solid black; vertical-align: middle; padding: 5px;" rowspan="2">'.$nomb5.'</td>
    <td colspan="2" style="width: 65px; height: 5px; font-size: 7px; border: 1px solid black; text-align: left; vertical-align: middle; padding: 5px;">APROBADO</td>
   <td style="text-align: center;">'.($califica5 === 'APROBADO' ? htmlspecialchars($te5) : '***').'</td>
            <td style="text-align: center;">'.($califica5 === 'APROBADO' ? htmlspecialchars($mesx5) : '***').'</td>
            <td style="text-align: center;">'.($califica5 === 'APROBADO' ? htmlspecialchars($anio5) : '***').'</td>
            <td style="text-align: center;">'.($califica5 === 'APROBADO' ? htmlspecialchars($numero_plantel5) : '***').'</td>
  </tr>
  <tr>
    <td colspan="2" style="width: 65px; height: 5px; font-size: 7px; border: 1px solid black; text-align: left; vertical-align: middle; padding: 5px;">NO APROBADO</td>
     <td style="text-align: center;">'.($califica5 !== 'APROBADO' ? htmlspecialchars($te5) : '***').'</td>
            <td style="text-align: center;">'.($califica5 !== 'APROBADO' ? htmlspecialchars($mesx5) : '***').'</td>
            <td style="text-align: center;">'.($califica5 !== 'APROBADO' ? htmlspecialchars($anio5) : '***').'</td>
            <td style="text-align: center;">'.($califica5 !== 'APROBADO' ? htmlspecialchars($numero_plantel5) : '***').'</td>
  </tr>
</table>';



$pdf->writeHTML($html, true, false, true, false, ''); // Escribe la tabla en el PDF




// Colspan debajo de ambas tablas
$pdf->SetXY(7.5, 170.6); // Ajustar la posición del colspan
$colspanTable = '
<table border="1" cellpadding="5" style=" font-size: 3px; width: 52.8%;">
    <tr>
       <td colspan="8" style="text-align: center; font-size: 1px;">
            <span style="font-size: 8px; font-weight: bold; "></span>
        </td>
    </tr>
</table>';
$pdf->writeHTMLCell(190, '', '', '', $colspanTable, 0, 1, false, true, '');





// PERIODO 6º

$pdf->SetFont('helvetica', 'B', 8); // Fuente y tamaño del texto

$pdf->SetXY(111.3, 130); // Ajusta la posición del texto
$pdf->Cell(60, 87.5, 'PERIODO:'.$periodo6[0]['nombre_periodo'], 0, 0, 'L'); // Agrega el texto



$pdf->SetFont('helvetica', 'B', 6 ); // Fuente y tamaño del texto
$pdf->StartTransform(); // Inicia la transformación
$pdf->Rotate(90, 100, 90); // Rota el texto -90 grados hacia la izquierda en el punto (100, 90)
$pdf->SetXY(5.7, 193); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'Plantel', 0, 0, 'L'); // Agrega el texto
$pdf->StopTransform(); // Finaliza la transformación
$pdf->SetFont('helvetica', 'B', 8); // Restaurar el tamaño original de la fuente



$pdf->SetFont('helvetica', 'B', 8 ); // Fuente y tamaño del texto
$pdf->SetXY(183.6, 175.8); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'T-E', 0, 0, 'L'); // Agrega el texto



// Texto AREAS DE FORMACION
$pdf->SetFont('helvetica', 'B', 8 ); // Fuente y tamaño del texto
$pdf->SetXY(118, 175.5); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'AREAS DE FORMACION', 0, 0, 'L'); // Agrega el texto






$pdf->SetXY(110.5, 176.2); // Posicionar esta tabla hacia la izquierda
// Añadir un espaciado para bajar el contenido

//prueba6
$html = '
<table border="1" cellpadding="1" style="width: 100%; float: left; border-collapse: collapse;">


  <tr>
    <th rowspan="2" style="width: 140px; vertical-align: middle; font-size: 8px; border: 1px solid black; text-align: center; padding: 2px;"> </th>
    <th colspan="2" style="width: 65px; height: 13px; border: 1px solid black; text-align: center; vertical-align: middle; padding: 2px;"><b>Calificación</b></th>
    <th rowspan="2" style="width: 18px; vertical-align: middle; border: 1px solid black; text-align: center; padding: 2px;"> </th>
    <th colspan="2" style="width: 45px; border: 1px solid black; text-align: center; padding: 2px;"><b>Fecha</b></th>
    <th rowspan="2" style="width: 16.4px;  text-align: center; border: 1px solid black; padding: 2px;"><b></b></th>
</tr>
<tr>
    <th style="width: 20px; border: 1px solid black; text-align: center; vertical-align: middle; padding: 2px;">N°</th>
    <th style="width: 45px; border: 1px solid black; text-align: center; vertical-align: middle; padding: 2px;">Letras</th>
    <th style="width: 22px; border: 1px solid black; text-align: center; vertical-align: middle; padding: 2px;">Mes</th>
    <th style="width: 23px; border: 1px solid black; text-align: center; vertical-align: middle; padding: 2px;">Año</th>
</tr>

  <tr>
    <td style="text-align: left; height: 5px; font-size: 7px; border: 1px solid black; padding: 5px;" class="custom-height">'.$periodo6[0]['nombre_curso'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo6[0]['calificacion'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo6[0]['calificacion_letras'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo6[0]['te'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo6[0]['mes'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo6[0]['anio'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo6[0]['numero_plantel'].'</td>
  </tr>
  <tr>
       <td style="text-align: left; height: 5px; font-size: 7px; border: 1px solid black; padding: 5px;" class="custom-height">'.$periodo6[1]['nombre_curso'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo6[1]['calificacion'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo6[1]['calificacion_letras'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo6[1]['te'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo6[1]['mes'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo6[1]['anio'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo6[1]['numero_plantel'].'</td>
  </tr>
  <tr>
        <td style="text-align: left; height: 5px; font-size: 7px; border: 1px solid black; padding: 5px;" class="custom-height">'.$periodo6[2]['nombre_curso'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo6[2]['calificacion'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo6[2]['calificacion_letras'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo6[2]['te'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo6[2]['mes'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo6[2]['anio'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo6[2]['numero_plantel'].'</td>
  </tr>
  <tr>
        <td style="text-align: left; height: 5px; font-size: 7px; border: 1px solid black; padding: 5px;" class="custom-height">'.$periodo6[3]['nombre_curso'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo6[3]['calificacion'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo6[3]['calificacion_letras'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo6[3]['te'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo6[3]['mes'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo6[3]['anio'].'</td>
    <td style="border: 1px solid black; text-align: center; padding: 5px;">'.$periodo6[3]['numero_plantel'].'</td>
  </tr>

  <!-- Aprobado / No Aprobado -->
  <tr>
    <td style="text-align: left;  font-size: 7px; border: 1px solid black; vertical-align: middle; padding: 5px;" rowspan="2">'.$nomb6.'</td>
    <td colspan="2" style="width: 65px; height: 5px; font-size: 7px; border: 1px solid black; text-align: left; vertical-align: middle; padding: 5px;">APROBADO</td>
   <td style="text-align: center;">'.($califica6 === 'APROBADO' ? htmlspecialchars($te6) : '***').'</td>
            <td style="text-align: center;">'.($califica6 === 'APROBADO' ? htmlspecialchars($mesx6) : '***').'</td>
            <td style="text-align: center;">'.($califica6 === 'APROBADO' ? htmlspecialchars($anio6) : '***').'</td>
            <td style="text-align: center;">'.($califica6 === 'APROBADO' ? htmlspecialchars($numero_plantel6) : '***').'</td>
  </tr>
  <tr>
    <td colspan="2" style="width: 65px; height: 5px; font-size: 7px; border: 1px solid black; text-align: left; vertical-align: middle; padding: 5px;">NO APROBADO</td>
     <td style="text-align: center;">'.($califica6 !== 'APROBADO' ? htmlspecialchars($te6) : '***').'</td>
            <td style="text-align: center;">'.($califica6 !== 'APROBADO' ? htmlspecialchars($mesx6) : '***').'</td>
            <td style="text-align: center;">'.($califica6 !== 'APROBADO' ? htmlspecialchars($anio6) : '***').'</td>
            <td style="text-align: center;">'.($califica6 !== 'APROBADO' ? htmlspecialchars($numero_plantel6) : '***').'</td>
  </tr>
</table>';



$pdf->writeHTML($html, true, false, true, false, ''); // Escribe la tabla en el PDF





// Colspan debajo de ambas tablas
$pdf->SetXY(110.5, 170.6); // Ajustar la posición del colspan
$colspanTable = '
<table border="1" cellpadding="5" style=" font-size: 3px; width: 52.8%;">
    <tr>
       <td colspan="8" style="text-align: center; font-size: 1px;">
            <span style="font-size: 8px; font-weight: bold; "></span>
        </td>
    </tr>
</table>';
$pdf->writeHTMLCell(190, '', '', '', $colspanTable, 0, 1, false, true, '');





// Texto 

$pdf->SetFont('helvetica', 'B', 8 ); // Fuente y tamaño del texto
$pdf->SetXY(23, 215.4); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'IDIOMAS', 0, 0, 'L'); // Agrega el texto


$pdf->SetFont('helvetica', 'B', 8 ); // Fuente y tamaño del texto
$pdf->SetXY(52, 215.4); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'APROBADO', 0, 0, 'L'); // Agrega el texto



$pdf->SetFont('helvetica', 'B', 8 ); // Fuente y tamaño del texto
$pdf->SetXY(92.5, 215.4); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'PERIODO', 0, 0, 'L'); // Agrega el texto




$pdf->SetXY(7.5, 210.5); // Posicionar esta tabla hacia la izquierda
// Añadir un espaciado para bajar el contenido

//prueba7


// Por ejemplo para la posición 5


  
$html = '

<table border="1" cellpadding="2" cellspacing="0" style="font-size:8px; width: 51.8%; border-collapse: collapse; text-align: center;">
   
<tr>
        <td colspan="5" style="font-weight: bold;">COMPONENTE DE IDIOMA</td>
    </tr>
    <tr>
        <td rowspan="2" style="font-weight: bold; width: 43%;"></td>
        <td rowspan="2" style="font-weight: bold; width: 20%;"> </td>
        <td colspan="2" style="font-weight: bold; width: 20%;">Fecha</td>
        <td rowspan="2" style="font-weight: bold; width: 17%;"> </td>
    </tr>
    <tr>
        <td style="font-weight: bold; width: 10%;">Mes</td>
        <td style="font-weight: bold; width: 10%;">Año</td>
    </tr>
    <tr>
        <td style="font-size:7px; ">'.$materia['nombre_curso'] .'</td>
        <td>'.$materia['calificacion_letras'] .'</td>
        <td>'.$materia['mes'] .'</td>
        <td>'.$materia['anio'] .'</td>
        <td>'.$materia['numero_periodo'] .'</td>
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


// Añadir el texto "VI. Observaciones:" encima de la línea
$pdf->SetFont('helvetica', 'B', 8); // Fuente normal y tamaño 8
$pdf->SetXY(7.5, 237.9); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, 'VI. Observaciones:', 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha

// Dibujar una línea al lado derecho del texto
$pdf->SetLineWidth(0.3); // Grosor de la línea
$pdf->Line(33, 244.8, 210.8, 244.8); // Coordenadas de inicio y fin de la línea (X1, Y1, X2, Y2)
// Y1 y y2 30 SE Y 30 SE AUMENTA PARA BAJAR Y SE DISMINUYE PARA SUBIR)
// x1 se disminuye para mover hacia la izquierda y x2  y disminuye para mover hacia la izquierda y se aumenta para ir hacia hacia la derecha
















//TEXTO


$pdf->SetFont('helvetica', 'B', 8 ); // Fuente y tamaño del texto
$pdf->SetXY(127, 215.4); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'OFICIO', 0, 0, 'L'); // Agrega el texto



$pdf->SetFont('helvetica', 'B', 8 ); // Fuente y tamaño del texto
$pdf->SetXY(155.3, 215.4); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'APROBADO', 0, 0, 'L'); // Agrega el texto



$pdf->SetFont('helvetica', 'B', 8 ); // Fuente y tamaño del texto
$pdf->SetXY(195.8, 215.4); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'PERIODO', 0, 0, 'L'); // Agrega el texto




$pdf->SetXY(110.5, 210.5); // Posicionar esta tabla hacia la izquierda
// Añadir un espaciado para bajar el contenido

//prueba8


$html2 = '
<table border="1" cellpadding="2" cellspacing="0" style="font-size:8px; width: 110.9%; border-collapse: collapse; text-align: center;">
    <tr>
        <td colspan="5" style="font-weight: bold;">COMPONENTE DE FORMACIÒN LABORAL</td>
    </tr>
    <tr>
        <td rowspan="2" style="font-weight: bold; width: 43%;"> </td>
        <td rowspan="2" style="font-weight: bold; width: 20%;"> </td>
        <td colspan="2" style="font-weight: bold; width: 20%;">Fecha</td>
        <td rowspan="2" style="font-weight: bold; width: 17%;"> </td>
    </tr>
    <tr>
        <td style="font-weight: bold; width: 10%;">Mes</td>
        <td style="font-weight: bold; width: 10%;">Año</td>
    </tr>
';
  
 
   foreach ($materiasOrdenadas as $materia) {
    $html2 .= '
       <tr >
            <td style="font-size:7px; ">'.$materia2['nombre_curso'] .'</td>
        <td>'.$materia2['calificacion_letras'] .'</td>
        <td>'.$materia2['mes'] .'</td>
        <td>'.$materia2['anio'] .'</td>
        <td>'.$materia2['numero_periodo'] .'</td>
    </tr>
    <tr>
        <td style="font-size:7px;">' . htmlspecialchars($materia['nombre_curso']) . '</td>
        <td>' . htmlspecialchars($materia['calificacion_letras']) . '</td>
        <td>' . htmlspecialchars($materia['mes']) . '</td>
        <td>' . htmlspecialchars($materia['anio']) . '</td>
        <td>' . htmlspecialchars($materia['numero_periodo']) . '</td>
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



//<tr>
//<td colspan="5" style="font-weight: bold; height: 60px;">COMPONENTE DE IDIOMA</td>
//</tr>

//SELLOS 

$pdf->SetFont('helvetica', 'B', 8 ); // Fuente y tamaño del texto
$pdf->SetXY(71.7, 275); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'SELLO DE PLANTEL', 0, 0, 'L'); // Agrega el texto


$pdf->SetFont('helvetica', 'B', 8 ); // Fuente y tamaño del texto
$pdf->SetXY(174.5, 275); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'SELLO DE LA ZONA', 0, 0, 'L'); // Agrega el texto


//texto

$pdf->SetFont('helvetica', 'B', 8 ); // Fuente y tamaño del texto
$pdf->SetXY(8, 258.3); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'IX. Plantel:', 0, 0, 'L'); // Agrega el texto


$pdf->SetFont('helvetica', 'B', 8 ); // Fuente y tamaño del texto
$pdf->SetXY(8, 262.2); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'Director(a)', 0, 0, 'L'); // Agrega el texto


$pdf->SetFont('helvetica', 'B', 8 ); // Fuente y tamaño del texto
$pdf->SetXY(8, 266); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'Apellidos y Nombres', 0, 0, 'L'); // Agrega el texto


$pdf->SetFont('helvetica', 'B', 8 ); // Fuente y tamaño del texto
$pdf->SetXY(23, 270.3); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'VILORIA JHONNY', 0, 0, 'L'); // Agrega el texto



$pdf->SetFont('helvetica', 'B', 8 ); // Fuente y tamaño del texto
$pdf->SetXY(8, 274.2); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'Nùmero de C.I:', 0, 0, 'L'); // Agrega el texto


$pdf->SetFont('helvetica', 'B', 8 ); // Fuente y tamaño del texto
$pdf->SetXY(26, 278.4); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'V-14206691', 0, 0, 'L'); // Agrega el texto

$pdf->SetFont('helvetica', 'B', 8 ); // Fuente y tamaño del texto
$pdf->SetXY(8, 282.4); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'Firma:', 0, 0, 'L'); // Agrega el texto


$pdf->SetFont('helvetica', 'B', 6 ); // Fuente y tamaño del texto
$pdf->SetXY(8, 291.1); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'PARA EFECTOS DE SU VALIDEZ A NIVEL NACIONAL', 0, 0, 'L'); // Agrega el texto

//texto de colspan izquierda mayor
$pdf->SetFont('helvetica', 'B', 6 ); // Fuente y tamaño del texto
$pdf->SetXY(8, 244.7); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'VII. Escala de calificación:
Uno (1): logros muy escasos. El estudiante deberá cursar el área correspondiente.
', 0, 0, 'L'); // Agrega el texto
$pdf->SetXY(8, 247.1); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'Dos (2): logros insuficientes. Deben realizarse actividades complementarias para alcanzar el mínimo aprobatorio.
', 0, 0, 'L'); // Agrega el texto
$pdf->SetXY(8, 249.6); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'Tres (3): logros suficientes. Calificación mínima aprobatoria.
', 0, 0, 'L'); // Agrega el texto
$pdf->SetXY(8, 252.1); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'Cuatro (4): logros mayores que los establecidos en la mayoría de los criterios del programa del área.
', 0, 0, 'L'); // Agrega el texto
$pdf->SetXY(8, 254.5); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'Cinco (5): logros altos. Muy superiores a los establecidos en todos los criterios del programa del área.
', 0, 0, 'L'); // Agrega el texto


//texto colpan derecha 
$pdf->SetFont('helvetica', 'B', 6 ); // Fuente y tamaño del texto
$pdf->SetXY(146, 244.7); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'VIII. Escala de conversión 1 al 20:
Uno (1) equivale a Cuatro (4)', 0, 0, 'L'); // Agrega el texto
$pdf->SetXY(146, 247.1); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'Dos (2) equivale a Ocho (8)
', 0, 0, 'L'); // Agrega el texto
$pdf->SetXY(146, 249.6); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'Tres (3) equivale a Doce (12)
', 0, 0, 'L'); // Agrega el texto
$pdf->SetXY(146, 252.1); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'Cuatro (4) equivale a Dieciséis (16)
', 0, 0, 'L'); // Agrega el texto
$pdf->SetXY(146, 254.5); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'Cinco (5) equivale a Veinte (20)
', 0, 0, 'L'); // Agrega el texto








$pdf->SetXY(7.5, 261.5); // Posicionar esta tabla hacia la izquierda
// Añadir un espaciado para bajar el contenido

//tablafinal1

$html = '

<table border="1" cellpadding="1" cellspacing="0" style="font-size:8px; width: 50%; border-collapse: collapse; text-align: center; line-height: 0.7;">
  
<tr><td style="text-align: left; width:57.6%; height: 11px; font-size: 8px; "></td></tr>
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

$pdf->SetFont('helvetica', 'B', 7 ); // Fuente y tamaño del texto
$pdf->SetXY(7.5, 295); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'TIMBRE FISCAL: Este documento no tiene validez si no se le colocan en la parte posterior timbres fiscales por Bs. 30% de la U.T. ', 0, 0, 'L'); // Agrega el texto


// Colspan 
$pdf->SetXY(7.5, 246.5); // Ajustar la posición del colspan
$colspanTable = '
<table border="1" cellpadding="5" style=" font-size: 3px; width: 107%;">
    <tr>
       <td colspan="8" style="text-align: center; height: 40px; font-size: 1px;">
            <span style="font-size: 8px; font-weight: bold; "> </span>
        </td>
    </tr>
</table>';
$pdf->writeHTMLCell(190, '', '', '', $colspanTable, 0, 1, false, true, '');

// Colspan sello 
$pdf->SetXY(63.2, 260.6); // Ajustar la posición del colspan
$colspanTable = '
<table border="1" cellpadding="5" style=" font-size: 3px; width: 23.6%;">
    <tr>
       <td colspan="8" style="text-align: center; height: 104.2px; font-size: 1px;">
            <span style="font-size: 8px; font-weight: bold; "></span>
        </td>
    </tr>
</table>';
$pdf->writeHTMLCell(190, '', '', '', $colspanTable, 0, 1, false, true, '');








//texto

$pdf->SetFont('helvetica', 'B', 8 ); // Fuente y tamaño del texto
$pdf->SetXY(111.1, 258.3); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'X. Zona Educativa:', 0, 0, 'L'); // Agrega el texto


$pdf->SetFont('helvetica', 'B', 8 ); // Fuente y tamaño del texto
$pdf->SetXY(111.1, 262.2); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'Director(a)', 0, 0, 'L'); // Agrega el texto


$pdf->SetFont('helvetica', 'B', 8 ); // Fuente y tamaño del texto
$pdf->SetXY(111.1, 266); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'Apellidos y Nombres', 0, 0, 'L'); // Agrega el texto




$pdf->SetFont('helvetica', 'B', 8 ); // Fuente y tamaño del texto
$pdf->SetXY(111.1, 274.2); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'Nùmero de C.I:', 0, 0, 'L'); // Agrega el texto



$pdf->SetFont('helvetica', 'B', 8 ); // Fuente y tamaño del texto
$pdf->SetXY(111.1, 282.4); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'Firma:', 0, 0, 'L'); // Agrega el texto


$pdf->SetFont('helvetica', 'B', 6 ); // Fuente y tamaño del texto
$pdf->SetXY(111.1, 289.8); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'PARA EFECTOS DE SU VALIDEZ A NIVEL ', 0, 0, 'L'); // Agrega el texto

$pdf->SetFont('helvetica', 'B', 6 ); // Fuente y tamaño del texto
$pdf->SetXY(111.1, 291.9); // Ajusta la posición del texto
$pdf->Cell(60, 10, 'INTERNACIONAL', 0, 0, 'L'); // Agrega el texto



$pdf->SetXY(110.6, 261.5); // Posicionar esta tabla hacia la izquierda
// Añadir un espaciado para bajar el contenido

//tablafinal1

$html = '

<table border="1" cellpadding="1" cellspacing="0" style="font-size:8px; width: 107.5%; border-collapse: collapse; text-align: center; line-height: 0.7;">
  
<tr><td style="text-align: left; width:57%; height: 11px; font-size: 8px; "> </td></tr>
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
$pdf->SetXY(166, 260.6); // Ajustar la posición del colspan
$colspanTable = '
<table border="1" cellpadding="5" style=" font-size: 3px; width: 23.6%;">
    <tr>
       <td colspan="8" style="text-align: center; height: 104px; font-size: 1px;">
            <span style="font-size: 8px; font-weight: bold; "></span>
        </td>
    </tr>
</table>';
$pdf->writeHTMLCell(190, '', '', '', $colspanTable, 0, 1, false, true, '');






// Salida del PDF
$pdf->Output('certificacion_calificaciones.pdf', 'I');
echo '<script>window.onload = function() { window.print(); }</script>';
?>