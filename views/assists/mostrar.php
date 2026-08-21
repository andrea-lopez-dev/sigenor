
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
  
  $stmt->close();
  $conn->close();


  if(!isset($_SESSION['rol'], $_SESSION['foto'] ) || $_SESSION['rol'] != 1){
    $foto = $_SESSION['foto'];
    header('location: ../home.php');
   
} else {
  $foto = '../../Assets/img/subidas/user.jpg'; // Ruta de la imagen por defecto en caso de no haber imagen en la sesión
}
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
        <title>Asistencias | SIGENOR</title>
	    <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="../../Assets/css/bootstrap-1.min.css">
	    <!----css3---->
        <link rel="stylesheet" href="../../Assets/css/custom.css">
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
            <h3 style="font-size:20px;"><img src="../../Assets/img/logo.png" class="img-fluid"/><span>SIGENOR</span></h3>
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
                        <a href="../../php/index.php">Respaldo</a>
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

         <li  class="active">
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
                                        <a href="#">🧮 9. REGISTRAR CALIFICACIONES</a>
                                    </li>
                                    <li>
                                        <a href="#">✅ Ejemplo: Cargar nota de Matemática del 2do periodo</a>
                                    </li>
                                    <li>
                                        <a href="#">🔹 Paso 1: Ir al módulo "Calificaciones".</a>
                                    </li>
                                    <li>
                                        <a href="#">🔹 Paso 2: Selecciona el estudiante, período, sección y asignatura.</a>
                                    </li>
                                           <li>
                                        <a href="#">🔹 Paso 3: Ingresa la calificación de cada asignatura por cada estudiante.</a>
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


            <div class="main-content">
              <div class="row">
                
                <div class="col-md-12">
                <div class="table-wrapper">
    <div class="table-title">
      <div class="row">
        <div class="col-sm-6 p-0 d-flex justify-content-lg-start justify-content-center">
          <h2 class="ml-lg-2">Asistencias</h2>
        </div>

        <div class="col-sm-12 p-0 d-flex justify-content-lg-end justify-content-center">
          <a href="#addEmployeeModal" class="btn btn-success" data-toggle="modal">
          <i class="material-icons">&#xE147;</i> </a>

          <a href="plantilla.php" class="btn btn-danger">
          <i class="material-icons">print</i> </a>
         
        </div>
      </div>
    </div>
    <?php  
require '../../Config/config.php';

$productosPorPagina = 5;
$pagina = 1;
if (isset($_GET["pagina"])) {
    $pagina = $_GET["pagina"];
}
$limit = $productosPorPagina;
$offset = ($pagina - 1) * $productosPorPagina;

// Contar el total de registros
$sentencia = $connect->query("SELECT 
    COUNT(*) AS conteo
FROM asistencias 
INNER JOIN estudiantes ON asistencias.id_estudiante = estudiantes.id_estudiante
INNER JOIN asignaturas ON asistencias.id_curso = asignaturas.id_curso
INNER JOIN seccion ON asistencias.id_seccion = seccion.id_seccion
INNER JOIN periodos ON asistencias.id_periodo = periodos.id_periodo");

$conteo = $sentencia->fetchObject()->conteo;
$paginas = ceil($conteo / $productosPorPagina);

// Consultar los registros paginados
$sentencia = $connect->prepare("SELECT 
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
LIMIT :limit OFFSET :offset");
  $sentencia->bindValue(':limit', $limit, PDO::PARAM_INT);
    $sentencia->bindValue(':offset', $offset, PDO::PARAM_INT);
    $sentencia->execute();
// Obtener los resultados
$productos = $sentencia->fetchAll(PDO::FETCH_ASSOC);
?>

    <table class="table table-striped table-hover" id="myTable">
      <thead>
        <tr>
          <th>Estudiante</th>  
          <th>Asignatura</th>
          <th>Seccion</th>
          <th>Periodo</th>
          <th>Asistencias</th>
          <th>Inasistencias</th>
          <th>Fecha</th>
          <th>Editar</th>
          <th>Eliminar</th>
        </tr>
      </thead>
      <tbody>
           <?php foreach ($productos as $producto) { ?>
            <tr>       
                <td><?php echo htmlspecialchars($producto['cedula']); ?></td>      
                <td><?php echo htmlspecialchars($producto['nombre_curso']); ?></td>
                <td><?php echo htmlspecialchars($producto['nombre_seccion']); ?></td>
                <td><?php echo htmlspecialchars($producto['nombre_periodo']); ?></td>
                <td><?php echo htmlspecialchars($producto['asistencias']); ?></td>
                <td><?php echo htmlspecialchars($producto['inasistencias']); ?></td>
                <td><?php echo htmlspecialchars($producto['fecha_creacion']); ?></td>
                <td>
                    <?php if (isset($producto['id_asistencia']) && !empty($producto['id_asistencia'])) { ?>
                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                            <input type="hidden" name="id_asistencia" value="<?php echo htmlspecialchars($producto['id_asistencia']); ?>">
                            <button name="editar" class="btn btn-warning text-white">
                                <i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i>
                            </button>
                        </form>
</td>
<td>
    <form onsubmit="return confirm('¿Realmente desea eliminar el registro?');" method='POST' action='<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>'>
        <input type='hidden' name='id_asistencia' value="<?php echo $producto['id_asistencia'] ?? ''; ?>">
        <button name='eliminar' class='btn btn-danger text-white'>
            <i class='material-icons' title='Delete'>&#xE872;</i>
        </button>
    </form>
    <?php } else { ?>
                        <span class="text-danger">ID no válido</span>
                    <?php } ?>
</td>


            </tr>
            <?php } ?>
      </tbody>
     
    </table>

    <nav aria-label="Page navigation example">
            <div class="row">
                <div class="col-xs-12 col-sm-6">

                    <p>Mostrando <?php echo $productosPorPagina ?> de <?php echo $conteo ?> cursos disponibles</p>
                </div>
                <div class="col-xs-12 col-sm-6">
                    <p>Página <?php echo $pagina ?> de <?php echo $paginas ?> </p>
                </div>
            </div>
            <ul class="pagination">
                <!-- Si la página actual es mayor a uno, mostramos el botón para ir una página atrás -->
                <?php if ($pagina > 1) { ?>
                    <li>
                        <a href="./mostrar?pagina=<?php echo $pagina - 1 ?>">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                <?php } ?>

                <!-- Mostramos enlaces para ir a todas las páginas. Es un simple ciclo for-->
                <?php for ($x = 1; $x <= $paginas; $x++) { ?>
                    <li class="<?php if ($x == $pagina) echo "active" ?>">
                        <a href="./mostrar?pagina=<?php echo $x ?>">
                            <?php echo $x ?></a>
                    </li>
                <?php } ?>
                <!-- Si la página actual es menor al total de páginas, mostramos un botón para ir una página adelante -->
                <?php if ($pagina < $paginas) { ?>
                    <li>
                        <a href="./mostrar?pagina=<?php echo $pagina + 1 ?>">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </nav>

  </div>
</div>

<?php 
if (isset($_POST['editar'])) {
    // Obtener el ID de asistencia del formulario
    $id_asistencia = isset($_POST['id_asistencia']) ? trim($_POST['id_asistencia']) : null;

    // Verificar que el ID es válido antes de continuar
    if (empty($id_asistencia)) {
        die("<div class='alert alert-danger'>Error: ID de asistencia no válido.</div>");
    }

    // Modificar la consulta SQL para incluir los valores adicionales
    $sql = "SELECT 
                asistencias.id_asistencia, 
                estudiantes.id_estudiante, 
                estudiantes.cedula, 
                asignaturas.nombre_curso,
                asignaturas.id_curso, 
                seccion.nombre_seccion, 
                seccion.id_seccion,
                periodos.nombre_periodo, 
                periodos.id_periodo,
                asistencias.asistencias, 
                asistencias.inasistencias, 
                asistencias.fecha_creacion  
            FROM asistencias 
            INNER JOIN estudiantes ON asistencias.id_estudiante = estudiantes.id_estudiante
            INNER JOIN asignaturas ON asistencias.id_curso = asignaturas.id_curso
            INNER JOIN seccion ON asistencias.id_seccion = seccion.id_seccion
            INNER JOIN periodos ON asistencias.id_periodo = periodos.id_periodo 
            WHERE asistencias.id_asistencia = :id_asistencia";

    $stmt = $connect->prepare($sql);
    $stmt->bindParam(':id_asistencia', $id_asistencia, PDO::PARAM_INT); 
    $stmt->execute();

    // Obtener los resultados
    $obj = $stmt->fetchObject();

    // Verificar si se obtuvieron datos
    if (!$obj) {
        die("<div class='alert alert-danger'>Error: No se encontraron datos para la asistencia con ID " . htmlspecialchars($id_asistencia) . ".</div>");
    }

    // Asignamos los valores de los nuevos campos, verificando que existan
    $id_asistencia = isset($obj->id_asistencia) ? $obj->id_asistencia : '';
    $cedula = isset($obj->cedula) ? $obj->cedula : '';
    $nombre_curso = isset($obj->nombre_curso) ? $obj->nombre_curso : '';
    $nombre_seccion = isset($obj->nombre_seccion) ? $obj->nombre_seccion : '';
    $nombre_periodo = isset($obj->nombre_periodo) ? $obj->nombre_periodo : '';
    $asistencias = isset($obj->asistencias) ? $obj->asistencias : '0'; // Default: 0 para evitar valores vacíos
    $inasistencias = isset($obj->inasistencias) ? $obj->inasistencias : '0';
    $fecha_creacion = isset($obj->fecha_creacion) ? $obj->fecha_creacion : '';

    // Mostrar el formulario con los valores obtenidos

?>

    <div class="col-12 col-md-12"> 

<form role="form" method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>">
    <input value="<?php echo $id_asistencia;?>" name="id_asistencia" type="hidden">
  <div class="form-row">

    <div class="form-group col-md-6">
      <label for="estudiante">Estudiante</label>
      <select value="<?php echo $cedula;?>" name="cedula" type="text" class="form-control">
      <?php 
$stmt = $connect->prepare('SELECT e.id_estudiante, e.cedula 
    FROM estudiantes e
    INNER JOIN asistencias a ON e.id_estudiante = a.id_estudiante');
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo '<option value="' . $row["id_estudiante"] . '">' . $row["cedula"] . '</option>';
}
?>
</select>
  </div>
  
  <div class="form-group col-md-6">
      <label for="asignatura">Asignatura</label>
      <select value="<?php echo $nombre_curso;?>" name="nombre_curso" type="text" class="form-control">
      <?php 
$stmt = $connect->prepare('SELECT asig.id_curso, asig.nombre_curso 
    FROM asignaturas asig
    INNER JOIN asistencias a ON asig.id_curso = a.id_curso');
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo '<option value="' . $row["id_curso"] . '">' . $row["nombre_curso"] . '</option>';
}
?>
</select>
    </div>


  <div class="form-group col-md-6">
      <label for="seccion">Seccion</label>
      <select value="<?php echo $nombre_seccion;?>" name="nombre_seccion" type="text" class="form-control">
      <?php 
$stmt = $connect->prepare('SELECT secc.id_seccion, secc.nombre_seccion 
    FROM seccion secc
    INNER JOIN asistencias a ON secc.id_seccion = a.id_seccion');
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo '<option value="' . $row["id_seccion"] . '">' . $row["nombre_seccion"] . '</option>';
}
?>
  </select>
    </div>
  <div class="form-group col-md-6">
      <label for="periodo">Periodo</label>
      <select value="<?php echo $nombre_periodo;?>" name="nombre_periodo" type="text" class="form-control">
      <?php 
$stmt = $connect->prepare('SELECT per.id_periodo, per.nombre_periodo 
    FROM periodos per
    INNER JOIN asistencias a ON per.id_periodo = a.id_periodo');
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo '<option value="' . $row["id_periodo"] . '">' . $row["nombre_periodo"] . '</option>';
}
?>
  </select>
    </div>
 <div class="form-group col-md-6">
      <label for="asistencias">Asistencias</label>
      <input value="<?php echo $asistencias;?>" name="asistencias" type="number" class="form-control">
      </div>
  <div class="form-group col-md-6">
      <label for="inasistencias">Inasistencias</label>
      <input value="<?php echo $inasistencias;?>" name="inasistencias" type="number" class="form-control">
  </div>
  <div class="form-group col-md-6">
      <label for="fecha_creacion">Fecha</label>
      <input value="<?php echo $fecha_creacion;?>" name="fecha_creacion" type="date" class="form-control">
  </div>
  </div>
        <div class="form-group">
          <button name="actualizar" type="submit" class="btn btn-primary  btn-block">Actualizar Registro</button>
        </div>
        
</form>
    </div>  
<?php }?>

<!-- add Modal HTML -->
<div class="modal fade" id="addEmployeeModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <form  enctype="multipart/form-data" method="POST"  autocomplete="off">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fa fa-user mr-1"></i>NUEVO
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="modal_contact_firstname">Estudiante</label>
                                    <div class="input-group">
                                       
                                    <select required name="txtid_estudiante"  class="form-control">
                                            <?php
                                            $sql = $connect->prepare('SELECT estudiantes.id_estudiante, estudiantes.cedula FROM estudiantes');
                                            $sql->execute();

                                            while($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                                                echo '<option value="' . $row["id_estudiante"] . '">' . $row["cedula"] . '</option>';
                                            }
                                            ?>
                                        </select>
                                </div>
                            </div>
                            </div>


                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="modal_contact_lastname">Asignatura</label>
                                    <div class="input-group">
                                       <select required name="txtid_curso"  class="form-control">
                                            <?php
                                            $sql = $connect->prepare('SELECT asignaturas.id_curso, asignaturas.nombre_curso FROM asignaturas');
                                            $sql->execute();

                                            while($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                                                echo '<option value="' . $row["id_curso"] . '">' . $row["nombre_curso"] . '</option>';
                                            }
                                            ?>
                                        </select>
                                                                    
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">                         
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="modal_contact_lastname">Seccion</label>
                                    <div class="input-group">
                                       <select required name="txtid_seccion"  class="form-control">
                                            <?php
                                            $sql = $connect->prepare('SELECT seccion.id_seccion, seccion.nombre_seccion FROM seccion');
                                            $sql->execute();

                                            while($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                                                echo '<option value="' . $row["id_seccion"] . '">' . $row["nombre_seccion"] . '</option>';
                                            }
                                            ?>
                                        </select>
                                                                    
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="modal_contact_lastname">Periodo</label>
                                    <div class="input-group">
                                    <select required name="txtid_periodo"  class="form-control">
                                            <?php
                                            $sql = $connect->prepare('SELECT periodos.id_periodo, periodos.nombre_periodo FROM periodos');
                                            $sql->execute();

                                            while($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                                                echo '<option value="' . $row["id_periodo"] . '">' . $row["nombre_periodo"] . '</option>';
                                            }
                                            ?>
                                        </select>
                                                                     
                                    </div>
                                </div>
                                </div>

                                <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="modal_contact_lastname">Asistencias</label>
                                    <div class="input-group">
                                    <input type="text"  name="txtasistencias" required class="form-control"/>
                                    </div>
                                
                            </div> 
                            </div> 
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="modal_contact_lastname">Inasistencias</label>
                                    <div class="input-group">
                                    <input type="text"  name="txtinasistencias" required class="form-control"/>
                                    </div>
                                
                            </div> 
                            </div> 

                           
                                    <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="modal_contact_lastname">Fecha de Registro</label>
                                    <div class="input-group">
                                    <input type="date"  name="txtfecha" required class="form-control"/>
                                    </div>

                    </div>
                    </div> 
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">CANCELAR</button>
                        <button  name='agregar' class="btn btn-primary">GUARDAR</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

<!-- Edit Modal HTML -->
</div>
        </div>
		   
</div>
</div>
<!----------html code compleate----------->
  
     <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
   <script src="../../Assets/js/jquery-3.3.1.slim.min.js"></script>
   <script src="../../Assets/js/popper.min.js"></script>
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
<?php  
if (isset($_POST['agregar'])) {
    // Capturar datos del formulario
    $id_estudiante = $_POST['txtid_estudiante'];
    $id_curso = $_POST['txtid_curso'];
    $id_seccion = $_POST['txtid_seccion'];
    $id_periodo = $_POST['txtid_periodo'];
    $asistencias = $_POST['txtasistencias'];
    $inasistencias = $_POST['txtinasistencias'];
    $fecha_creacion = $_POST['txtfecha'];

    // Validación de campos
    if (empty($id_estudiante)) {
        $errMSG = "Por favor ingresa el nombre del estudiante.";
    } else if (empty($id_curso)) {
        $errMSG = "Por favor ingresa el nombre de la asignatura.";
    } else if (empty($id_seccion)) {
        $errMSG = "Por favor ingresa el Inombre de la seccion.";
    } else if (empty($id_periodo)) {
        $errMSG = "Por favor ingresa el nombre del periodo.";
    } else if (empty($asistencias)) {
        $errMSG = "Por favor ingresa el numero de asistencias.";
    } else if (empty($inasistencias)) {
        $errMSG = "Por favor ingresa el numero de inasistencias.";
    } else if (empty($fecha_creacion)) {
        $errMSG = "Por favor ingresa la fecha del dia de hoy.";
    }

    // Si no hay errores, continuar con el proceso
    if (!isset($errMSG)) {
        try {
            // Consulta SQL corregida
            $stmt = $connect->prepare("INSERT INTO asistencias (id_estudiante, id_curso, id_seccion, id_periodo, asistencias, inasistencias, fecha_creacion) 
                                       VALUES (:id_estudiante, :id_curso, :id_seccion, :id_periodo, :asistencias, :inasistencias, :fecha_creacion)");
            // Vincular parámetros
            $stmt->bindParam(':id_estudiante', $id_estudiante);
            $stmt->bindParam(':id_curso', $id_curso);
            $stmt->bindParam(':id_seccion', $id_seccion);
            $stmt->bindParam(':id_periodo', $id_periodo);
            $stmt->bindParam(':asistencias', $asistencias);
            $stmt->bindParam(':inasistencias', $inasistencias);
            $stmt->bindParam(':fecha_creacion', $fecha_creacion);

            // Ejecutar la consulta
            if ($stmt->execute()) {
                echo '<script type="text/javascript">
                swal("¡Registrado!", "Agregado correctamente", "success").then(function() {
                    window.location = "mostrar";
                });
                </script>';
            } else {
                $errMSG = "Error al insertar los datos.";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
?>

<script type="text/javascript">
$(document).ready(function() {
    setTimeout(function() {
        $(".content").fadeOut(1500);
    },3000);

});
</script>


<?php  
if(isset($_POST['eliminar'])){

try {
        // Verificar si hay dependencias en estudiantes_planteles
        $consultaVerificar = "SELECT COUNT(*) FROM asistencias WHERE id_asistencia = :id_asistencia";
        $stmtVerificar = $connect->prepare($consultaVerificar);
        $idstu = trim($_POST['id_asistencia']);
        $stmtVerificar->bindParam(':id_asistencia', $idstu, PDO::PARAM_INT);
        $stmtVerificar->execute();
        $dependencias = $stmtVerificar->fetchColumn();

        if ($dependencias > 0) {
            echo '<script type="text/javascript">
            swal("Error", "No se puede eliminar este periodo porque esta relacionado. Por favor, elimina la relacion primero.", "error");
            </script>';
        }  else {

$consulta = "DELETE FROM `asistencias` WHERE `id_asistencia`=:id_asistencia";
$sql = $connect-> prepare($consulta);
$sql -> bindParam(':id_asistencia', $idcur, PDO::PARAM_INT);
$idcur=trim($_POST['id_asistencia']);
$sql->execute();

if($sql->rowCount() > 0)
{
$count = $sql -> rowCount();
echo '<script type="text/javascript">
swal("¡Eliminado!", "Eliminado correctamente", "success").then(function() {
            window.location = "mostrar";
        });
        </script>';
}
else{
    echo "<div class='content alert alert-danger'> No se pudo eliminar el registro  </div>";


}}
    } catch (PDOException $e) {
        echo "<div class='content alert alert-danger'>Error: " . htmlspecialchars($e->getMessage()) . "</div>";
    }
}
// Cierra envio de guardado
?>
  

  <?php
if (isset($_POST['actualizar'])) {
    require '../../Config/config.php';

    // Obtener valores del formulario
    $id_asistencia = isset($_POST['id_asistencia']) ? trim($_POST['id_asistencia']) : null;
    $asistencias = isset($_POST['asistencias']) ? trim($_POST['asistencias']) : null;
    $inasistencias = isset($_POST['inasistencias']) ? trim($_POST['inasistencias']) : null;
    $fecha_creacion = isset($_POST['fecha_creacion']) ? trim($_POST['fecha_creacion']) : null;

    // Validar que los campos no estén vacíos
    if (empty($id_asistencia) || empty($asistencias) || empty($inasistencias) || empty($fecha_creacion)) {
        die("<div class='alert alert-danger'>Error: Todos los campos son obligatorios.</div>");
    }

        // Consulta SQL corregida
        $consulta = "UPDATE asistencias 
                     SET asistencias = :asistencias, 
                         inasistencias = :inasistencias, 
                         fecha_creacion = :fecha_creacion 
                     WHERE id_asistencia = :id_asistencia";

        $sql = $connect->prepare($consulta);

        // Vincular parámetros con valores del formulario
        $sql->bindParam(':asistencias', $asistencias, PDO::PARAM_INT);
        $sql->bindParam(':inasistencias', $inasistencias, PDO::PARAM_INT);
        $sql->bindParam(':fecha_creacion', $fecha_creacion, PDO::PARAM_STR);
        $sql->bindParam(':id_asistencia', $id_asistencia, PDO::PARAM_INT); // Parámetro faltante agregado

        // Ejecutar la consulta
        $sql->execute();

        // Verificar si se actualizó alguna fila
      if($sql->rowCount() > 0)
{
$count = $sql -> rowCount();
echo  '<script type="text/javascript">
swal("¡Actualizado!", "Registro actualizado correctamente", "success").then(function() {
            window.location = "mostrar";
        });
        </script>';
} else {
    echo "<div class='alert alert-warning'>No se realizaron cambios en el registro.</div>";
}

    }

?>

<script>
   function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah')
                        .attr('src', e.target.result);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
  </script>
  <script src="../../Assets/js/periodo.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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


