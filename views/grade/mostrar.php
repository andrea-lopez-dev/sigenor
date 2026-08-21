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
        <title>Plan administrativo | SIGENOR</title>
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

         <li  class="active">
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
                    <div class="col-10 col-md-6 col-lg-8 order-1 order-md-3" style="margin-left: 281px;">
                        <div class="xp-profilebar text-right">
							 <nav class="navbar p-0">
                        <ul class="nav navbar-nav flex-row ml-auto">   
                            <li class="dropdown nav-item active">
                                <a href="#" class="nav-link" data-toggle="dropdown">
                                   <span class="material-icons">notifications</span>
								   <span class="notification">4 </span>
                               </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="#">🔟 IMPRIMIR DOCUMENTOS IMPORTANTES 🖨️📑</a>
                                    </li>
                                    <li>
                                        <a href="#">✅ Una vez que ya registraste toda la información anterior (usuarios, planteles, periodos, estudiantes, profesores, asignaturas, asistencias, calificaciones y plan administrativo)...</a>
                                    </li>
                                    <li>
                                        <a href="#">📌 Asegúrate de tener todos los filtros correctamente seleccionados (🗓️ periodo, 🏫 plantel, 📖 asignatura, 📘 sección) antes de imprimir.</a>
                                    </li>
                                    <li>
                                        <a href="#">🔚 ¡Y listo! Tus documentos estarán listos para entregar o archivar. 🎉</a>
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
          <h2 class="ml-lg-2">Plan Administrativo</h2>
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
        $limit = $productosPorPagina;
        $offset = ($pagina - 1) * $productosPorPagina;

        $sentencia = $connect->query("SELECT plan_administrativo.id_plan_est, plan_administrativo.plan_estudio, plan_administrativo.codigo_estudio, plan_administrativo.estrategia_estudio, plan_administrativo.tipo_evaluacion, plan_administrativo.descripcion, plan_administrativo.fecha_estudio, count(*) AS conteo FROM plan_administrativo");
    $conteo = $sentencia->fetchObject()->conteo;
    $paginas = ceil($conteo / $productosPorPagina);
    $sentencia = $connect->prepare("SELECT plan_administrativo.id_plan_est, plan_administrativo.plan_estudio, plan_administrativo.codigo_estudio,  plan_administrativo.estrategia_estudio, plan_administrativo.tipo_evaluacion, plan_administrativo.descripcion, plan_administrativo.fecha_estudio, count(*) AS conteo FROM plan_administrativo LIMIT ? OFFSET ?");
    $sentencia->execute([$limit, $offset]);
    $productos = $sentencia->fetchAll(PDO::FETCH_OBJ);
       ?>

    <table class="table table-striped table-hover">
      <thead>
        <tr>
        
          <th>codigo</th>
          <th>plan de estudio</th>
          <th>estategia de estudio</th>
          <th>tipo de evaluacion</th>
          <th>descripcion</th>
          <th>fecha</th>
          <th>Editar</th>
          <th>Eliminar</th>
        </tr>
      </thead>
      <tbody>
          <?php foreach($productos as $persona){ ?>
            <tr>
               <td><?php echo $persona->codigo_estudio ?></td>
               <td><?php echo $persona->plan_estudio ?></td>
               <td><?php echo $persona->estrategia_estudio ?></td>
                <td><?php echo $persona->tipo_evaluacion ?></td>
                <td><?php echo $persona->descripcion ?></td>
               <td><?php echo $persona->fecha_estudio ?></td>
               <td>
<form method='POST' action='<?php $_SERVER['PHP_SELF'] ?>'>
<input type='hidden' name='iddeg' value="<?php echo  $persona->id_plan_est; ?>">
<button name='editar' class='btn btn-warning text-white'><i class='material-icons' data-toggle='tooltip' title='Edit'>&#xE254;</i></button>
</form>
                   
               </td>
               <td>
<form  onsubmit="return confirm('Realmente desea eliminar el registro?');" method='POST' action='<?php $_SERVER['PHP_SELF'] ?>'>
<input type='hidden' name='iddeg' value="<?php echo  $persona->id_plan_est; ?>">
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

                    <p>Mostrando <?php echo $productosPorPagina ?> de <?php echo $conteo ?> grados disponibles</p>
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
$iddeg = $_POST['iddeg'];
$sql= "SELECT plan_administrativo.id_plan_est, plan_administrativo.plan_estudio, 
plan_administrativo.codigo_estudio, plan_administrativo.estrategia_estudio,
plan_administrativo.tipo_evaluacion, plan_administrativo.descripcion,
plan_administrativo.fecha_estudio, count(*) AS conteo FROM plan_administrativo WHERE id_plan_est = :id_plan_est"; 
$stmt = $connect->prepare($sql);
$stmt->bindParam(':id_plan_est', $iddeg, PDO::PARAM_INT); 
$stmt->execute();
$obj = $stmt->fetchObject();
 
?>

    <div class="col-12 col-md-12"> 

<form role="form" method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>">
    <input value="<?php echo $obj->id_plan_est;?>" name="iddeg" type="hidden">
  <div class="form-row">
    <div class="form-group col-md-6">
      <label for="nombres">plan de estudio</label>
      <input type="text" value="<?php echo $obj->plan_estudio;?>" required name="idper" class="form-control">
      
    </div>
    <div class="form-group col-md-6">
      <label for="edad">Codigo</label>
      <input value="<?php echo $obj->codigo_estudio;?>" name="nomgra" type="text" class="form-control">
    </div>


   <div class="form-group col-md-6">
      <label for="edad">Estrategia de estudio</label>
      <input value="<?php echo $obj->estrategia_estudio;?>" name="estrat" type="text" class="form-control">
    </div>
 

  
        <div class="form-group col-md-6">
        <label for="edad">Tipo de evaluacion</label>
        <input value="<?php echo $obj->tipo_evaluacion;?>" name="tipo_evaluacion" type="text" class="form-control">
        </div>

        <div class="form-group col-md-6">
        <label for="edad">Descripcion</label>
        <input value="<?php echo $obj->descripcion;?>" name="descripcion" type="text" class="form-control">
        </div>

  <div class="form-group col-md-6">
      <label for="edad">Fecha de estudio</label>
      <input value="<?php echo $obj->fecha_estudio;?>" name="fech" type="date" class="form-control">
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
                                    <label for="modal_contact_firstname">Codigo</label>
                                    <div class="input-group">
                                       
                                        <input type="text"  name="txtnomgra" required class="form-control" placeholder="codigo de estudio" />
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="modal_contact_lastname">Plan de estudio</label>
                                    <div class="input-group">
                                      <input type="text" required name="txtidper" class="form-control" placeholder="Plan de estudio" />                                   
                                    
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="modal_contact_lastname">Tipo de evaluacion</label>
                                    <div class="input-group">
                                      <input type="text" required name="txttipo" class="form-control" placeholder="Tipo de evaluacion" />                                   
                                    
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="modal_contact_lastname">Descripcion</label>
                                    <div class="input-group">
                                      <input type="text" required name="txtdesc" class="form-control" placeholder="Descripcion" />                                   
                                    
                                    </div>
                                </div>
                            </div>

                             <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="modal_contact_lastname">Estrategia</label>
                                    <div class="input-group">
                                      <input type="text" required name="txtestr" class="form-control" placeholder="Estrategia de estudio" />                                   
                                    
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="modal_contact_lastname">Fecha</label>
                                    <div class="input-group">
                                      <input type="date" required name="txtfech" class="form-control" placeholder="Fecha de estudio" />                                   
                                    
                                    </div>
                                </div>
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
if(isset($_POST['agregar'])){

    $nomgra = $_POST['txtnomgra'];
    $idper = $_POST['txtidper'];
    $estrategia_estudio = $_POST['txtestr'];
    $tipo_evaluacion = $_POST['txttipo'];
    $descripcion = $_POST['txtdesc'];
    $fecha_estudio = $_POST['txtfech'];
    $id_plan_est = uniqid(); // Por ejemplo, generar un ID único

    $sql = "INSERT INTO plan_administrativo (id_plan_est, codigo_estudio, plan_estudio, estrategia_estudio, tipo_evaluacion, descripcion, fecha_estudio)
            VALUES (:id_plan_est, :codigo_estudio, :plan_estudio, :estrategia_estudio, :tipo_evaluacion, :descripcion, :fecha_estudio)";

    $statement = $connect->prepare($sql);

    $statement->bindValue(':id_plan_est', $id_plan_est);
    $statement->bindValue(':codigo_estudio', $nomgra);
    $statement->bindValue(':plan_estudio', $idper);
    $statement->bindValue(':estrategia_estudio', $estrategia_estudio);
    $statement->bindValue(':tipo_evaluacion', $tipo_evaluacion);
    $statement->bindValue(':descripcion', $descripcion);
    $statement->bindValue(':fecha_estudio', $fecha_estudio);

    $inserted = $statement->execute();

    if($inserted){
        echo '<script type="text/javascript">
            swal("¡Registrado!", "Agregado correctamente", "success").then(function() {
                window.location = "mostrar";
            });
        </script>';
    } else {
        throw new Exception("Error: Fallido! No se pudieron agregar los Planes Administrativos.");
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
$consulta = "DELETE FROM `plan_administrativo` WHERE `id_plan_est`=:id_plan_est";
$sql = $connect-> prepare($consulta);
$sql -> bindParam(':id_plan_est', $iddeg, PDO::PARAM_INT);
$iddeg=trim($_POST['iddeg']);
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
$iddeg=trim($_POST['iddeg']);    
$idper=trim($_POST['idper']);
$nomgra=trim($_POST['nomgra']);
$estrat=trim($_POST['estrat']);
$tipo_evaluacion=trim($_POST['tipo_evaluacion']);
$descripcion=trim($_POST['descripcion']);
$fech=trim($_POST['fech']);


///////// Fin informacion enviada por el formulario /// 

////////////// Actualizar la tabla /////////
$consulta = "UPDATE plan_administrativo
SET `id_plan_est`= :id_plan_est, `codigo_estudio` = :codigo_estudio, `plan_estudio` = :plan_estudio, `estrategia_estudio` = :estrategia_estudio, 
`tipo_evaluacion` = :tipo_evaluacion, `descripcion` = :descripcion,
 `fecha_estudio` = :fecha_estudio";
$sql = $connect->prepare($consulta);
$sql->bindParam(':plan_estudio',$idper,PDO::PARAM_STR, 25);
$sql->bindParam(':codigo_estudio',$nomgra,PDO::PARAM_STR, 25);
$sql->bindParam(':id_plan_est',$iddeg,PDO::PARAM_INT);
$sql->bindParam(':estrategia_estudio',$estrat,PDO::PARAM_STR, 25);
$sql->bindParam(':tipo_evaluacion',$tipo_evaluacion,PDO::PARAM_STR, 25);
$sql->bindParam(':descripcion',$descripcion,PDO::PARAM_STR, 25);
$sql->bindParam(':fecha_estudio',$fech,PDO::PARAM_STR, 25);

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
    echo "<div class='content alert alert-danger'> No se pudo actulizar el plan administrativo  </div>";

print_r($sql->errorInfo()); 
}
}// Cierra envio de guardado
?>
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


