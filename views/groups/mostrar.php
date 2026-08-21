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
        <title>Seccion | SIGENOR</title>
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


            <div class="main-content">
              <div class="row">
                
                <div class="col-md-12">
                <div class="table-wrapper">
    <div class="table-title">
      <div class="row">
        <div class="col-sm-6 p-0 d-flex justify-content-lg-start justify-content-center">
          <h2 class="ml-lg-2">Sección</h2>
        </div>

        <div class="col-sm-12 p-0 d-flex justify-content-lg-end justify-content-center">
          <a href="#addEmployeeModal" class="btn btn-success" data-toggle="modal">
          <i class="material-icons">&#xE147;</i> </a>

         
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
$limit = (int) $productosPorPagina;
$offset = (int) (($pagina - 1) * $productosPorPagina);


// Consulta para el conteo total de estudiantes
$sentencia = $connect->query("SELECT 
    seccion.id_seccion, 
    seccion.nombre_seccion, 
    seccion.capacidad, 
    seccion.fecha, 
    seccion.estado,
    periodos.id_periodo,   
    periodos.nombre_periodo, COUNT(*) AS conteo
FROM seccion
INNER JOIN periodos ON seccion.id_periodo = periodos.id_periodo;
");

$conteo = $sentencia->fetchObject()->conteo;
$paginas = ceil($conteo / $productosPorPagina);



// Definir y asignar un valor a la variable $id_estudiante


// Verificar que $id_estudiante tiene un valor antes de ejecutar la consulta

    // Consulta para obtener los datos de los estudiantes
    $sentencia = $connect->prepare("SELECT 
    seccion.id_seccion, 
    seccion.nombre_seccion, 
    seccion.capacidad, 
    seccion.fecha, 
    seccion.estado,
    periodos.id_periodo,   
    periodos.nombre_periodo
FROM seccion
INNER JOIN periodos ON seccion.id_periodo = periodos.id_periodo;
");


$sentencia->execute();
$productos = $sentencia->fetchAll(PDO::FETCH_ASSOC);

?>


    <table class="table table-striped table-hover">
      <thead>
      <tr>
            <th>Periodo</th>
            <th>Seccion</th>
            <th>Capacidad</th>
            <th>Fecha</th>
            <th>Estado</th>
            <th>Entrar</th>
            <th>Imprimir</th>
            <th>Editar</th>
            <th>Eliminar</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $productos = isset($productos) && is_array($productos) ? $productos : []; 
        foreach ($productos as $producto) { ?>
        <tr>
            <td><span class="badge badge-danger"><?php echo htmlspecialchars($producto['nombre_periodo']); ?></span></td>
            <td><?php echo htmlspecialchars($producto['nombre_seccion']); ?></td>
            <td><?php echo htmlspecialchars($producto['capacidad']); ?></td>
            <td><?php echo htmlspecialchars($producto['fecha']); ?></td>
            <td>
                <?php if ($producto['estado'] == 1) { ?> 
                <span class="badge badge-success">Activo</span>
                <?php } else { ?> 
                <span class="badge badge-danger">No activo</span>
                <?php } ?>
            </td>
            <td>
                <a href="entrar?id=&periodo=<?php echo htmlspecialchars($producto['id_periodo']); ?>" 
                   class="btn btn-primary text-white">
                   <i class="material-icons" data-toggle="tooltip" title="Entrar">login</i>
                </a>
            </td>
            <td>
<a href="plantilla.php?id=<?php echo htmlspecialchars($producto['id_seccion']); ?>&periodo=<?php echo htmlspecialchars($producto['id_periodo']); ?>" 
   class="btn btn-danger text-white">
   <i class="material-icons" data-toggle="tooltip" title="Imprimir">print</i>
</a>
</td>

            <td>
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                    <input type="hidden" name="idsec" value="<?php echo htmlspecialchars($producto['id_seccion']); ?>">
                    <button name="editar" class="btn btn-warning text-white">
                        <i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i>
                    </button>
                </form>
            </td>
            <td>
                <form onsubmit="return confirm('Realmente desea eliminar el registro?');" 
                      method="POST" 
                      action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                    <input type="hidden" name="idsec" value="<?php echo htmlspecialchars($producto['id_seccion']); ?>">
                    <button name="eliminar" class='btn btn-square btn-outline-danger m-2 text-red'>
                        <i class="material-icons" title="Delete">&#xE872;</i>
                    </button>
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
if (isset($_POST['editar'])){
    $idsec = $_POST['idsec'];
    $sql= "SELECT seccion.id_seccion, 
        seccion.nombre_seccion, 
        seccion.capacidad, 
        seccion.fecha, 
        seccion.estado,
        periodos.nombre_periodo
    FROM seccion
    INNER JOIN periodos ON seccion.id_periodo = periodos.id_periodo WHERE seccion.id_seccion = :id_seccion"; 
    $stmt = $connect->prepare($sql);
    $stmt->bindParam(':id_seccion', $idsec, PDO::PARAM_INT); 
    $stmt->execute();
    $obj = $stmt->fetchObject();
     

?>

<div class="col-12 col-md-12"> 

<form role="form" method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>">
    <input value="<?php echo $obj->id_seccion;?>" name="idsec" type="hidden">
  <div class="form-row">
    <div class="form-group col-md-6">
      <label for="nombres">Sección</label>

      <select required name="nomsec" class="form-control">
    <option value="<?php echo $obj->nombre_seccion;?>"><?php echo $obj->nombre_seccion;?></option>
    </select>
      
    </div>
    <div class="form-group col-md-6">
      <label for="edad">Periodo</label>
      <select required name="idtea" class="form-control">

    <?php 
    $stmt = $connect->prepare('SELECT * FROM periodos');
    $stmt->execute();

while($row=$stmt->fetch(PDO::FETCH_ASSOC))
        {
            extract($row);
            ?>
            <option value="<?php echo $id_periodo; ?>"><?php echo $nombre_periodo; ?></option>
            <?php
        }
        ?>
 
    
    </select>
    </div>
  </div>

  <div class="form-row">
    <div class="form-group col-md-6">
      <label for="nombres">Capacidad</label>

      <input maxlength="2"onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" value="<?php echo $obj->capacidad;?>" name="capa" type="text" class="form-control">
      
    </div>
    
    <div class="form-group col-md-6">
      <label for="nombres">Fecha</label>

      <input type="datetime-local" value="<?php echo $obj->fecha;?>"  required name="fecha" class="form-control" >
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
                                    <label for="modal_contact_firstname">Periodo</label>
                                    <div class="input-group">

                                       <select required id="periodo" class="form-control" name="periodo">                  
                                            <option value="" disabled="" selected="">Selecciona el periodo</option>
                                       <?php 
                                       $stmt = $connect->prepare('SELECT * FROM periodos');
                                        $stmt->execute();

                                        while($row=$stmt->fetch(PDO::FETCH_ASSOC))
                                        {
                                            extract($row);
                                            ?>

                                            <option value="<?php echo $id_periodo; ?>"><?php echo $nombre_periodo; ?></option>
                                            <?php
                                        }
                                        ?>
                                      </select>
                                        
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="modal_contact_lastname">Seccion</label>
                                    <div class="input-group">
                                      <input type="text "required id="seccion" name="seccion" class="form-control" placeholder="Nombre de la seccion"/>
                                                       
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="form-row">
                        <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="modal_contact_firstname">Capacidad</label>
                                    <div class="input-group">
                                       
                                        <input type="text" maxlength="2" id="capacidad" name="capacidad" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" required class="form-control" placeholder="Capacidad" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="modal_contact_firstname">Fecha</label>
                                    <div class="input-group">
                                       
                                        <input type="datetime-local" id="fecha" name="fecha" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" required class="form-control" />
                                    </div>
                                </div>
                            </div>
                            
                        </div>                 
                        <div class="form-group">
                            <label for="modal_contact_lastname">Estado</label>
                                    <div class="input-group">
                                        <select class="form-control" required id="estado" name="estado">
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
    try {
        // Información enviada por el formulario
        $periodo = trim($_POST['periodo']);
        $seccion = trim($_POST['seccion']);
        $capacidad = trim($_POST['capacidad']);
        $fecha = trim($_POST['fecha']);
        $estado = trim($_POST['estado']);

        // Validar que los campos obligatorios no estén vacíos
        if (empty( $periodo) || empty($seccion) || empty($capacidad) || empty($fecha)) {
            echo "<div class='content alert alert-danger'> Por favor completa todos los campos obligatorios. </div>";
            return;
        }

        // Consulta para insertar en la tabla
        $consulta = "INSERT INTO seccion (
                         id_seccion, id_periodo, nombre_seccion, capacidad, fecha, estado) VALUES (:id_seccion,:id_periodo, :nombre_seccion, 
                         :capacidad, :fecha, :estado)";

        $sql = $connect->prepare($consulta);

       // Vincular parámetros
$sql->bindParam(':id_seccion', $id_seccion, PDO::PARAM_INT);
$sql->bindParam(':id_periodo', $periodo, PDO::PARAM_INT); // ← Esto está corregido
$sql->bindParam(':nombre_seccion', $seccion, PDO::PARAM_STR); // ← También estaba mal el tipo
$sql->bindParam(':capacidad', $capacidad, PDO::PARAM_STR);
$sql->bindParam(':fecha', $fecha, PDO::PARAM_STR);
$sql->bindParam(':estado', $estado, PDO::PARAM_STR);


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
    $consulta = "DELETE FROM `seccion` WHERE `id_seccion`=:id_seccion";
$sql = $connect-> prepare($consulta);
$sql -> bindParam(':id_seccion', $idsec, PDO::PARAM_INT);
$idsec =trim($_POST['idsec']);
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
        // Verificar que las claves estén definidas
        if (empty($_POST['idsec'])) {
            echo "<div class='content alert alert-danger'> ID inválido. </div>";
            return;
        }

        require '../../Config/config.php';

        $idsec = isset($_POST['idsec']) ? trim($_POST['idsec']) : null;
        $periodo = isset($_POST['periodo']) ? trim($_POST['periodo']) : null;
        $seccion = isset($_POST['seccion']) ? trim($_POST['seccion']) : null;
        $capacidad = isset($_POST['capacidad']) ? trim($_POST['capacidad']) : null;
        $fecha = isset($_POST['fecha']) ? trim($_POST['fecha']) : null;
        $estado = isset($_POST['estado']) ? trim($_POST['estado']) : null;
        
      
        

        // Consulta corregida con nombres de parámetros correctos
        $consulta = "UPDATE seccion SET 
            id_periodo = :id_periodo, 
            nombre_seccion = :nombre_seccion, 
            capacidad = :capacidad, 
            fecha = :fecha, 
            estado = :estado        
            WHERE id_seccion = :id_seccion";

        $sql = $connect->prepare($consulta);

        // Vincular parámetros con el tipo correcto
        $sql->bindParam(':id_seccion', $idsec, PDO::PARAM_INT);
        $sql->bindParam(':id_periodo', $periodo, PDO::PARAM_INT);
        $sql->bindParam(':nombre_seccion', $seccion, PDO::PARAM_STR);
        $sql->bindParam(':capacidad', $capacidad, PDO::PARAM_INT);
        $sql->bindParam(':fecha', $fecha, PDO::PARAM_STR);
        $sql->bindParam(':estado', $estado, PDO::PARAM_STR);

        // Ejecutar la consulta
        $sql->execute();

        // Verificar si se actualizó algún registro
        if ($sql->rowCount() > 0) {
            echo '<script>swal("¡Actualizado!", "Registro actualizado.", "success").then(() => { window.location = "mostrar.php"; });</script>';
        } else {
            echo "<div class='content alert alert-warning'> No se realizaron cambios. </div>";
        }
    } catch (PDOException $e) { 
        echo "<div class='content alert alert-danger'> Error: " . htmlspecialchars($e->getMessage()) . " </div>";
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


