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
    $sql = "SELECT 
        estudiantes.id_estudiante, estudiantes.cedula, estudiantes.nombres, estudiantes.apellidos, estudiantes.edad, 
        estudiantes.sexo, estudiantes.fecha_nacimiento, estudiantes.direccion, estudiantes.tlf_estudiante, 
        estudiantes.correo, estudiantes.foto, estudiantes.lugar_nacimiento, estudiantes.entidad_federal, estudiantes.fecha, estudiantes.estado, 
        seccion.nombre_seccion, 
        periodos.id_periodo, periodos.numero_periodo, periodos.nombre_periodo,
        calificaciones.id_calificacion, 
        asistencias.asistencia, 
        profesores.id_profesor, profesores.nombres AS nombre_profesor, profesores.apellidos AS apellido_profesor,
        asignaturas.id_asignatura, asignaturas.nombre_asignatura
    FROM estudiantes
    LEFT JOIN seccion ON estudiantes.id_seccion = seccion.id_seccion
    LEFT JOIN periodos ON seccion.id_periodo = periodos.id_periodo
    LEFT JOIN calificaciones ON estudiantes.id_estudiante = calificaciones.id_estudiante
    LEFT JOIN asistencias ON estudiantes.id_estudiante = asistencias.id_estudiante
    LEFT JOIN asignaturas ON calificaciones.id_asignatura = asignaturas.id_asignatura
    LEFT JOIN profesores ON asignaturas.id_profesor = profesores.id_profesor
    WHERE seccion.id_seccion = ? 
    AND periodos.id_periodo = ? 
    ORDER BY estudiantes.id_estudiante ASC 
    LIMIT 30";

    // Preparar la consulta
    if ($stmt = $conexion->prepare($sql)) {
        $stmt->bind_param("ii", $id_seccion, $id_periodo);
        $stmt->execute();
        $resultado = $stmt->get_result();

        // Inicializar el arreglo de datos
        $rows = [];

        while ($row = $resultado->fetch_assoc()) {
            // Separar la fecha de nacimiento en día, mes y año
            $fecha_nacimiento = explode("-", $row['fecha_nacimiento']);
            $dia = isset($fecha_nacimiento[0]) ? $fecha_nacimiento[0] : '00';
            $mes = isset($fecha_nacimiento[1]) ? $fecha_nacimiento[1] : '00';
            $anio = isset($fecha_nacimiento[2]) ? $fecha_nacimiento[2] : '0000';

            $rows[] = [
                str_pad(count($rows) + 1, 2, '0', STR_PAD_LEFT), // N°
                $row['cedula'], // Cédula de Identidad
                $row['apellidos'], // Apellidos
                $row['nombres'], // Nombres
                $row['lugar_nacimiento'], // Lugar de Nacimiento
                $row['entidad_federal'], // EF
                $row['sexo'], // Sexo
                $dia, // Día
                $mes, // Mes
                $anio, // Año
                $row['nombre_seccion'], // Sección
                $row['nombre_periodo'], // Período
                $row['nombre_asignatura'], // Asignatura
                $row['nombre_profesor'] . ' ' . $row['apellido_profesor'], // Profesor
                $row['id_calificacion'], // Calificación
                $row['asistencia'] // Asistencia
            ];
        }

        $stmt->close();
    } else {
        echo "Error en la consulta SQL: " . $conexion->error;
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
$pdf->Image($image_file, 6.5, 13, $new_width, $new_height, '', '', '', false, 300, '', false, false, 0, false, false, false);



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
$pdf->Cell(0, 10, '31058', 0, 0, 'L'); // Agregar el texto
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
$pdf->SetFont('helvetica', '', 8); // Fuente normal y tamaño 8
$pdf->SetXY(136, 27.2); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, 'Ingresar', 0, 0, 'L'); // Agregar el texto
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
$pdf->SetFont('helvetica', '', 8); // Fuente normal y tamaño 8
$pdf->SetXY(185, 20.5); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, 'Ingresar', 0, 0, 'L'); // Agregar el texto
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
$pdf->SetFont('helvetica', '', 8); // Fuente normal y tamaño 8
$pdf->SetXY(185, 27.2); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, 'Ingresar', 0, 0, 'L'); // Agregar el texto
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
$pdf->Cell(60, 10, 'Nro de cèdula del estudiante', 0, 0, 'L'); // Agregar el texto
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
$pdf->Cell(60, 10, 'Formato de fecha', 0, 0, 'L'); // Agregar el texto
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
  $tbl = '<table border="1">';
    $tbl .= '<tr>
                <th>N°</th>
                <th>Cédula</th>
                <th>Apellidos</th>
                <th>Nombres</th>
                <th>Lugar de Nacimiento</th>
                <th>EF</th>
                <th>Sexo</th>
                <th>Día</th>
                <th>Mes</th>
                <th>Año</th>
                <th>Sección</th>
                <th>Período</th>
                <th>Asignatura</th>
                <th>Profesor</th>
                <th>Calificación</th>
                <th>Asistencia</th>
            </tr>';

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

exit;



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



$pdf->SetFont('helvetica', 'B', 9); // Fuente normal y tamaño 10
$pdf->SetXY(106, 208.3); // Ajustar la posición horizontal (X) y vertical (Y)
$pdf->Cell(50, 10, 'Inscritos', 0, 0, 'L'); // Agregar el texto
// DISMINUIR X HACIA LA IZQUIERDA AUMENTAR HACIA LA DERECHA

$pdf->SetFont('helvetica', 'B', 9); // Fuente normal y tamaño 10
$pdf->SetXY(104, 212.4); // Ajustar la posición horizontal (X) y vertical (Y)
$pdf->Cell(50, 10, 'Inasistentes', 0, 0, 'L'); // Agregar el texto
// DISMINUIR X HACIA LA IZQUIERDA AUMENTAR HACIA LA DERECHA

$pdf->SetFont('helvetica', 'B', 9); // Fuente normal y tamaño 10
$pdf->SetXY(105, 216.7); // Ajustar la posición horizontal (X) y vertical (Y)
$pdf->Cell(50, 10, 'Aprobados', 0, 0, 'L'); // Agregar el texto
// DISMINUIR X HACIA LA IZQUIERDA AUMENTAR HACIA LA DERECHA



$pdf->SetFont('helvetica', 'B', 9); // Fuente normal y tamaño 10
$pdf->SetXY(103, 220.9); // Ajustar la posición horizontal (X) y vertical (Y)
$pdf->Cell(50, 10, 'No Aprobados', 0, 0, 'L'); // Agregar el texto
// DISMINUIR X HACIA LA IZQUIERDA AUMENTAR HACIA LA DERECHA



$pdf->SetFont('helvetica', 'B', 10); // Fuente normal y tamaño 10
$pdf->SetXY(30, 214.5); // Ajustar la posición horizontal (X) y vertical (Y)
$pdf->Cell(50, 10, 'Totales', 0, 0, 'L'); // Agregar el texto
// DISMINUIR X HACIA LA IZQUIERDA AUMENTAR HACIA LA DERECHA




//tabla totales
$pdf->SetXY(69.9, 211.3); // Posicionar esta tabla hacia la izquierda

$html = '
<table border="1" cellpadding="1" cellspacing="0" style="font-size:8px; width: 100.1%; border-collapse: collapse; text-align: center;">
      <tr>
        <td style=" width: 66.5%; "></td>
        <td style="width: 4.84%; ">*</td>
        <td style="width: 4.84%; ">*</td>
        <td style="width: 4.84%; ">*</td>
        <td style="width: 4.84%; ">*</td>
        <td style="width: 8.05%; ">*</td>
        <td style="width: 5.4%; ">*</td>
        <td style="width: 8.05%; ">*</td>
    </tr>
    <tr>
        <td style=" width: 66.5%; "></td>
        <td style="width: 4.84%; ">*</td>
        <td style="width: 4.84%; ">*</td>
        <td style="width: 4.84%; ">*</td>
        <td style="width: 4.84%; ">*</td>
        <td style="width: 8.05%; ">*</td>
        <td style="width: 5.4%; ">*</td>
        <td style="width: 8.05%; ">*</td>
    </tr>
    <tr>
        <td style="width: 66.5%; "></td>
        <td style="width: 4.84%; ">*</td>
        <td style="width: 4.84%; ">*</td>
        <td style="width: 4.84%; ">*</td>
        <td style="width: 4.84%; ">*</td>
        <td style="width: 8.05%; ">*</td>
        <td style="width: 5.4%;">*</td>
        <td style="width: 8.05%; ">*</td>
    </tr>
    <tr>
        <td style="width: 66.5%; height: 1px;"></td>
        <td style="width: 4.84%; height: 1px;">*</td>
        <td style="width: 4.84%; height: 1px;">*</td>
        <td style="width: 4.84%; height: 1px;">*</td>
        <td style="width: 4.84%; height: 1px;">*</td>
        <td style="width: 8.05%; height: 1px;">*</td>
        <td style="width: 5.4%; height: 1px;">*</td>
        <td style="width: 8.05%; height: 1px;">*</td>
    </tr>
</table>
';

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
$pdf->Cell(60, 10, 'LENGUA CULTURA Y COMUNICACIÒN', 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha



$pdf->SetFont('helvetica', 'B', 7); // Fuente normal y tamaño 8
$pdf->SetXY(7.5, 243.2); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, 'MATEMÀTICA', 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha


$pdf->SetFont('helvetica', 'B', 7); // Fuente normal y tamaño 8
$pdf->SetXY(7.5, 246.6); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, 'MEMORIA TERRITORIO Y CIUDADANIA', 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha


$pdf->SetFont('helvetica', 'B', 7); // Fuente normal y tamaño 8
$pdf->SetXY(7.5, 249.9); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, 'CIENCIAS NATURALES', 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha



$pdf->SetFont('helvetica', 'B', 7); // Fuente normal y tamaño 8
$pdf->SetXY(7.5, 253.2); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, 'COMPONENTE DE PART. E INTEG.', 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha




$pdf->SetFont('helvetica', 'B', 7); // Fuente normal y tamaño 8
$pdf->SetXY(7.5, 256.5); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, 'COMPONENTE DE IDIOMAS', 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha



$pdf->SetFont('helvetica', 'B', 7); // Fuente normal y tamaño 8
$pdf->SetXY(7.5, 259.9); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, 'COMPONENTE DE FORMACIÒN LABORAL', 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha



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
$pdf->SetXY(165, 224.5); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, 'VI. Identificaciòn del Curso', 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha



$pdf->SetFont('helvetica', 'B', 7); // Fuente normal y tamaño 8
$pdf->SetXY(169, 227.1); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, 'PLAN DE ESTUDIO:', 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha




$pdf->SetFont('helvetica', 'B', 5); // Fuente normal y tamaño 8
$pdf->SetXY(152, 229.6); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, 'EDUCACIÒN MEDIA GENERAL DE JOVENES ADULTOS Y ADULTAS', 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha


$pdf->SetFont('helvetica', 'B', 7); // Fuente normal y tamaño 8
$pdf->SetXY(175, 232); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, 'CODIGO:', 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha

 

$pdf->SetFont('helvetica', 'B', 7); // Fuente normal y tamaño 8
$pdf->SetXY(177, 234.6); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, '31058', 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha




$pdf->SetFont('helvetica', 'B', 7); // Fuente normal y tamaño 8
$pdf->SetXY(164.7, 237.3); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, 'ESTRATEGIA DE ESTUDIO:', 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha




$pdf->SetFont('helvetica', 'B', 7); // Fuente normal y tamaño 8
$pdf->SetXY(164.7, 239.8); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, '#', 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha


$pdf->SetFont('helvetica', 'B', 7); // Fuente normal y tamaño 8
$pdf->SetXY(175, 242.5); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, 'PERIODO:', 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha




$pdf->SetFont('helvetica', 'B', 7); // Fuente normal y tamaño 8
$pdf->SetXY(164.7, 244.7); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, '#', 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha



$pdf->SetFont('helvetica', 'B', 7); // Fuente normal y tamaño 8
$pdf->SetXY(175, 247.5); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, 'SECCION:', 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha




$pdf->SetFont('helvetica', 'B', 7); // Fuente normal y tamaño 8
$pdf->SetXY(164.7, 249.5); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, '#', 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha





$pdf->SetFont('helvetica', 'B', 6); // Fuente normal y tamaño 8
$pdf->SetXY(162, 252.6); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, 'N° DE ESTUDIANTES EN ESTA PÀGINA:', 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha




$pdf->SetFont('helvetica', 'B', 6); // Fuente normal y tamaño 8
$pdf->SetXY(162, 255.3); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, '#', 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha




$pdf->SetFont('helvetica', 'B', 6); // Fuente normal y tamaño 8
$pdf->SetXY(162, 257.8); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, 'N° DE ESTUDIANTES DE LA SECCIÒN:', 0, 0, 'L'); // Agregar el texto
// Disminuir Y para subir y aumentar para bajar
// Disminuir X  para mover a la izquierda y aumentar para mover hacia la derecha



$pdf->SetFont('helvetica', 'B', 6); // Fuente normal y tamaño 8
$pdf->SetXY(162, 260.3); // Ajustar la posición horizontal (X) y vertical (Y) para colocar el texto encima de la línea
$pdf->Cell(60, 10, '#', 0, 0, 'L'); // Agregar el texto
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






// Salida del PDF
$pdf->Output('resumen_curricular.pdf', 'I');
?>