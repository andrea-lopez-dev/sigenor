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
        <title> Porfesores | SIGENOR</title>
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

         <li  class="active">
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
                                        <a href="#">📘 7. REGISTRAR ASIGNATURAS</a>
                                    </li>
                                    <li>
                                        <a href="#">✅ Ejemplo: Matemática, Ciencias, Historia</a>
                                    </li>
                                    <li>
                                        <a href="#">🔹 Paso 1: Abre el módulo "Asignaturas".</a>
                                    </li>
                                    <li>
                                        <a href="#">🔹 Paso 2: Clic en "Nueva Asignatura".</a>
                                    </li>
                                      <li>
                                        <a href="#">🔹 Paso 3: Ingresa nombre del curso y docente asignado.</a>
                                    </li>
                                    <li>
                                        <a href="#">🔹 Paso 4: Asocia a una sección y guarda ✅.</a>
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
          <h2 class="ml-lg-2">Profesores</h2>
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
$pagina = isset($_GET['pagina']) && is_numeric($_GET['pagina']) && $_GET['pagina'] > 0 ? (int)$_GET['pagina'] : 1;

$limit = $productosPorPagina;
$offset = ($pagina - 1) * $productosPorPagina;

try {
    // Conteo total
    $sentencia = $connect->query("SELECT COUNT(*) AS conteo FROM profesores;");
    $conteo = $sentencia->fetchObject()->conteo;

    $paginas = ceil($conteo / $productosPorPagina);

    // Datos de la página actual
    $sentencia = $connect->prepare("SELECT * FROM profesores LIMIT :limit OFFSET :offset");
    $sentencia->bindValue(':limit', $limit, PDO::PARAM_INT);
    $sentencia->bindValue(':offset', $offset, PDO::PARAM_INT);
    $sentencia->execute();
    $productos = $sentencia->fetchAll(PDO::FETCH_OBJ);

} catch (PDOException $e) {
    echo "<div class='alert alert-danger'>Error: " . htmlspecialchars($e->getMessage()) . "</div>";
    exit;
}
?>

<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th>Nombre Apellido</th>
            <th>Cedula</th>
            <th>Sexo</th>
            <th>Correo</th>
            <th>Teléfono</th>
            <th>Foto</th>
            <th>Fecha</th>
            <th>Estado</th>
            <th>Editar</th>
            <th>Eliminar</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($productos as $producto): ?>
        <tr>
            <td><?php echo htmlspecialchars($producto->nombre_apellido); ?></td>
            <td><?php echo htmlspecialchars($producto->cedula_profesor); ?></td>
            <td><?php echo htmlspecialchars($producto->sexo); ?></td>
            <td><?php echo htmlspecialchars($producto->correo_profesor); ?></td>
            <td><?php echo htmlspecialchars($producto->telefono_profesor); ?></td>
            <td>
                <?php if (file_exists("../../Assets/img/subidas/" . $producto->foto) && !empty($producto->foto)): ?>
                    <img src="../../Assets/img/subidas/<?php echo htmlspecialchars($producto->foto); ?>" width='90'>
                <?php else: ?>
                    <img src="../../Assets/img/subidas/default.png" width='90' alt="Sin imagen">
                <?php endif; ?>
            </td>
            <td><?php echo htmlspecialchars($producto->fecha); ?></td>
            <td>
                <?php if ($producto->estado == 1): ?>
                    <span class="badge badge-success">Activo</span>
                <?php else: ?>
                    <span class="badge badge-danger">No activo</span>
                <?php endif; ?>
            </td>
            <td>
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                    <input type="hidden" name="idtea" value="<?php echo htmlspecialchars($producto->id_profesor); ?>">
                    <button name="editar" class="btn btn-warning text-white">
                        <i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i>
                    </button>
                </form>
            </td>
            <td>
                <form onsubmit="return confirm('¿Está seguro de eliminar este registro?');" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                    <input type="hidden" name="idtea" value="<?php echo htmlspecialchars($producto->id_profesor); ?>">
                    <button name="eliminar" class="btn btn-danger text-white">
                        <i class="material-icons" title="Delete">&#xE872;</i>
                    </button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>


<nav aria-label="Page navigation example">
  <div class="row">
    <div class="col-xs-12 col-sm-6">
      <p>Mostrando <?php echo $productosPorPagina ?> de <?php echo $conteo ?> profesores disponibles</p>
    </div>
    <div class="col-xs-12 col-sm-6">
      <p>Página <?php echo $pagina ?> de <?php echo $paginas ?> </p>
    </div>
  </div>
  <ul class="pagination">
    <?php if ($pagina > 1) { ?>
      <li>
        <a href="./mostrar?pagina=<?php echo $pagina - 1 ?>">
          <span aria-hidden="true">&laquo;</span>
        </a>
      </li>
    <?php } ?>
    <?php for ($x = 1; $x <= $paginas; $x++) { ?>
      <li class="<?php if ($x == $pagina) echo "active" ?>">
        <a href="./mostrar?pagina=<?php echo $x ?>">
          <?php echo $x ?></a>
      </li>
    <?php } ?>
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
require '../../Config/config.php';

if (isset($_POST['editar'])) {
    $id_profesor = $_POST['idtea'];
    $sql = "SELECT * FROM profesores WHERE id_profesor = :id_profesor"; 
    $stmt = $connect->prepare($sql);
    $stmt->bindParam(':id_profesor', $id_profesor, PDO::PARAM_INT); 
    $stmt->execute();
    $obj = $stmt->fetchObject();
?>

<div class="col-12 col-md-12"> 
    <form role="form" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <input value="<?php echo $obj->id_profesor; ?>" name="id_profesor" type="hidden">
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="nombre_apellido">Nombre y apellidos</label>
                <input value="<?php echo $obj->nombre_apellido; ?>" name="nombre_apellido" type="text" placeholder="Nombre y apellidos" class="form-control">
            </div>
            <div class="form-group col-md-6">
                <label for="cedula_profesor">Cedula</label>
                <input value="<?php echo $obj->cedula_profesor; ?>"  name="cedula_profesor" type="text" class="form-control" placeholder="Cédula">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="sexo">Sexo</label>
                <select required name="sexo" class="form-control">                        
                    <option value="Masculino">Masculino</option>
                    <option value="Femenino">Femenino</option>
                </select>
            </div>
            <div class="form-group col-md-6">
                <label for="correo_profesor">Correo</label>
                <input value="<?php echo $obj->correo_profesor; ?>" name="correo_profesor" type="email" class="form-control" placeholder="Correo">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="telefono_profesor">Teléfono</label>
                <input value="<?php echo $obj->telefono_profesor; ?>" name="telefono_profesor" maxlength="12" onKeyPress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" type="text" class="form-control" placeholder="Teléfono móvil">
            </div>
        </div>

        <div class="form-group">
            <button name="actualizar" type="submit" class="btn btn-primary btn-block">Actualizar Registro</button>
        </div>
    </form>
</div>

<?php } ?>

<div id="addEmployeeModal" class="modal" tabindex="-1" role="dialog" aria-labelledby="myTitle" aria-hidden="true">
    <div class="modal-dialog">
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
                <div id="step1"> 
                    <div class="form-row">                     
                            <div class="col-sm-6">
                                <div class="form-group">                                 
                                    <div class="input-group">       
                                        <input type="text"  name="txtnom" placeholder="Nombre y apellidos" required class="form-control"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="text"  name="txtdni" required class="form-control" placeholder="Cedula" />
                                    </div>
                                </div>
                            </div>
                    </div>


                    <div class="form-row">
                    <div class="col-sm-6">
                                <div class="form-group">
                                    
                                    <div class="input-group">
                                        <select class="form-control" required name="txtsex">
                                          <option selected>GÉNERO</option>
                                          <option value="Masculino">Masculino</option>
                                          <option value="Femenino">Femenino</option>
                                         
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    
                                    <div class="input-group">
                                       
                                        <input type="email"  name="txtcorr" required class="form-control" placeholder="Correo" />
                                    </div>
                                </div>
                            </div>
                         
                    </div>

                    <button id="btnEndStep1">NEXT</button>
                </div>
                <div id="step2" class="hideMe"> 
                   <div class="form-row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    
                                    <div class="input-group">
                                       
                                        <input type="text"  name="txttel" maxlength="12" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" required class="form-control" placeholder="Teléfono" />
                                    </div>
                                </div>
                            </div>           
                    </div> 


                     
                    <div class="form-row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="modal_contact_firstname">Foto</label>
                                    <div class="input-group">
                                     <input type="file" id="imagen" name="foto" onchange="readURL(this);" data-toggle="tooltip">
                 <img id="blah"  alt="your image" style="max-width:90px;" />
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
                        <button  type="submit" name='agregar' class="btn btn-primary">GUARDAR</button>
                </div>
                </div>
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
 if(isset($_POST['agregar'])) {
   $nomte = $_POST['txtnom'];
   $dnite = $_POST['txtdni'];
   $sexte = $_POST['txtsex'];
   $correo = $_POST['txtcorr'];
   $telet = $_POST['txttel'];

   $imgFile = $_FILES['foto']['name'];
   $tmp_dir = $_FILES['foto']['tmp_name'];
   $imgSize = $_FILES['foto']['size'];

   if (empty($nomte)) {
       $errMSG = "Please enter your dni.";
   } else if (empty($dnite)) {
       $errMSG = "Please Enter your name.";
   } else if (empty($sexte)) {
       $errMSG = "Please Enter your sexo.";
   } else if (empty($correo)) {
       $errMSG = "Please Enter your email.";
   } else if (empty($telet)) {
       $errMSG = "Please Enter your phone.";
   } else if (empty($imgFile)) {
       $errMSG = "Please Select Image File.";
   } else {
       $upload_dir = '../../Assets/img/subidas/'; // upload directory
       $imgExt = strtolower(pathinfo($imgFile, PATHINFO_EXTENSION)); // get image extension
       $valid_extensions = array('jpeg', 'jpg', 'png', 'gif'); // valid extensions
       $foto = rand(1000, 1000000) . "." . $imgExt;

       if (in_array($imgExt, $valid_extensions)) {
           if ($imgSize < 5000000) {
               move_uploaded_file($tmp_dir, $upload_dir . $foto);
           } else {
               $errMSG = "Sorry, your file is too large.";
           }
       } else {
           $errMSG = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
       }
   }

   if (!isset($errMSG)) {
       $stmt = $connect->prepare("INSERT INTO profesores (nombre_apellido, cedula_profesor, sexo, correo_profesor, telefono_profesor, foto, estado ) VALUES (:nombre_apellido, :cedula_profesor, :sexo, :correo_profesor, :telefono_profesor, :foto, '1')");
       $stmt->bindParam(':nombre_apellido', $nomte);
       $stmt->bindParam(':cedula_profesor', $dnite);
       $stmt->bindParam(':sexo', $sexte);
       $stmt->bindParam(':correo_profesor', $correo);
       $stmt->bindParam(':telefono_profesor', $telet);
       $stmt->bindParam(':foto', $foto);

       if ($stmt->execute()) {
           echo '<script type="text/javascript">
swal("¡Registrado!", "Agregado correctamente", "success").then(function() {
               window.location = "mostrar";
           });
           </script>';
       } else {
           $errMSG = "Error while inserting...";
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
$consulta = "DELETE FROM `profesores` WHERE `id_profesor`=:id_profesor";
$sql = $connect-> prepare($consulta);
$sql -> bindParam(':id_profesor', $idtea, PDO::PARAM_INT);
$idtea=trim($_POST['idtea']);
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
    
if(isset($_POST['actualizar'])){
///////////// Informacion enviada por el formulario /////////////
$idtea=trim($_POST['id_profesor']);
$dnite=trim($_POST['nombre_apellido']);
$nomte=trim($_POST['cedula_profesor']);
$sexte=trim($_POST['sexo']);
$correo=trim($_POST['correo_profesor']);
$telet=trim($_POST['telefono_profesor']);

///////// Fin informacion enviada por el formulario /// 

////////////// Actualizar la tabla /////////
$consulta = "UPDATE profesores
SET `nombre_apellido`= :nombre_apellido, `cedula_profesor` = :cedula_profesor, `sexo` = :sexo, `correo_profesor` = :correo_profesor, `telefono_profesor` = :telefono_profesor WHERE `id_profesor` = :id_profesor";
$sql = $connect->prepare($consulta);
$sql->bindParam(':nombre_apellido',$dnite,PDO::PARAM_STR, 25);
$sql->bindParam(':cedula_profesor',$nomte,PDO::PARAM_STR, 25);
$sql->bindParam(':sexo',$sexte,PDO::PARAM_STR,25);
$sql->bindParam(':correo_profesor',$correo,PDO::PARAM_STR,25);
$sql->bindParam(':telefono_profesor',$telet,PDO::PARAM_STR,25);
$sql->bindParam(':id_profesor',$idtea,PDO::PARAM_INT);

$sql->execute();

if($sql->rowCount() > 0)
{
$count = $sql -> rowCount();
echo '<script type="text/javascript">
swal("¡Actualizado!", "Actualizado correctamente", "success").then(function() {
            window.location = "mostrar";
        });
        </script>';
}
else{
    echo "<div class='content alert alert-danger'> No se pudo actulizar el registro  </div>";

print_r($sql->errorInfo()); 
}
}// Cierra envio de guardado
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
  <script type="text/javascript">
      $("#btnEndStep1").click(function () {
    $("#step1").addClass('hideMe');
    $("#step2").removeClass('hideMe');
});
$("#btnEndStep2").click(function () {
    $("#step2").addClass('hideMe');
    $("#step3").removeClass('hideMe');
});
$("#btnEndStep3").click(function () {
    // Whatever your final validation and form submission requires
    $("#sampleModal").modal("hide");
});
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


