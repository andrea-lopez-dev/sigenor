<?php  
  session_start(); 
  
  // Verificar que la variable id_usuario esté definida en la sesión
  if (!isset($_SESSION['id_usuario'])) {
      header("Location: ../home.php");
      exit();
  }
  $id_usuario = $_SESSION['id_usuario'];

  $conn = new mysqli("localhost", "root", "", "sigenor");
  if ($conn->connect_error) {
      die("Conexión fallida: " . $conn->connect_error);
  }

  $stmt = $conn->prepare("SELECT foto FROM usuarios WHERE id_usuario = ?");
  $stmt->bind_param("i", $id_usuario);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $foto_perfil = $row['foto'];
  } else {
      $foto_perfil = "ruta/a/imagen_por_defecto.jpg";
  }

  $stmt->close();
  $conn->close();

  if (!isset($_SESSION['rol'], $_SESSION['foto']) || $_SESSION['rol'] != 1) {
      $foto = $_SESSION['foto'];
      header('location: ../home.php');
  } else {
      $foto = '../../Assets/img/subidas/user.jpg'; // Ruta de la imagen por defecto en caso de no haber imagen en la sesión
  }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
        <title>SIGENOR</title>
	    <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="http://localhost/sistema_escolar/Assets/css/bootstrap-1.min.css">
	    <!----css3---->
        <link rel="stylesheet" href="http://localhost/sistema_escolar/Assets/css/custom.css">
        <link rel="stylesheet" href="http://localhost/sistema_escolar/Assets/css/card.css">
        <link rel="icon" type="image/png" sizes="96x96" href="../../Assets/img/logo.png">
		<!--google fonts -->
	  
	    <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
	<!--google material icon-->
      <link href="https://fonts.googleapis.com/css2?family=Material+Icons"rel="stylesheet">
       <link href="../../Assets/DataTables/css/datatables.css" rel="stylesheet">
  </head>
  <body>

<style>
    /* Animación de carga simple (círculo girando) */
    .spinnerContainer {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.spinner {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    position: relative;
    border: 6px solid transparent;
    animation: spin 1s infinite linear;
    margin-top:250px;
}

/* Capa con gradiente de múltiples colores */

.spinner::before,
.spinner::after {
    content: "";
    position: absolute;
    inset: 0;
    border-radius: 50%;
    border: 6px solid transparent;
    border-top: 6px solidrgba(0, 255, 128, 0.81); /* Rojo anaranjado */
    border-right: 6px solidrgba(9, 255, 0, 0.79); /* Amarillo */
    border-bottom: 6px solidrgba(77, 255, 41, 0.42); /* Azul */
    border-left: 6px solidrgba(0, 255, 153, 0.6); /* Verde */
    animation: spin 1s infinite linear, colorChange 4s infinite linear;
}

/* Animación de rotación */
@keyframes spin {
    100% {
        transform: rotate(1turn);
    }
}

/* Cambio de colores dinámico */
@keyframes colorChange {
    0% {
        border-top-color: #ff5733;
        border-right-color: #ffc300;
        border-bottom-color: #299fff;
        border-left-color: #00ff99;
    }
    25% {
        border-top-color: #00ff99;
        border-right-color: #ff5733;
        border-bottom-color: #ffc300;
        border-left-color: #299fff;
    }
    50% {
        border-top-color: #299fff;
        border-right-color: #00ff99;
        border-bottom-color: #ff5733;
        border-left-color: #ffc300;
    }
    75% {
        border-top-color: #ffc300;
        border-right-color: #299fff;
        border-bottom-color: #00ff99;
        border-left-color: #ff5733;
    }
    100% {
        border-top-color: #ff5733;
        border-right-color: #ffc300;
        border-bottom-color: #299fff;
        border-left-color: #00ff99;
    }
}




.words {
  overflow: hidden;
}

.word {
  display: block;
  height: 100%;
  width: 100%;
  padding-left: 6px;
  font-weight: bold;
  font-size: 45px;
  background: linear-gradient(45deg, rgb(41, 255, 52), rgb(52, 162, 255), rgb(255, 52, 172));
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  animation: cycle-words 6s infinite;
 
}


@keyframes cycle-words {
  10% {
    -webkit-transform: translateY(-105%);
    transform: translateY(-105%);
  }

  25% {
    -webkit-transform: translateY(-100%);
    transform: translateY(-100%);
  }

  35% {
    -webkit-transform: translateY(-205%);
    transform: translateY(-205%);
  }

  50% {
    -webkit-transform: translateY(-200%);
    transform: translateY(-200%);
  }

  60% {
    -webkit-transform: translateY(-305%);
    transform: translateY(-305%);
  }

  75% {
    -webkit-transform: translateY(-300%);
    transform: translateY(-300%);
  }

  85% {
    -webkit-transform: translateY(-405%);
    transform: translateY(-405%);
  }

  100% {
    -webkit-transform: translateY(-400%);
    transform: translateY(-400%);
  }
}

.loader {
  color: #4a4a4a;
  font-family: "Poppins",sans-serif;
  font-weight: 500;
  font-size: 45px;
  -webkit-box-sizing: content-box;
  box-sizing: content-box;
  height: 70px;
  padding: 10px 10px;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  border-radius: 8px;
  
}
.loader p{
  color: #4a4a4a;
  font-weight: 500;
  font-size: 45px;
  -webkit-box-sizing: content-box;
  box-sizing: content-box;
  height: 70px;
  padding: 10px 10px;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  border-radius: 8px;
    margin-top: 20px; 
      
}



    #contenido {
      display: none;
    }
  </style>
<div id="loader" class="spinnerContainer"> 
  <div class="spinner"></div>
  <div class="loader">
    <p>loading</p>
    <div class="words">
      <span class="word">Periodos</span>
      <span class="word">Secciones</span>
      <span class="word">Estudiantes</span>
      <span class="word">Planteles</span>
      <span class="word">Asignaturas</span>
      <span class="word">Calificaciones</span>
      <span class="word">Asistencias</span>
      <span class="word">Plan administrativo</span>
    </div>
  </div>
</div>

<div id="contenido">
   <div class="wrapper">
   <div class="body-overlay"></div>
   <!-------------------------sidebar------------>
        <!-- Sidebar  -->
   <nav id="sidebar">
       <div class="sidebar-header">
       <h3 style="font-size:20px;" ><img src="../../Assets/img/logo.png" class="img-fluid"/><span>SIGENOR</span></h3>
       </div>
       <ul class="list-unstyled components">
<li class="dropdown">
               <a href="#pageSubmenu3" data-toggle="collapse" aria-expanded="false" 
     class="dropdown-toggle">
     <img src="../../Views/profile/<?php echo ucfirst($_SESSION['foto']); ?> " style="width:40px; height:40px; border-radius:50%;"/>
     <span><?php echo ucfirst($_SESSION['nombre_usuario']); ?></span></a>
               <ul class="collapse list-unstyled menu" id="pageSubmenu3">
                   <li class="active">
                       <a href="../../Views/profile/mostrar.php">Perfil</a>
                   </li>
                   <li>
                       <a href="../../php/index.php">Respaldo</a>
                   </li>
                   <li>
                       <a href="../pages-logout.php">Cerrar sesión</a>
                   </li>
               </ul>
           </li>
       <li  class="active">
 <a href="../admin/pages-admin.php" class="dashboard"><i class="material-icons">dashboard</i>
               <span>Dashboard</span></a>
 </li>
   
 </li>
   
<li class="">
    <a href="../period/mostrar"><i class="material-icons">calendar_month</i><span>Periodos</span></a>
</li>

<li  class="">
    <a href="../users/mostrar"><i class="material-icons">person_outline</i><span>Usuarios

</span></a>
</li>

<li  class="">
    <a href="../fathers/mostrar"><i class="material-icons">apartment</i><span>Planteles

    </span></a>
</li>

<li  class="">
           <a href="../teachers/mostrar"><i class="material-icons">supervised_user_circle</i><span>Profesores

               </span></a>
           </li>

           <li  class="">
               <a href="../students/mostrar"><i class="material-icons">school</i><span>Estudiantes

               </span></a>
           </li>
           <li  class="">
               <a href="../subgrade/mostrar"><i class="material-icons">checklist</i><span>Calificaciones

               </span></a>
           </li>

<li  class="">
    <a href="../careers/mostrar"><i class="material-icons">edit_square</i><span>Asignaturas

    </span></a>
</li>

<li  class="">
    <a href="../grade/mostrar"><i class="material-icons">description</i><span>Plan administrativo

    </span></a>
</li>
<li  class="">
    <a href="../groups/mostrar"><i class="material-icons">groups</i><span>Sección

    </span></a>
</li>

<li  class="">
    <a href="../assists/mostrar"><i class="material-icons">assignment_turned_in</i><span>Asistencias

    </span></a>
</li>

</ul>
</nav>


   
   <!--------page-content---------------->
   
   <div id="content">
      
      <!--top--navbar----design--------->
      
      <div class="top-navbar">
         <div class="xp-topbar">

           <!-- Start XP Row -->
           <div class="row"> 
               <!-- Start XP Col -->
               <div class="col-2 col-md-1 col-lg-1 order-2 order-md-1 align-self-center">
                   <div class="xp-menubar">
                          <span class="material-icons text-white">signal_cellular_alt
                          </span>
                    </div>
               </div> 
               <!-- End XP Col -->

               <!-- End XP Col -->

               <!-- Start XP Col -->
               <div class="col-10 col-md-6 col-lg-8 order-1 order-md-3"  style="margin-left: 281px;">
                   <div class="xp-profilebar text-right">
                        <nav class="navbar p-0">
                   <ul class="nav navbar-nav flex-row ml-auto">   
                       <li class="dropdown nav-item active">
                           <a href="#" class="nav-link" data-toggle="dropdown">
                              <span class="material-icons">notifications</span>
                              <span class="notification">6</span>
                          </a>
                           <ul class="dropdown-menu">
                               <li>
                                   <a href="#">👤 1. REGISTRAR USUARIOS</a>
                               </li>
                               <li>
                                   <a href="#">✅ Ejemplo: Registrar administradores</a>
                               </li>
                               <li>
                                   <a href="#">🔹 Paso 1: Ve al módulo "Usuarios" en el menú principal.</a>
                               </li>
                               <li>
                                   <a href="#">🔹 Paso 2: Haz clic en el botón "Agregar Usuario".</a>
                               </li>
                                <li>
                                   <a href="#">🔹 Paso 3: Rellena los datos: nombre, correo, contraseña y rol.</a>
                               </li>
                                  <li>
                                   <a href="#">🔹 Paso 4: Haz clic en Guardar ✅.</a>
                               </li>
                           </ul>
                       </li>
                      
                       <li class="nav-item dropdown">
                           <a class="nav-link" href="#" data-toggle="dropdown">
                           <img src="../../Views/profile/<?php echo ucfirst ($_SESSION['foto']) ?> " style="width:40px; height:40px; border-radius:50%;"/>
                           <span class="xp-user-live"></span>
                           </a>
                           <ul class="dropdown-menu small-menu">
                               <li>
                                   <a href="../profile/mostrar.php">
                                     <span class="material-icons">
person_outline
</span>Perfil

                                   </a>
                               </li>
                               <li>
                                   <a href="../../php/index.php"><span class="material-icons">
settings
</span>Respaldo</a>
                               </li>
                               <li>
                                   <a href="../pages-logout.php"><span class="material-icons">
logout</span>Cerrar sesión</a>
                               </li>
                           </ul>
                       </li>
                   </ul>
               
          
       </nav>
                       
                   </div>
               </div>
               <!-- End XP Col -->

           </div> 
       <!-- Start XP Col -->
<div class="row mb-3"  style="margin-top: 15px;">
  <div class="col">
    <input type="text" id="searchInput" class="form-control" placeholder="Buscar estudiante..." style="border-radius: 5px;">
  </div>
  <div class="col">
    <select id="periodoSelect" class="form-control" style="border-radius: 5px;">
      <option value="">Todos Periodos</option>
    </select>
  </div>
  <div class="col">
    <select id="seccionSelect" class="form-control" style="border-radius: 5px;">
      <option value="">Todas Secciones</option>
    </select>
  </div>
  <div class="col">
    <button id="searchButton" class="btn btn-primary w-100" style="border-radius: 15px;">Buscar</button>
  </div>
  <div class="col">
    <button id="printButton" class="btn btn-danger w-100" style="border-radius: 15px;" disabled>Imprimir resultados</button>
  </div>
</div>

<div id="customAlert" class="custom-alert" style="display:none;">
  <span class="close-btn" id="closeAlert">×</span>
  <div id="alertBody"><!-- resultados aquí --></div>
</div>


<!-- CSS para estilos personalizados -->
<style>
/* Estilo de la alerta dinámica */
.custom-alert {
   position: fixed;
   top: 50%;
   left: 50%;
   transform: translate(-50%, -50%);
   z-index: 1050; /* Encima de todo */
   background-color: white;
   border: 1px solid #ddd;
   box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
   padding: 20px;
   border-radius: 8px;
   display: none; /* Oculto por defecto */
   max-width: 500px;
   width: 80%; /* Adaptable */
}

/* Botón de cierre */
.custom-alert .close-btn {
   position: absolute;
   top: 10px;
   right: 15px;
   cursor: pointer;
   color: red;
   font-size: 20px;
   font-weight: bold;
}
</style>

<script>
// Referencias a los elementos
const alertElement = document.getElementById('customAlert');
const closeAlertButton = document.getElementById('closeAlert');
const searchButton = document.getElementById('searchButton');
const alertTitle = document.getElementById('alertTitle');
const alertBody = document.getElementById('alertBody');

// Función para mostrar la alerta
function showAlert(title, message, duration = 5000) {
   alertTitle.innerHTML = title;
   alertBody.innerHTML = message;
   alertElement.style.display = 'block';

   // Oculta la alerta automáticamente después de 'duration' ms
   setTimeout(() => {
       hideAlert();
   }, duration);
}

// Función para ocultar la alerta
function hideAlert() {
   alertElement.style.display = 'none';
}

// Evento para cerrar la alerta manualmente
closeAlertButton.addEventListener('click', hideAlert);

// Evento del botón de búsqueda
let ultimoFiltro = {};

document.getElementById('searchButton').onclick = buscar;
document.getElementById('closeAlert').onclick = () => document.getElementById('customAlert').style.display='none';
document.getElementById('printButton').onclick = imprimir;

function mostrarAlerta(html) {
  document.getElementById('alertBody').innerHTML = html;
  document.getElementById('customAlert').style.display = 'block';
}


function buscar() {
  const query = document.getElementById('searchInput').value.trim();
  const periodo = document.getElementById('periodoSelect').value;
  const seccion = document.getElementById('seccionSelect').value;

  if (!query) {
    alert('Ingrese estudiante');
    return;
  }

  if (!periodo) {
    alert('Debe seleccionar un periodo');
    return;
  }

 ultimoFiltro = { busqueda: query, periodo, seccion };
  const body = new URLSearchParams(ultimoFiltro);

  mostrarAlerta(`<div class="text-center">
    <div class="spinner-border text-success" role="status"></div>
    <p>Cargando resultados...</p>
  </div>`);

 fetch('buscar_estudiante_detalles.php', {
    method: 'POST',
    body: body
  })
  .then(r => r.text())
  .then(html => {
    mostrarAlerta(html);
    document.getElementById('printButton').disabled = false;
  })
  .catch(e => mostrarAlerta('<p class="text-danger">Error: ' + e.message + '</p>'));
}

function mostrarAlerta(html) {
  document.getElementById('alertBody').innerHTML = html;
  document.getElementById('customAlert').style.display = 'block';
}
// Función para imprimir resultados

function imprimir() {
  const params = new URLSearchParams(ultimoFiltro).toString();
 window.open('imprimir_resultados.php?' + params, '_blank');

}

// Al cargar, opcionalmente construir selects de periodo y sección
fetch('listar_periodos_secciones.php')
  .then(r => r.json())
  .then(data => {
    data.periodos.forEach(p => {
      const opt = new Option(p.nombre_periodo, p.id_periodo);
      document.getElementById('periodoSelect').add(opt);
    });
    data.secciones.forEach(s => {
      const so = new Option(s.nombre_seccion, s.id_seccion);
      document.getElementById('seccionSelect').add(so);
    });
  });
</script>


           <!-- End XP Row -->

       </div>
        <div class="xp-breadcrumbbar text-center">
           <h4 class="page-title">Bienvenido&nbsp;<?php echo ucfirst($_SESSION['nombre_usuario']); ?></h4>  
              
       </div>
       
      </div>

      <!--------main-content------------->

<div class="main-content">

  <div class="container mt-5">
       <div class="row">
<div class="col-md-3">
 <div class="card-counter primary">
 
   <i class="material-icons">sentiment_very_satisfied</i>
     <?php require '../../Config/config.php'; ?>
    <?php 
   $sql = "SELECT COUNT(*) total FROM estudiantes";
   $result = $connect->query($sql); //$pdo sería el objeto conexión
   $total = $result->fetchColumn();

    ?>
   <span class="count-numbers"><?php echo  $total; ?></span>
   <span class="count-name">Estudiantes</span>
 </div>

 <!-- Estudiantes - Pirámide por sexo -->
</div>








<div class="col-md-3">
 <div class="card-counter danger">
   <i class="material-icons">psychology</i>
    <?php 
   $sql = "SELECT COUNT(*) total FROM profesores";
   $result = $connect->query($sql); //$pdo sería el objeto conexión
   $total = $result->fetchColumn();

    ?>
   <span class="count-numbers"><?php echo  $total; ?></span>
   <span class="count-name">Profesores</span>
 </div>
</div>

<div class="col-md-3">
 <div class="card-counter success">
   <i class="material-icons">supervisor_account</i>
    <?php 
   $sql = "SELECT COUNT(*) total FROM seccion";
   $result = $connect->query($sql); //$pdo sería el objeto conexión
   $total = $result->fetchColumn();

    ?>
   <span class="count-numbers"><?php echo  $total; ?></span>
   <span class="count-name">Secciones</span>
 </div>
</div>

<div class="col-md-3">
 <div class="card-counter info">
   <i class="material-icons">person_outline</i>
    <?php 
   $sql = "SELECT COUNT(*) total FROM usuarios";
   $result = $connect->query($sql); //$pdo sería el objeto conexión
   $total = $result->fetchColumn();

    ?>
   <span class="count-numbers"><?php echo  $total; ?></span>
   <span class="count-name">Usuarios</span>
 </div>
</div>
</div>

<div class="row">

<div class="col-sm-6 mb-3 mb-md-0">
<div class="card">
 <div class="card-body">

    <h5 class="card-title">Genero Estudiantes <a href="../students/mostrar" class="btn btn-success btn-sm" style="margin-left:180px">Ver todos</a></h5>
    <?php 
   $sql = "SELECT sexo, COUNT(*) as total FROM estudiantes GROUP BY sexo";
$stmt = $connect->prepare($sql);
$stmt->execute();

// Inicializa el array con valores cero
$sexoCounts = ['MASCULINO' => 0, 'FEMENINO' => 0];

// Trae todos los resultados como arrays asociativos
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($rows as $row) {
    $sexoCounts[$row['sexo']] = (int)$row['total'];
}


// Ahora $sexoCounts['M'] y $sexoCounts['F'] tienen los conteos correctos

    ?>
    <canvas id="chartEstudiantes"></canvas>



 </div>
</div>
</div>

<div class="col-sm-6">
<div class="card">
 <div class="card-body">
   <h5 class="card-title">Asistencias/Inasistencias <a href="../assists/mostrar" class="btn btn-success btn-sm" style="margin-left:120px">Ver todos</a></h5>

   
   <?php
// Agrupar asistencias e inasistencias por fecha
$sql = "SELECT 
    asistencias.fecha_creacion,
    SUM(asistencias.asistencias) AS total_asistencias,
    SUM(asistencias.inasistencias) AS total_inasistencias
FROM asistencias
GROUP BY asistencias.fecha_creacion
ORDER BY asistencias.fecha_creacion ASC";

$result = $connect->query($sql);

$fechas = [];
$asistencias = [];
$inasistencias = [];

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $fechas[] = $row['fecha_creacion'];
    $asistencias[] = (int)$row['total_asistencias'];
    // Convertimos inasistencias a negativo para que se vean "hacia abajo"
    $inasistencias[] = -(int)$row['total_inasistencias'];
}
?>
<canvas id="lineChartAsistencias" width="400" height="200"></canvas>


 </div>
</div>
</div>


<p>
</p>







<div class="col-sm-12">
<div class="card">
 <div class="card-body">
   <h3 class="card-title">Antecedentes Planteles            <a href="../fathers/mostrar" class="btn btn-success btn-sm" style="margin-left:590px">Ver todos</a></h3>


   <?php  
  $sql = "
  SELECT 
    periodos.nombre_periodo,
    COUNT(DISTINCT calificaciones.id_plantel) AS total_planteles
  FROM calificaciones
  INNER JOIN periodos ON calificaciones.id_periodo = periodos.id_periodo
  GROUP BY periodos.nombre_periodo
  ORDER BY periodos.numero_periodo ASC
";

$result = $connect->query($sql);

$periodos = [];
$totalesPlanteles = [];

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $periodos[] = $row['nombre_periodo'];
    $totalesPlanteles[] = (int)$row['total_planteles'];
}

   ?>
   <canvas id="pieChartPlantelesPorPeriodo" width="400" height="400"></canvas>

 </div>
</div>
</div>




<script>
function previewImage(event) {
var reader = new FileReader();
reader.onload = function(){
var output = document.getElementById('profileImage');
output.src = reader.result;
};
reader.readAsDataURL(event.target.files[0]);
}
</script>


<?php
if (!defined('dbhost')) {
define('dbhost', 'localhost');
}
if (!defined('dbuser')) {
define('dbuser', 'root');
}
if (!defined('dbpass')) {
define('dbpass', '');
}
if (!defined('dbname')) {
define('dbname', 'sigenor');
}


// Conectando a la base de datos
{
$connect = new PDO("mysql:host=" . dbhost . ";dbname=" . dbname, dbuser, dbpass);
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if(isset($_POST['agregar'])) {
$imgFile = $_FILES['foto']['name'];
$tmp_dir = $_FILES['foto']['tmp_name'];
$imgSize = $_FILES['foto']['size'];


$imgExt = strtolower(pathinfo($imgFile, PATHINFO_EXTENSION));
$valid_extensions = array('jpeg', 'jpg', 'png', 'gif');
$foto = rand(1000, 1000000).".".$imgExt;

if(in_array($imgExt, $valid_extensions)) {
   if($imgSize < 40000000) { // Tamaño del archivo < 40MB
       if(move_uploaded_file($tmp_dir,$foto)) {
           $_SESSION['foto'] = $foto;                       
           $stmt = $connect->prepare("UPDATE usuarios SET foto=:foto WHERE id_usuario=:id_usuario");
           $stmt->bindParam(':foto', $foto);
           $stmt->bindParam(':id_usuario', $_SESSION['id_usuario']);

           if($stmt->execute()) {
               echo '<script type="text/javascript">
               alert("Modificado Correctamente").then(function() {
                   window.location = "../pages-admin.php";
               });
               </script>';
           } else {
               $errMSG = "Error al insertar....";
           }
       } else {
           $errMSG = "Lo siento, hubo un error al subir tu archivo.";
       }
   } else {
       $errMSG = "Lo siento, tu archivo es demasiado grande.";
   }
} else {
   $errMSG = "Lo siento, solo se permiten archivos JPG, JPEG, PNG y GIF.";
}

if(isset($errMSG)) {
   echo '<script type="text/javascript">alert("'.$errMSG.'");</script>';
}
}
?>




</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {

// Datos Estudiantes por sexo (pirámide simple)
  const estudiantesData = {
    labels: ['Femenino', 'Masculino'],
    datasets: [
      {
        label: 'Femenino',
        data: [<?php echo $sexoCounts['FEMENINO']; ?>],
        backgroundColor: 'rgba(255, 99, 132, 0.7)',
        borderWidth: 1
      },
      {
        label: 'Masculino',
        data: [<?php echo $sexoCounts['MASCULINO']; ?>],
        backgroundColor: 'rgba(54, 162, 235, 0.7)',
        borderWidth: 1
      }
    ]
  };

  // Pirámide estilo (horizontal barras con valores opuestos)
  const ctxEstudiantes = document.getElementById('chartEstudiantes').getContext('2d');
  new Chart(ctxEstudiantes, {
    type: 'bar',
    data: {
      labels: ['Estudiantes'],
      datasets: [
        {
          label: 'Femenino',
          data: [<?php echo $sexoCounts['FEMENINO']; ?>],
          backgroundColor: 'rgba(255, 99, 132, 0.7)',
          stack: 'Stack 0',
        },
        {
          label: 'Masculino',
          data: [-<?php echo $sexoCounts['MASCULINO']; ?>], // negativo para la izquierda
          backgroundColor: 'rgba(54, 162, 235, 0.7)',
          stack: 'Stack 0',
        }
      ]
    },
    options: {
      indexAxis: 'y',
      responsive: true,
      scales: {
        x: {
          stacked: true,
          ticks: {
            callback: function(value) { return Math.abs(value); }
          }
        },
        y: {
          stacked: true
        }
      },
      plugins: {
        legend: {
          position: 'top',
        },
        tooltip: {
          callbacks: {
            label: function(context) {
              return context.dataset.label + ': ' + Math.abs(context.parsed.x);
            }
          }
        }
      }
    }
  });

  const ctx = document.getElementById('lineChartAsistencias').getContext('2d');

const data = {
  labels: <?php echo json_encode($fechas); ?>,
  datasets: [
    {
      label: 'Asistencias',
      data: <?php echo json_encode($asistencias); ?>,
      borderColor: 'green',
      backgroundColor: 'rgba(0,128,0,0.2)',
      fill: false,
      tension: 0.2,
      pointStyle: 'circle',
      pointRadius: 5,
      pointHoverRadius: 7,
    },
    {
      label: 'Inasistencias',
      data: <?php echo json_encode($inasistencias); ?>,
      borderColor: 'red',
      backgroundColor: 'rgba(255,0,0,0.2)',
      fill: false,
      tension: 0.2,
      pointStyle: 'triangle',
      pointRadius: 5,
      pointHoverRadius: 7,
    }
  ]
};

const config = {
  type: 'line',
  data: data,
  options: {
    scales: {
      y: {
        title: {
          display: true,
          text: 'Cantidad'
        },
        ticks: {
          // Mostrar números negativos (inasistencias)
          callback: function(value) {
            return Math.abs(value);
          }
        }
      },
      x: {
        title: {
          display: true,
          text: 'Fecha'
        }
      }
    },
    plugins: {
      tooltip: {
        callbacks: {
          label: function(context) {
            let label = context.dataset.label || '';
            let val = context.parsed.y;
            // Mostrar valor absoluto para tooltip
            return label + ': ' + Math.abs(val);
          }
        }
      }
    }
  }
};

const myChart = new Chart(ctx, config);

const ctxPie = document.getElementById('pieChartPlantelesPorPeriodo').getContext('2d');

const dataPie = {
  labels: <?php echo json_encode($periodos); ?>,
  datasets: [{
    label: 'Planteles por Periodo',
    data: <?php echo json_encode($totalesPlanteles); ?>,
    backgroundColor: [
      '#00FFFF',
      '#f67019',
      '#f53794',
      '#FFFF00',
      '#00CC00',
      '#000000',
      '#00a950',
      '#58595b',
      '#8549ba'
    ],
    borderWidth: 1,
    borderColor: '#fff'
  }]
};

const configPie = {
  type: 'pie',
  data: dataPie,
  options: {
    responsive: true,
    plugins: {
      legend: {
        position: 'right',
      },
      tooltip: {
        callbacks: {
          label: function(context) {
            let label = context.label || '';
            let value = context.parsed || 0;
            return `${label}: ${value} planteles`;
          }
        }
      }
    }
  }
};

const pieChart = new Chart(ctxPie, configPie);

    // Verificar si el loader ya se mostró
    if (!sessionStorage.getItem('loaderShown')) {
      // Mostrar loader y luego contenido
      setTimeout(function() {
        document.getElementById('loader').style.display = 'none';
        document.getElementById('contenido').style.display = 'block';

        // Marcar en sessionStorage que ya se mostró el loader
        sessionStorage.setItem('loaderShown', 'true');
      }, 8000); // 8 segundos de espera
    } else {
      // Si ya se mostró el loader, ocultar directamente loader y mostrar contenido
      document.getElementById('loader').style.display = 'none';
      document.getElementById('contenido').style.display = 'block';
    }
  });

  
</script>



<body>    



<!----------html code compleate----------->
  
     <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
 <!-- Meta tags, title, and other head elements -->
 <script src="../../Assets/js/jquery-3.3.1.slim.min.js"></script>
   <script src="../../Assets/js/popper.min.js"></script>
   <script src="../../Assets/js/bootstrap-1.min.js"></script>
   <script src="../../Assets/js/jquery-3.3.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
  <script src="http://localhost/sistema_escolar/Assets/js/scriptsidebar.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<footer class="text-center py-3 bg-light">
  <small> S.I.G.E.N.O.R UPTMA v1.0 &copy; <?php echo date("Y"); ?>  Todos los derechos reservados.</small>
</footer>
<STYLE>
footer {
  position: relative;
  bottom: 22PX;
  width: 100%;
  color:black
}
</STYLE>
  </body>
  </html>


