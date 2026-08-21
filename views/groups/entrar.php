<?php  

  session_start(); 
  if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../home.php");
    exit();
}
  $conn = new mysqli("localhost", "root", "", "sigenor");
  if ($conn->connect_error) {
      die("Conexión fallida: " . $conn->connect_error);
  }
  
  $stmt = $conn->prepare("SELECT foto FROM usuarios WHERE id_usuario = ?");
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $result = $stmt->get_result();
  
  if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $foto_perfil = $row['foto'];
  } else {
      $foto_perfil = "ruta/a/imagen_por_defecto.jpg";
  }
  



  if(!isset($_SESSION['rol'], $_SESSION['foto'] ) || $_SESSION['rol'] != 1){
    $foto = $_SESSION['foto'];
    header('location: ../home.php');
   
} 
  $stmt->close();
  $conn->close();

?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
        <title>Sección | SIGENOR</title>
	    <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="../../Assets/css/bootstrap-1.min.css">
	    <!----css3---->
        <link rel="stylesheet" href="../../Assets/css/custom.css">
        <link rel="stylesheet" href="../../Assets/css/check.css">
        <link rel="icon" type="image/png" sizes="96x96" href="../../Assets/img/logo.png">
		<!--google fonts -->
	
	    <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
	<!--google material icon-->
      <link href="https://fonts.googleapis.com/css2?family=Material+Icons"rel="stylesheet">
       <link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet">
        <link href="../../Assets/css/font-awesome.min.css" rel="stylesheet" />
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/js/bootstrap.min.js.map">
  </head>
  <body>
  

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
          <img src="../../Views/profile/<?php echo ucfirst ($_SESSION['foto']) ?> " style="width:40px; height:40px; border-radius:50%;"/>
          <span><?php echo ucfirst($_SESSION['nombre_usuario']); ?></span></a>
                    <ul class="collapse list-unstyled menu" id="pageSubmenu3">
                        <li>
                            <a href="../profile/mostrar.php">Perfil</a>
                        </li>
                        <li>
                            <a href="#">Configuración</a>
                        </li>
                        <li>
                            <a href="../pages-logout.php">Cerrar sesión</a>
                        </li>
                    </ul>
                </li>

			<li  class="">
                    <a href="../admin/pages-admin.php" class="dashboard"><i class="material-icons">dashboard</i>
					<span>Dashboard</span></a>
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
     <li  class="active">
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

                    <!-- Start XP Col -->
                
                    <!-- End XP Col -->

                    <!-- Start XP Col -->
                    <div class="col-10 col-md-6 col-lg-8 order-1 order-md-3" style="margin-left: 281px;">
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
                                        <a href="#">🧒 5. REGISTRAR ESTUDIANTES</a>
                                    </li>
                                    <li>
                                        <a href="#">✅ Ejemplo: Registrar al estudiante Juan Pérez</a>
                                    </li>
                                    <li>
                                        <a href="#">🔹 Paso 1: Ir al módulo "Estudiantes".</a>
                                    </li>
                                    <li>
                                        <a href="#">🔹 Paso 2: Haz clic en "Nuevo Estudiante".</a>
                                    </li>
                                    <li>
                                        <a href="#">🔹 Paso 3: Ingresar cédula, nombres, apellidos, fecha de nacimiento, etc.</a>
                                    </li>
                                      <li>
                                        <a href="#">🔹 Paso 4: Asignar sección y guardar ✅.</a>
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
                                        <a href="#"><span class="material-icons">
settings
</span>Configuración</a>
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
                <!-- End XP Row -->

            </div>
		     <div class="xp-breadcrumbbar text-center">
                <h4 class="page-title">Bienvenido&nbsp;<?php echo ucfirst($_SESSION['nombre_usuario']); ?></h4>  
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><?php echo ucfirst($_SESSION['nombre_completo']); ?></li>
                    
                  </ol>                
            </div>
			
		   </div>

           <!--------main-content------------->


   
         



   
<div class="container">
    <div class="main-body">
    
          <!-- Breadcrumb -->
          <nav aria-label="breadcrumb" class="main-breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="../admin/pages-admin.php">Home</a></li>
              <li class="breadcrumb-item"><a href="../groups/mostrar">Sección</a></li>
              <li class="breadcrumb-item active" aria-current="page">Secciones alumnos</li>
            </ol>
          </nav>
          <!-- /Breadcrumb ------------>
<?php
require_once '../../Config/config.php';

$id_periodo = isset($_GET['periodo']) ? intval($_GET['periodo']) : null;


if (!$id_periodo) {
    die("Parámetro de periodo inválido.");
}

$sql = "
    SELECT 
        e.id_estudiante,
        e.nombres,
        e.apellidos,
        e.cedula,
        e.foto,
        s.nombre_seccion,
        s.id_periodo
    FROM estudiantes e
    INNER JOIN seccion s ON e.id_seccion = s.id_seccion
    WHERE s.id_periodo = :id_periodo
";

$stmt = $connect->prepare($sql);
$stmt->bindParam(':id_periodo', $id_periodo, PDO::PARAM_INT);
$stmt->execute();

$estudiantesFiltrados = [];
while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
    // Validar explícitamente que el estudiante pertenece al periodo
    if ((int)$fila['id_periodo'] === $id_periodo) {
        $estudiantesFiltrados[] = $fila;
    }
}



?>




<!-- Estilos CSS para contenedores uniformes y efecto hover -->
<style>
    /* Estilo general para el contenedor */
    .container {
        padding-top: 20px;
    }

    /* Diseño de las tarjetas */
    .card {
        border: 1px solid #ddd;
        border-radius: 15px; /* Bordes redondeados */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Sombra inicial */
        transition: transform 0.3s ease, box-shadow 0.3s ease; /* Transición suave */
        overflow: hidden; /* Para que la imagen no sobresalga */
    }

    .card:hover {
        transform: translateY(-10px); /* Eleva la tarjeta al pasar el cursor */
        box-shadow: 0 15px 25px rgba(0, 0, 0, 0.2); /* Aumenta la sombra */
    }

    /* Imagen de las tarjetas */
    .card-img-top {
        transition: transform 0.3s ease; /* Transición suave para la imagen */
    }

    .card:hover .card-img-top {
        transform: scale(1.1); /* Zoom en la imagen al pasar el cursor */
    }

    /* Cuerpo de la tarjeta */
    .card-body {
        padding: 20px;
        background-color: #f9f9f9; /* Fondo claro */
        border-top: 1px solid #ddd; /* Separador */
        transition: background-color 0.3s ease; /* Transición de color */
    }

    .card:hover .card-body {
        background-color: #f1f1f1; /* Cambio de fondo al pasar el cursor */
    }

    /* Título de la tarjeta */
    .card-title {
        font-size: 1.3rem;
        color: #333;
        margin-bottom: 10px;
    }

    /* Texto dentro de la tarjeta */
    .card-text {
        color: #555;
        margin-bottom: 15px;
    }

    /* Botón dentro de la tarjeta */
    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
        transition: background-color 0.3s ease, transform 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        transform: scale(1.05); /* Efecto de aumento al pasar el cursor */
    }

    /* Responsividad para pantallas más pequeñas */
    @media (max-width: 356px) {
        .card {
            margin-bottom: 20px;
        }
    }

    
</style>


<!-- Mostrar estudiantes horizontalmente con diseño uniforme -->
<div class="container mt-4">
    <div class="row">

<?php
       if (empty($estudiantesFiltrados)) { echo '<div class="alert alert-warning">No hay estudiantes registrados en este periodo.</div>';
} else { 

             foreach ($estudiantesFiltrados as $estudiante){  ?>
                <div class="col-md-4 mb-4">
                    <div class="card text-center">
                        <img src="../../Assets/img/subidas/<?php echo htmlspecialchars($estudiante['foto'] ?? 'default.jpg'); ?>" 
                             alt="Foto del estudiante" 
                             class="card-img-top" 
                             style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($estudiante['nombres'] . " " . $estudiante['apellidos']); ?></h5>
                            <p class="card-text">Cédula: <?php echo htmlspecialchars($estudiante['cedula']); ?></p>
                            <p class="card-text">Sección: <?php echo htmlspecialchars($estudiante['nombre_seccion']); ?></p>
                            <button type="button" class="btn btn-primary mb-2"
                                    onclick="loadStudentDetails(<?php echo $estudiante['id_estudiante'];  ?>)">
                                Detalles
                            </button>
                            <br>
                            <a href="boletin.php?id=<?php echo $estudiante['id_estudiante']; ?>&periodo=<?php echo $id_periodo; ?>" 
   class="btn btn-danger text-white">
   <i class="material-icons" title="Imprimir">print</i>
</a>

                        </div>
                    </div>
                </div>
                     <?php  } ?>
            <?php  } ?>
        
    </div>
</div>


<div class="modal fade" id="studentModal" tabindex="-1" role="dialog" aria-labelledby="studentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Encabezado del Modal -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="studentModalLabel">Detalles del Estudiante</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <!-- Cuerpo del Modal -->
            <div class="modal-body">
                <div id="modalSpinner" class="text-center py-5">
                    <!-- Spinner de carga -->
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                </div>
                <!-- Contenido dinámico cargado aquí -->
                <div id="modalContent" class="d-none">
                    <!-- Este contenido será reemplazado dinámicamente -->
                </div>
            </div>
            <!-- Pie del Modal -->
            <div class="modal-footer bg-light">
              
            </div>
        </div>
    </div>
</div>


<!-- CSS -->
<style>
    .card-img-top {
        max-height: 180px;
        object-fit: cover;
    }
</style>
<style>
    /* Clase para uniformar el tamaño del texto */
    .uniform-text {
        font-size: 16px; /* Ajusta el tamaño para toda la tabla */
        font-weight: normal;
        line-height: 1.5; /* Uniformidad entre líneas */
    }

    /* Estilo general para la tabla */
    .table-bordered {
        border: 1px solid #ddd;
    }

    .table-hover tbody tr:hover {
        background-color: #f1f1f1;
    }

    .align-middle {
        vertical-align: middle;
    }

    .text-center {
        text-align: center;
    }
</style>



<!-- Carga correcta de las bibliotecas -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- JavaScript para manejar el modal -->
<script>
    // Función para cargar datos en el modal
    function loadStudentDetails(studentId) {
        const modalSpinner = document.getElementById('modalSpinner');
        const modalContent = document.getElementById('modalContent');

        // Mostrar spinner de carga y ocultar contenido previo
        modalSpinner.classList.remove('d-none');
        modalContent.classList.add('d-none');

        fetch('modalData.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'id_estudiante=' + encodeURIComponent(studentId),
        })
        .then(response => response.text())
        .then(data => {
            // Ocultar spinner y mostrar contenido cargado dinámicamente
            modalSpinner.classList.add('d-none');
            modalContent.classList.remove('d-none');
            modalContent.innerHTML = data;

            // Mostrar el modal una vez cargados los datos
            const modalElement = document.getElementById('studentModal');
            const modalInstance = new bootstrap.Modal(modalElement);
            modalInstance.show();
        })
        .catch(error => {
            modalSpinner.classList.add('d-none');
            modalContent.classList.remove('d-none');
            modalContent.innerHTML = `<p class="text-danger">Error al cargar los detalles: ${error.message}</p>`;
        });
    }
</script>







<section style="background-color: #eee;">
  <div class="container py-5">


  



<!----------html code compleate----------->
  
     <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
   <script src="../../Assets/js/jquery-3.3.1.slim.min.js"></script>
   <script src="../../Assets/js/popper.min.js"></script>
   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
   <script src="../../Assets/js/bootstrap-1.min.js"></script>
   <script src="../../Assets/js/jquery-3.3.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript">
		$(document).ready(function(){
		  $(".xp-menubar").on('click',function(){
		    $('#sidebar').toggleClass('active');
			$('#content').toggleClass('active');
		  });
		  
		   $(".xp-menubar,.body-overlay").on('click',function(){
		     $('#sidebar,.body-overlay').toggleClass('show-nav');
		   });
		  
		});
</script>
<script type="text/javascript">

</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
 
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


