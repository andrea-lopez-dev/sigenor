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
        <title>Asignaturas | SIGENOR</title>
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

         <li  class="active">
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
                    <div class="col-md-5 col-lg-3 order-3 order-md-2">
                        <div class="xp-searchbar">
                            
                        </div>
                    </div>
                    <!-- End XP Col -->

                    <!-- Start XP Col -->
                    <div class="col-10 col-md-6 col-lg-8 order-1 order-md-3">
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
  <a href="#">📘 8. REGISTRAR ASISTENCIAS</a>
</li>
<li>
  <a href="#">✅ Ejemplo: Nº de asistencias y Nº de inasistencias o I</a>
</li>
<li>
  <a href="#">🔹 Paso 1: Abre el módulo "Asistencias".</a>
</li>
<li>
  <a href="#">🔹 Paso 2: Selecciona  el estudiante y anexa la asignatura</a>
</li>
<li>
  <a href="#">🔹 Paso 3: Registra sucesivamnete la asistencia de cada estudiante.</a>
</li>
<li>
  <a href="#">🔹 Paso 4: Guarda los registros ✅.</a>
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
          <h2 class="ml-lg-2">Asignaturas</h2>
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

// Consulta para obtener el conteo total de asignaturas
$sentencia = $connect->query("SELECT COUNT(*) AS conteo FROM asignaturas;");
$conteo = $sentencia->fetchObject()->conteo ?? 0; // Si no hay asignaturas, asigna 0

// Definir el número de páginas, asegurando que al menos sea 1
$paginas = ($conteo > 0) ? ceil($conteo / $productosPorPagina) : 1;

// Consulta para obtener las asignaturas con paginación
$sentencia = $connect->prepare("
    SELECT asignaturas.id_curso, asignaturas.id_profesor, asignaturas.id_periodo, asignaturas.nombre_curso, 
           asignaturas.fecha, asignaturas.estado, 
           profesores.nombre_apellido
    FROM asignaturas 
    INNER JOIN periodos ON asignaturas.id_periodo = periodos.id_periodo 
    INNER JOIN profesores ON asignaturas.id_profesor = profesores.id_profesor 
    LIMIT ? OFFSET ?");
$sentencia->execute([$limit, $offset]);
$productos = $sentencia->fetchAll(PDO::FETCH_ASSOC);
        ?>
    <table class="table table-striped table-hover" id="myTable">
      <thead>
        <tr>
          <th>Asignatura</th>  
          <th>Profesor Asignado</th>    
          <th>fecha</th>
          <th>estado</th>
          <th>Editar</th>
          <th>Eliminar</th>
        </tr>
      </thead>
      <tbody>
          <?php foreach($productos as $producto){ ?>
            <tr>       
            <td><?php echo $producto['nombre_curso'];?></td>      
               <td><?php echo $producto['nombre_apellido']; ?></td>
                             <td><?php echo $producto['fecha'];?></td>
               <td>
          <?php if ($producto['estado'] == 1) { ?> 
            <span class="badge badge-success">Activo</span>
          <?php } else { ?> 
            <span class="badge badge-danger">No activo</span>
          <?php } ?>  
        </td>
               <td>
<form method='POST' action='<?php $_SERVER['PHP_SELF'] ?>'>
<input type='hidden' name='idcur' value="<?php echo  $producto['id_curso']; ?>">
<button name='editar' class='btn btn-warning text-white'><i class='material-icons' data-toggle='tooltip' title='Edit'>&#xE254;</i></button>
</form>
                   
               </td>
               <td>
<form  onsubmit="return confirm('Realmente desea eliminar el registro?');" method='POST' action='<?php $_SERVER['PHP_SELF'] ?>'>
<input type='hidden' name='idcur' value="<?php echo  $producto['id_curso']; ?>">
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

                    <p>Mostrando <?php echo $productosPorPagina ?> de <?php echo $conteo ?> cursos disponibles</p>
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

if (isset($_POST['editar'])){
$idcur = $_POST['idcur'];
$sql= "SELECT asignaturas.id_curso, asignaturas.id_profesor, asignaturas.id_periodo, asignaturas.nombre_curso, asignaturas.descripcion, asignaturas.fecha, asignaturas.estado, profesores.nombre_apellido, periodos.nombre_periodo, count(*) AS conteo FROM asignaturas INNER JOIN periodos ON asignaturas.id_periodo = periodos.id_periodo INNER JOIN profesores ON asignaturas.id_profesor = profesores.id_profesor WHERE asignaturas.id_curso = :id_curso"; 
$sql = $connect->prepare($sql);
$sql->bindParam(':id_curso', $idcur, PDO::PARAM_INT); 
$sql->execute();
$obj = $sql->fetchObject();

$id_curso = isset($obj->id_curso) ? $obj->id_curso : '';
$nombre_curso = isset($obj->nombre_curso) ? $obj->nombre_curso : '';
$nombre_apellido = isset($obj->nombre_apellido) ? $obj->nombre_apellido : '';

$fecha = isset($obj->fecha) ? $obj->fecha : '';
$estado= isset($obj->estado) ? $obj->estado : '';
?>

    <div class="col-12 col-md-12"> 

<form role="form" method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>">
    <input value="<?php echo $id_curso;?>" name="id_curso" type="hidden">
  <div class="form-row">

    <div class="form-group col-md-6">
      <label for="edad">Asignatura </label>
      <input value="<?php echo $nombre_curso;?>" name="nomcur" type="text" class="form-control">
  </div>
  <div class="form-group col-md-6">
      <label for="edad">Profesor Asignado </label>
      <select required name="nomprof"  class="form-control">
                                            <?php
                                            $sql = $connect->prepare('SELECT profesores.id_profesor, profesores.nombre_apellido FROM profesores');
                                            $sql->execute();

                                            while($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                                                echo '<option value="' . $row["id_profesor"] . '">' . $row["nombre_apellido"] . '</option>';
                                            }
                                            ?>
                                        </select>
  </div>
  <div class="form-group col-md-12">
     
  </div>
  
 
  <div class="form-group col-md-6">
      <label for="edad">Fecha </label>
      <input value="<?php echo $fecha;?>" name="fecha" type="date-time" class="form-control">
      </div>
  <div class="form-group col-md-6">
    <label for="estado">Estado</label>
    <select class="form-control" name="estado" id="estado">
        <option value="Activo" <?php echo ($estado == "Activo") ? "selected" : ""; ?>>Activo</option>
        <option value="Inactivo" <?php echo ($estado == "Inactivo") ? "selected" : ""; ?>>Inactivo</option>
    </select>
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
                                    <label for="modal_contact_firstname">Asignatura</label>
                                    <div class="input-group">
                                       
                                        <input type="text"  name="txtnomcur" required class="form-control" placeholder="Nombre de la asignatura" />
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="modal_contact_lastname">Asignar Profesor</label>
                                    <div class="input-group">
                                       <select required name="txtidprf"  class="form-control">
                                            <?php
                                            $sql = $connect->prepare('SELECT profesores.id_profesor, profesores.nombre_apellido FROM profesores');
                                            $sql->execute();

                                            while($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                                                echo '<option value="' . $row["id_profesor"] . '">' . $row["nombre_apellido"] . '</option>';
                                            }
                                            ?>
                                        </select>
                                                                    
                                  
                                </div>
                            </div>
                        </div>

                        <div class="form-row">                         
                            <div class="col-sm-6">
                                <div class="form-group">
                  
                                    <div class="input-group">
                                     
                                                                    
                    
                                </div>
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
<div class="col-sm-6">
                            <div class="form-group">
                            <label for="modal_contact_lastname">Estado</label>
                                    <div class="input-group">
                                        <select class="form-control" required name="txtesta">
                                          <option selected>SELECCIONE</option>
                                          <option value="1">Activo</option>
                                          <option value="0">Inactivo</option>
                                        </select>
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
    $nomcur = $_POST['txtnomcur'];
    $idprf = $_POST['txtidprf'];
 
    $fecha = $_POST['txtfecha'];
    $estado = $_POST['txtesta'];

    // Validación de campos
    if (empty($nomcur)) {
        $errMSG = "Por favor ingresa el nombre del curso.";
    } else if (empty($idprf)) {
        $errMSG = "Por favor ingresa el ID del profesor.";
   
    } else if (empty($fecha)) {
        $errMSG = "Por favor ingresa la fecha.";
    } else if (empty($estado)) {
        $errMSG = "Por favor ingresa el estado.";
    }

    // Si no hay errores, continuar con el proceso
    if (!isset($errMSG)) {
        try {
            // Consulta SQL corregida
            $stmt = $connect->prepare("INSERT INTO asignaturas (nombre_curso, id_profesor, id_periodo, descripcion, fecha, estado) 
                                       VALUES (:nombre_curso, :id_profesor, :id_periodo, :descripcion, :fecha, :estado)");
            // Vincular parámetros
            $stmt->bindParam(':nombre_curso', $nomcur);
            $stmt->bindParam(':id_profesor', $idprf);
    
            $stmt->bindParam(':fecha', $fecha);
            $stmt->bindParam(':estado', $estado);

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
////////////// Actualizar la tabla /////////
$consulta = "DELETE FROM `asignaturas` WHERE `id_curso`=:id_curso";
$sql = $connect-> prepare($consulta);
$sql -> bindParam(':id_curso', $idcur, PDO::PARAM_INT);
$idcur=trim($_POST['idcur']);
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
    // Información enviada por el formulario
      $id_curso = trim($_POST['id_curso']);
    $nomcur = trim($_POST['nomcur']);
    $nomprof = trim($_POST['nomprof']);
    $nomperiod = trim($_POST['nomperiod']);
    $descrip = trim($_POST['descrip']);
    $fecha = trim($_POST['fecha']);
    $estado = trim($_POST['estado']);

    // Validar que los campos no estén vacíos
    if (empty($nomcur) ||  empty($descrip) ||  empty($nomperiod) ||  empty($nomprof) || empty($fecha) || empty($estado)) {
        echo "<div class='content alert alert-danger'> Por favor completa todos los campos obligatorios. </div>";
    } else {
        try {

$consulta = "UPDATE asignaturas 
             SET nombre_curso = :nombre_curso, 
                 id_profesor = :id_profesor, 
                 id_periodo = :id_periodo, 
                 descripcion = :descripcion, 
                 fecha = :fecha, 
                 estado = :estado
             WHERE id_curso = :id_curso";

$sql = $connect->prepare($consulta);

$sql->bindParam(':nombre_curso', $nomcur, PDO::PARAM_STR, 25);
$sql->bindParam(':id_profesor', $nomprof, PDO::PARAM_STR, 25);
$sql->bindParam(':id_periodo', $nomperiod, PDO::PARAM_STR, 25);
$sql->bindParam(':descripcion', $descrip, PDO::PARAM_STR);
$sql->bindParam(':fecha', $fecha, PDO::PARAM_STR, 25);
$sql->bindParam(':estado', $estado, PDO::PARAM_STR, 25);
$sql->bindParam(':id_curso', $id_curso, PDO::PARAM_INT);

$sql->execute();


            // Verificar si se actualizó alguna fila
            if ($sql->rowCount() > 0) {
                echo '<script type="text/javascript">
                swal("¡Actualizado!", "El registro ha sido actualizado correctamente.", "success").then(function() {
                    window.location = "mostrar";
                });
                </script>';
            } else {
                echo "<div class='content alert alert-warning'> No se realizaron cambios en el registro. </div>";
            }
        } catch (PDOException $e) {
            echo "<div class='content alert alert-danger'> Error al actualizar: " . $e->getMessage() . " </div>";
        }
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
  </body>
  
  </html>


