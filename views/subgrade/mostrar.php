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
        <title>Estudiates | SIGENOR</title>
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
        <style type="text/css">
           .hideMe {
    display: none;
}
       </style>
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
                <li  class="active">
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
                                        <a href="#">🔟 REGISTRAR PLAN ADMINISTRATIVO 🗂️</a>
                                    </li>
                                    <li>
                                        <a href="#">1️⃣ Ve al módulo 📁 Plan Administrativo en el sistema.</a>
                                    </li>
                                    <li>
                                        <a href="#">2️⃣ Haz clic en ➕ Registrar Nuevo Plan.</a>
                                    </li>
                                    <li>
                                        <a href="#">3️⃣ Completa los siguientes campos:</a>
                                    </li>
                                    <li>
                                         <li>
                                        <a href="#">3️⃣ Completa los siguientes campos requeridos: incluyendo la evaluación ya sea Diagnóstica, Final, Tutoria, etc.</a>

                                    <li>
                                        <a href="#">5️⃣ Haz clic en 💾 Guardar y tendrás el plan listo para ser usado, incluyendo datos relevantes del Resúmen Curricular</a>
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
          <h2 class="ml-lg-2">Calificaciones</h2>
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
    $pagina = (int) $_GET["pagina"];
}

$limit = (int) $productosPorPagina;
$offset = (int) (($pagina - 1) * $productosPorPagina);

// Consulta para el conteo total de estudiantes
$sentencia = $connect->query("
    SELECT COUNT(*) AS conteo
    FROM calificaciones 
    INNER JOIN estudiantes ON calificaciones.id_estudiante = estudiantes.id_estudiante 
    INNER JOIN asignaturas ON calificaciones.id_curso = asignaturas.id_curso 
    INNER JOIN periodos ON calificaciones.id_periodo = periodos.id_periodo 
    INNER JOIN planteles ON calificaciones.id_plantel = planteles.id_plantel");

$conteo = $sentencia->fetchObject()->conteo ?? 0; // Asigna 0 si no hay resultados
$paginas = ($conteo > 0) ? ceil($conteo / $productosPorPagina) : 1; // Evita división por 0

// Consulta para obtener los datos de los estudiantes con paginación
$sentencia = $connect->prepare("
    SELECT calificaciones.id_calificacion, estudiantes.id_estudiante, estudiantes.cedula, 
           asignaturas.id_curso, asignaturas.nombre_curso, 
           periodos.id_periodo, periodos.nombre_periodo, planteles.nombre, planteles.numero_plantel,
           calificaciones.calificacion, calificaciones.calificacion_letras, 
           calificaciones.`T-E` AS te, calificaciones.mes, calificaciones.año AS anio
    FROM calificaciones 
    INNER JOIN estudiantes ON calificaciones.id_estudiante = estudiantes.id_estudiante 
    INNER JOIN asignaturas ON calificaciones.id_curso = asignaturas.id_curso 
    INNER JOIN periodos ON calificaciones.id_periodo = periodos.id_periodo 
    INNER JOIN planteles ON calificaciones.id_plantel = planteles.id_plantel
    GROUP BY calificaciones.id_calificacion
    LIMIT ? OFFSET ?");

$sentencia->execute([$limit, $offset]);
$productos = $sentencia->fetchAll(PDO::FETCH_ASSOC);


?>


    <table class="table table-striped table-hover">
      <thead>
        <tr>
            
         
        
        <th>Cedula Estudiante</th>
          <th>Asignatura</th>
          <th>Periodo</th>
          <th>Nº Plantel</th>
          <th>Plantel</th>          
          <th>Calificacion numerica</th>
          <th>Calificacion en letras</th>
          <th>T-E</th>
          <th>Mes</th>
          <th>Año</th>    
          <th>Editar</th>
          <th>Eliminar</th>
        </tr>
      </thead>

      <tbody>
          <?php foreach($productos as $producto){ ?>
            <tr>
        
            <td><?php echo $producto['cedula']; ?></td>
        <td><span class="badge badge-primary"><?php echo $producto['nombre_curso']; ?></span></td>
        <td><span class="badge badge-danger"><?php echo $producto['nombre_periodo']; ?></span></td>
        <td><?php echo $producto['numero_plantel']; ?></td>
        <td><?php echo $producto['nombre']; ?></td>
        <td><?php echo $producto['calificacion']; ?></td>
        <td><?php echo $producto['calificacion_letras']; ?></td>
        <td><?php echo $producto['te']; ?></td>
        <td><?php echo $producto['mes']; ?></td>
        <td><?php echo $producto['anio']; ?></td>
      
               
            
    
               <td>
<form method='POST' action='<?php $_SERVER['PHP_SELF'] ?>'>
<input type='hidden' name='id_calificaciones' value="<?php echo  $producto['id_calificacion']; ?>">
<button name='editar' class='btn btn-warning text-white'><i class='material-icons' data-toggle='tooltip' title='Edit'>&#xE254;</i></button>
</form>
                   
               </td>
               <td>
<form  onsubmit="return confirm('Realmente desea eliminar el registro?');" method='POST' action='<?php $_SERVER['PHP_SELF'] ?>'>
<input type='hidden' name='id_calificaciones' value="<?php echo  $producto['id_calificacion']; ?>">
<button name='eliminar' class='btn btn-danger text-white' ><i class='material-icons'  title='Delete'>&#xE872;</i></button>
</form>
               </td>

            </tr>
            <?php } ?>
      </tbody>
    </table>
    <nav aria-label="Page navigation example">
            <div class="row">
                <div class="col-xs-12 col-sm-6">

                    <p>Mostrando <?php echo $productosPorPagina ?> de <?php echo $conteo ?> estudiantes disponibles</p>
                </div>
                <div class="col-xs-12 col-sm-6">
                    <p>Página <?php echo $pagina ?> de <?php echo $paginas ?> </p>
                </div>
            </div>
           <ul class="pagination">
        <?php if ($pagina > 1) { ?>
            <li>
                <a href="./mostrar?pagina=<?php echo $pagina - 1; ?>">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
        <?php } ?>

        <?php for ($x = 1; $x <= $paginas; $x++) { ?>
            <li class="<?php if ($x == $pagina) echo "active"; ?>">
                <a href="./mostrar?pagina=<?php echo $x; ?>"><?php echo $x; ?></a>
            </li>
        <?php } ?>

        <?php if ($pagina < $paginas) { ?>
            <li>
                <a href="./mostrar?pagina=<?php echo $pagina + 1; ?>">
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
    $id_calificacion = $_POST['id_calificaciones'];
    $sql = "SELECT calificaciones.id_calificacion, 
                   calificaciones.id_estudiante, 
                   calificaciones.id_curso, 
                   calificaciones.id_periodo, 
                   calificaciones.id_plantel, 
                   calificaciones.calificacion, 
                   calificaciones.calificacion_letras, 
                   calificaciones.`T-E` AS te, 
                   calificaciones.mes, 
                   calificaciones.año AS anio
            FROM calificaciones 
            INNER JOIN estudiantes ON calificaciones.id_estudiante = estudiantes.id_estudiante 
INNER JOIN asignaturas ON calificaciones.id_curso = asignaturas.id_curso 
INNER JOIN periodos ON calificaciones.id_periodo = periodos.id_periodo 
INNER JOIN planteles ON calificaciones.id_plantel = planteles.id_plantel
            WHERE calificaciones.id_calificacion = :id_calificacion";
    $stmt = $connect->prepare($sql);
    $stmt->bindParam(':id_calificacion', $id_calificacion, PDO::PARAM_INT);
    $stmt->execute();
    $obj = $stmt->fetchObject();


// Asegurarse de que las variables estén definidas
$id_calificacion = isset($obj->id_calificacion) ? $obj->id_calificacion : '';
$id_estudiante = isset($obj->id_estudiante) ? $obj->id_estudiante : '';
$id_curso = isset($obj->id_curso) ? $obj->id_curso : '';
$id_periodo = isset($obj->id_periodo) ? $obj->id_periodo : '';
$id_plantel = isset($obj->id_plantel) ? $obj->id_plantel : '';
$calificacion = isset($obj->calificacion) ? $obj->calificacion : '';
$calificacion_letras = isset($obj->calificacion_letras) ? $obj->calificacion_letras : '';
$te = isset($obj->te) ? $obj->te : ''; // Asegurarse de que la variable $te esté definida
$mes = isset($obj->mes) ? $obj->mes : '';
$anio = isset($obj->anio) ? $obj->anio : '';
$numero_periodo = isset($obj->numero_periodo) ? $obj->numero_periodo : ''; // Para periodo
$nombre_curso = isset($obj->nombre_curso) ? $obj->nombre_curso : '';
$nombre = isset($obj->nombre) ? $obj->nombre : '';

?>

<div class="col-12 col-md-12"> 

<form role="form" method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>">
    <input value="<?php echo $id_calificacion; ?>" name="idcalif" type="hidden">
  
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="estudiante">Estudiante</label>
            <select required name="id_estudiante" class="form-control">

                <?php 
                $stmt = $connect->prepare('SELECT id_estudiante, cedula FROM estudiantes');
                $stmt->execute();
 
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                ?>
                    <option value="<?php echo $id_estudiante; ?>"><?php echo $cedula; ?></option>
                <?php } ?>
            </select>
        </div>

        <div class="form-group col-md-6">
            <label for="curso">Curso</label>
            <select required name="id_curso" class="form-control">

                <?php 
                $stmt = $connect->prepare('SELECT id_curso, nombre_curso FROM asignaturas');
                $stmt->execute();

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo '<option value="' . $row["id_curso"] . '">' . $row["nombre_curso"] . '</option>';
                    }
                ?>
            </select>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="periodo">Periodo</label>
            <select required name="id_periodo" class="form-control">            

                <?php 
                $stmt = $connect->prepare('SELECT id_periodo, numero_periodo FROM periodos');
                $stmt->execute();

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                ?>
                    <option value="<?php echo $id_periodo; ?>"><?php echo $numero_periodo; ?></option>
                <?php } ?>
            </select>
        </div>

        <div class="form-group col-md-6">
            <label for="plantel">Plantel</label>
            <select required name="id_plantel" class="form-control">
                
                <?php 
                $stmt = $connect->prepare('SELECT id_plantel, nombre FROM planteles');
                $stmt->execute();

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                ?>
                    <option value="<?php echo $id_plantel; ?>"><?php echo $nombre; ?></option>
                <?php } ?>
            </select>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-4">
            <label for="calificacion">Calificación</label>
            <input value="<?php echo $calificacion; ?>" name="calificacion" type="text" class="form-control">
        </div>

        <div class="form-group col-md-4">
            <label for="calificacion_letras">Calificación (Letras)</label>
            <input value="<?php echo $calificacion_letras; ?>" name="calificacion_letras" type="text" class="form-control">
        </div>

        <div class="form-group col-md-4">
            <label for="te">T-E</label>
            <input value="<?php echo $te; ?>" name="te" type="text" class="form-control">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="mes">Mes</label>
            <input value="<?php echo $mes; ?>" name="mes" type="number" class="form-control">
        </div>

        <div class="form-group col-md-6">
            <label for="anio">Año</label>
            <input value="<?php echo $anio; ?>" name="año" type="number" class="form-control">
        </div>
    </div>

    <div class="form-group">
        <button name="actualizar" type="submit" class="btn btn-primary btn-block">Actualizar Registro</button>
    </div>
</form>
</div>
             <?php }?>

<!-- add Modal HTML -->
<div class="modal fade" id="addEmployeeModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form enctype="multipart/form-data" method="POST" autocomplete="off">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fa fa-user mr-1"></i>NUEVO REGISTRO
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div id="step1"> 
                    <div class="form-row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <div class="input-group">                                                            
                                    <select required name="id_estudiante" class="form-control">
                                    <option value="">-- Seleccione un estudiante --</option>
                                    <?php 
                                    $stmt = $connect->prepare('SELECT id_estudiante, nombres FROM estudiantes');
                                    $stmt->execute();
                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                        echo '<option value="' . $row["id_estudiante"] . '">' . $row["nombres"] . '</option>';
                                     }
                                            ?>                                            
                                        </select>                                  
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">                            
                                    <select required name="id_curso" class="form-control">
                                    <option value="">-- Seleccione un curso --</option>
                                    <?php 
                                    $stmt = $connect->prepare('SELECT id_curso, nombre_curso FROM asignaturas');
                                    $stmt->execute();
                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                        echo '<option value="' . $row["id_curso"] . '">' . $row["nombre_curso"] . '</option>';
                                    }?>
                                      </select>
                                    </div>
                                </div>
                    </div>








                
                    <div class="form-row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <select required name="id_plantel" class="form-control">
                                    <option value="">-- Seleccione un plantel --</option>
                                    <?php 
                                    $stmt = $connect->prepare('SELECT id_plantel, nombre FROM planteles');
                                    $stmt->execute();
                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                        echo '<option value="' . $row["id_plantel"] . '">' . $row["nombre"] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <select required name="id_periodo" class="form-control">
                                    <option value="">-- Seleccione un periodo --</option>
                                    <?php 
                                    $stmt = $connect->prepare('SELECT id_periodo, numero_periodo FROM periodos');
                                    $stmt->execute();
                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                        echo '<option value="' . $row["id_periodo"] . '">' . $row["numero_periodo"] . '</option>';
                                    }
                                    ?>                                
                                </select>
                            </div>
                        </div>
                </div>


                    <div class="form-row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="calificacion">Calificación</label>
                                <input type="text" name="calificacion" required class="form-control" placeholder="Numérica">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="calificacion_letras">Calificacion</label>
                                <input type="text" name="calificacion_letras" required class="form-control" placeholder="En letras">
                            </div>
                        </div>   
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="te">T-E</label>
                                <input type="text" name="T-E" required class="form-control" placeholder="T-E">
                            </div>
                        </div>
                    </div>



                    <div class="form-row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="mes">Mes</label>
                                <input type="number" name="mes" required class="form-control" placeholder="Mes">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="anio">Año</label>
                                <input type="number" name="año" required class="form-control" placeholder="Año">
                            </div>
                        </div>
                    </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button name="agregar" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                document.getElementById('blah').src = e.target.result;
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>


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
    try {
        // Información enviada por el formulario
        $id_estudiante = trim($_POST['id_estudiante']);
        $id_curso = trim($_POST['id_curso']);
        $id_periodo = trim($_POST['id_periodo']);
        $id_plantel = trim($_POST['id_plantel']);
        $calificacion = trim($_POST['calificacion']);
        $calificacion_letras = trim($_POST['calificacion_letras']);
        $te = trim($_POST['T-E']);
        $mes = trim($_POST['mes']);
        $anio = trim($_POST['año']);

        // Validar que los campos obligatorios no estén vacíos
        if (empty($id_estudiante) || empty($id_curso) || empty($id_periodo) || empty($id_plantel) || empty($calificacion) || empty($calificacion_letras) || empty($te) || empty($mes) || empty($anio)) {
            echo "<div class='content alert alert-danger'> Por favor completa todos los campos obligatorios. </div>";
            return;
        }

        // Consulta para insertar en la tabla
        $consulta = "INSERT INTO calificaciones (
                         id_estudiante, id_curso, id_periodo, id_plantel, 
                         calificacion, calificacion_letras, `T-E`, mes, año
                     ) VALUES (
                         :id_estudiante, :id_curso, :id_periodo, :id_plantel, 
                         :calificacion, :calificacion_letras, :te, :mes, :anio
                     )";

        $sql = $connect->prepare($consulta);

        // Vincular parámetros
        $sql->bindParam(':id_estudiante', $id_estudiante, PDO::PARAM_INT);
        $sql->bindParam(':id_curso', $id_curso, PDO::PARAM_INT);
        $sql->bindParam(':id_periodo', $id_periodo, PDO::PARAM_INT);
        $sql->bindParam(':id_plantel', $id_plantel, PDO::PARAM_INT);
        $sql->bindParam(':calificacion', $calificacion, PDO::PARAM_STR);
        $sql->bindParam(':calificacion_letras', $calificacion_letras, PDO::PARAM_STR);
        $sql->bindParam(':te', $te, PDO::PARAM_STR);
        $sql->bindParam(':mes', $mes, PDO::PARAM_STR);
        $sql->bindParam(':anio', $anio, PDO::PARAM_STR);

        // Ejecutar consulta
        $sql->execute();

        $lastInsertId = $connect->lastInsertId();
        if ($lastInsertId > 0) {
            echo '<script type="text/javascript">
            swal("¡Registrado!", "Agregado correctamente.", "success").then(function() {
                window.location = "mostrar";
            });
            </script>';
        } else {
            echo '<script type="text/javascript">
            swal("ERROR!", "No se pudo agregar el registro.", "error").then(function() {
                window.location = "mostrar";
            });
            </script>';
        }
    } catch (PDOException $e) {
        // Manejo de errores
        echo "<div class='content alert alert-danger'> Error al agregar el registro: " . $e->getMessage() . " </div>";
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
    ////////////// Actualizar la tabla /////////
    $consulta = "DELETE FROM `calificaciones` WHERE `id_calificacion`=:id_calificacion";
$sql = $connect-> prepare($consulta);
$sql -> bindParam(':id_calificacion', $id_calificacion, PDO::PARAM_INT);
$id_calificacion=trim($_POST['id_calificaciones']);
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

print_r($sql->errorInfo()); 
}
}// Cierra envio de guardado
    ?>

<?php
if (isset($_POST['actualizar'])) {
    try {
        if (empty($_POST['idcalif'])) {
            echo "<div class='content alert alert-danger'> ID inválido. </div>";
            return;
        }

        require '../../Config/config.php';
        
        $id_calificacion = trim($_POST['idcalif']);
        $id_estudiante = trim($_POST['id_estudiante']);
        $id_curso = trim($_POST['id_curso']);
        $id_periodo = trim($_POST['id_periodo']);
        $id_plantel = trim($_POST['id_plantel']);
        $calificacion = trim($_POST['calificacion']);
        $calificacion_letras = trim($_POST['calificacion_letras']);
        $te = trim($_POST['te']);
        $mes = trim($_POST['mes']);
        $anio = trim($_POST['año']);

        $consulta = "UPDATE calificaciones SET 
            id_estudiante = :id_estudiante, 
            id_curso = :id_curso, 
            id_periodo = :id_periodo, 
            id_plantel = :id_plantel, 
            calificacion = :calificacion, 
            calificacion_letras = :calificacion_letras, 
            `T-E` = :te, 
            mes = :mes, 
            año = :anio 
            WHERE id_calificacion = :id_calificacion";

        $sql = $connect->prepare($consulta);
        $sql->bindParam(':id_calificacion', $id_calificacion, PDO::PARAM_INT);
        $sql->bindParam(':id_estudiante', $id_estudiante, PDO::PARAM_INT);
        $sql->bindParam(':id_curso', $id_curso, PDO::PARAM_INT);
        $sql->bindParam(':id_periodo', $id_periodo, PDO::PARAM_INT);
        $sql->bindParam(':id_plantel', $id_plantel, PDO::PARAM_INT);
        $sql->bindParam(':calificacion', $calificacion, PDO::PARAM_STR);
        $sql->bindParam(':calificacion_letras', $calificacion_letras, PDO::PARAM_STR);
        $sql->bindParam(':te', $te, PDO::PARAM_STR);
        $sql->bindParam(':mes', $mes, PDO::PARAM_STR);
        $sql->bindParam(':anio', $anio, PDO::PARAM_STR);
        $sql->execute();

        if ($sql->rowCount() > 0) {
            echo '<script>swal("¡Actualizado!", "Registro actualizado.", "success").then(() => { window.location = "mostrar.php"; });</script>';
        } else {
            echo "<div class='content alert alert-warning'> No se realizaron cambios. </div>";
        }
    } catch (PDOException $e) {
        echo "<div class='content alert alert-danger'> Error: " . $e->getMessage() . " </div>";
    }
}


?>
 <script type="text/javascript">
            function showselect(str){
                var xmlhttp; 
                if (str=="")
                  {
                  document.getElementById("txtHint").innerHTML="";
                  return;
                  }
                if (window.XMLHttpRequest)
                  {// code for IE7+, Firefox, Chrome, Opera, Safari
                  xmlhttp=new XMLHttpRequest();
                  }
                else
                  {// code for IE6, IE5
                  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                  }
                xmlhttp.onreadystatechange=function()
                  {
                  if (xmlhttp.readyState==4 && xmlhttp.status==200)
                     {
                     document.getElementById("periodo").innerHTML=xmlhttp.responseText;
                     }
                  }
                xmlhttp.open("GET","../funciones/grado.php?c="+str,true);
                xmlhttp.send();
            }
        </script>
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


