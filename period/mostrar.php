
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
        <title>Periodos | SIGENOR</title>
	    <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="http://localhost/sistema_escolar/Assets/css/bootstrap-1.min.css">
	    <!----css3---->
        <link rel="stylesheet" href="http://localhost/sistema_escolar/Assets/css/custom.css">
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
		
        <li class="active">
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
                                        <a href="#">🧷 4. REGISTRAR SECCIONES</a>
                                    </li>
                                    <li>
                                        <a href="#">✅ Ejemplo: Sección A, PERIODO UNO</a>
                                    </li>
                                    <li>
                                        <a href="#">🔹 Paso 1: Abre el módulo "Secciones".</a>
                                    </li>
                                    <li>
                                        <a href="#">🔹 Paso 2: Clic en "Agregar Sección".</a>
                                    </li>
                                      <li>
                                        <a href="#">🔹 Paso 3: Llena el nombre de la sección y sus respectivos campos</a>
                                    </li>
                                      <li>
                                        <a href="#">🔹 Paso 4: Asocia al periodo y haz clic en Guardar ✅.</a>
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
          <h2 class="ml-lg-2">Periodos</h2>
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

        // Conexión a la base de datos y consulta para obtener el conteo total de estudiantes
$sentencia = $connect->query("SELECT count(*) AS conteo FROM periodos INNER JOIN planteles ON periodos.id_plantel = planteles.id_plantel;");
$conteo = $sentencia->fetchObject()->conteo;
$paginas = ceil($conteo / $productosPorPagina);

    $sentencia = $connect->prepare("SELECT periodos.id_periodo, periodos.id_plantel, periodos.numero_periodo, periodos.fecha_inicio, periodos.fecha_fin, periodos.nombre_periodo, periodos.fecha, periodos.estado, planteles.nombre  FROM periodos INNER JOIN planteles ON periodos.id_plantel = planteles.id_plantel LIMIT :limit OFFSET :offset");
    $sentencia->bindValue(':limit', $limit, PDO::PARAM_INT);
    $sentencia->bindValue(':offset', $offset, PDO::PARAM_INT);
    $sentencia->execute();
    $productos = $sentencia->fetchAll(PDO::FETCH_OBJ);
       ?>
    <table class="table table-striped table-hover">
      <thead>
        <tr>
        
          <th>Plantel</th>
          <th>Numero de periodo</th>
          <th>Inicio</th>
          <th>Fin</th>
          <th>Nombre del periodo</th>
          <th>Fecha de registro</th>
          <th>Estado</th>
          <th>Editar</th>
          <th>Eliminar</th>
        </tr>
      </thead>

      <tbody>
          <?php foreach($productos as $producto){ ?>
            <tr>
              

               <td><?php echo $producto->nombre?></td>
               <td><?php echo $producto->numero_periodo ?></td>
               <td><?php echo $producto->fecha_inicio ?></td>
               <td><?php echo $producto->fecha_fin ?></td>
               <td><?php echo $producto->nombre_periodo ?></td>
               <td><?php echo $producto->fecha?></td>
               <td>

                        <?php if($producto->estado==1)  { ?> 
        <span class="badge badge-success">Activo</span>
    <?php  }   else { ?> 
        <span class="badge badge-danger">No activo</span>
        <?php  } ?>  
                            
                    </td>
               <td>
<form method='POST' action='<?php $_SERVER['PHP_SELF'] ?>'>
<input type='hidden' name='id_periodo' value="<?php echo  $producto->id_periodo; ?>">
<button name='editar' class='btn btn-warning text-white'><i class='material-icons' data-toggle='tooltip' title='Edit'>&#xE254;</i></button>
</form>
                   
               </td>
               <td>
<form  onsubmit="return confirm('Realmente desea eliminar el registro?');" method='POST' action='<?php $_SERVER['PHP_SELF'] ?>'>
<input type='hidden' name='id_periodo' value="<?php echo  $producto->id_periodo; ?>">
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

                    <p>Mostrando <?php echo $productosPorPagina ?> de <?php echo $conteo ?> periodos disponibles</p>
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
    $idper = $_POST['id_periodo'];
  // Consulta para obtener los datos del estudiante
  $sql = "SELECT periodos.id_periodo, periodos.id_plantel, periodos.numero_periodo, periodos.fecha_inicio, periodos.fecha_fin, periodos.nombre_periodo, periodos.fecha, periodos.estado  FROM periodos
  INNER JOIN planteles ON periodos.id_plantel = planteles.id_plantel 

  WHERE id_periodo = :id_periodo";


$stmt = $connect->prepare($sql);
$stmt->bindParam(':id_periodo', $idper, PDO::PARAM_INT); 
$stmt->execute();
$obj = $stmt->fetchObject();

// Asegurarse de que las variables estén definidas
$id_periodo = isset($obj->id_periodo) ? $obj->id_periodo : '';
$id_plantel = isset($obj->id_plantel) ? $obj->id_plantel : '';
$numero_periodo = isset($obj->numero_periodo) ? $obj->numero_periodo : '';
$fecha_inicio = isset($obj->fecha_inicio) ? $obj->fecha_inicio : '';
$fecha_fin = isset($obj->fecha_fin) ? $obj->fecha_fin : '';
$nombre_periodo = isset($obj->nombre_periodo) ? $obj->nombre_periodo : '';
$fecha = isset($obj->fecha) ? $obj->fecha : '';
$estado = isset($obj->estado) ? $obj->estado : '';



 
?>

    <div class="col-12 col-md-12"> 

<form role="form" method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>">
    <input value="<?php echo $id_periodo;?>" name="idper" type="hidden">
  <div class="form-row">
    <div class="form-group col-md-6">
      <label for="nombres">Plantel</label>
      <select id="incrementSelect" class="form-control" name="id_plantel">
    <?php 
    $stmt = $connect->prepare('SELECT planteles.id_plantel, planteles.nombre FROM planteles');
    $stmt->execute();

    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo '<option value="' . $row["id_plantel"] . '">' . $row["nombre"] . '</option>';
    }
    ?>

    </select>
  </div>

      
    <div class="form-group col-md-6">
      <label for="edad">Numero de Periodo</label>
      <input value="<?php echo $numero_periodo;?>" name="numper" type="text" class="form-control" placeholder="Numero del Periodo">
    </div>
  </div>


  <div class="form-row">
    <div class="form-group col-md-6">
      <label for="nombres">Inicio</label>
      <input value="<?php echo $fecha_inicio;?>" name="fechaini" type="year" class="form-control">
    </div>

    <div class="form-group col-md-6">
      <label for="nombres">Fin</label>
      <input value="<?php echo $fecha_fin;?>" name="fechafin" type="year" class="form-control">
    </div>
    
  </div>
  
  <div class="form-row">
    <div class="form-group col-md-6">
      <label for="nombres">Nombre del Periodo</label>
      <input value="<?php echo $nombre_periodo;?>" name="nompr" type="text" class="form-control" placeholder="Nombre del Periodo">
    </div>

    <div class="form-group col-md-6">
      <label for="nombres">Fecha de Registro</label>
      <input value="<?php echo $fecha;?>" name="fecha" type="date" class="form-control">
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
                                    <label for="modal_contact_firstname">Plantel</label>
                                    <div class="input-group">
                                       
                                    <select id="incrementSelect" class="form-control" name="id_plantel" onchange="incrementValue()">
                                            <?php
                                            $stmt = $connect->prepare('SELECT planteles.id_plantel, planteles.nombre FROM planteles');
                                            $stmt->execute();

                                            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                echo '<option value="' . $row["id_plantel"] . '">' . $row["nombre"] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                            <div class="form-group">
                            <label for="modal_contact_firstname">Numero de Periodo</label>
                                    <div class="input-group">
                                       
                                        <input type="text"  name="txtperi" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" required class="form-control" placeholder=" Numero del Periodo" />
                                    </div> 
                                    </div>
                                    </div>            
                        </div>
                        <div class="form-row">
                        <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="modal_contact_lastname">Inicio</label>
                                    <div class="input-group">
                                         
                                        <input type="year"  name="txtini" required class="form-control"/>
                                    </div>
                                </div>
                            </div> 

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="modal_contact_lastname">Fin</label>
                                    <div class="input-group">
                                         
                                        <input type="year"  name="txttermi" required class="form-control"/>
                                    </div>
                                </div>
                            </div> 
                            </div> 

                        <div class="form-row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="modal_contact_firstname">Nombre del Periodo</label>
                                    <div class="input-group">
                                       
                                        <input type="text"  name="txtnom" required class="form-control" placeholder="Nombre del Periodo" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="modal_contact_lastname">Fecha de Registro</label>
                                    <div class="input-group">
                                    <input type="date"  name="txtfech" required class="form-control"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
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
$id_plantel=$_POST['id_plantel'];    
$numperi=$_POST['txtperi'];
$starperi=$_POST['txtini'];
$endperi=$_POST['txttermi'];
$nomperi=$_POST['txtnom'];
$fecha=$_POST['txtfech'];
$state=$_POST['txtesta'];

$sql = "INSERT INTO periodos (id_plantel, numero_periodo, fecha_inicio, fecha_fin, nombre_periodo, fecha, estado) VALUES (:id_plantel, :numero_periodo,:fecha_inicio,:fecha_fin,:nombre_periodo, :fecha, :estado)";


//Prepare our statement.
$statement = $connect->prepare($sql);


//Bind our values to our parameters (we called them :make and :model).
$statement->bindValue(':id_plantel', $id_plantel);
$statement->bindValue(':numero_periodo', $numperi);
$statement->bindValue(':fecha_inicio', $starperi);
$statement->bindValue(':fecha_fin', $endperi);
$statement->bindValue(':nombre_periodo', $nomperi);
$statement->bindValue(':fecha', $fecha);
$statement->bindValue(':estado',$state);


//Execute the statement and insert our values.
$inserted = $statement->execute();


//Because PDOStatement::execute returns a TRUE or FALSE value,
//we can easily check to see if our insert was successful.
if($inserted){
    echo '<script type="text/javascript">
swal("¡Registrado!", "Agregado correctamente", "success").then(function() {
            window.location = "mostrar";
        });
        </script>';
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
        $consultaVerificar = "SELECT COUNT(*) FROM periodos WHERE id_periodo = :id_periodo";
        $stmtVerificar = $connect->prepare($consultaVerificar);
        $idstu = trim($_POST['id_periodo']);
        $stmtVerificar->bindParam(':id_periodo', $idstu, PDO::PARAM_INT);
        $stmtVerificar->execute();
        $dependencias = $stmtVerificar->fetchColumn();

        if ($dependencias > 0) {
            echo '<script type="text/javascript">
            swal("Error", "No se puede eliminar este periodo porque esta relacionado. Por favor, elimina la relacion primero.", "error");
            </script>';
        }  else {
    
$consulta = "DELETE FROM `periodos` WHERE `id_periodo`=:id_periodo";
$sql = $connect-> prepare($consulta);
$sql -> bindParam(':id_periodo', $idper, PDO::PARAM_INT);
$idper=trim($_POST['id_periodo']);
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
    
if(isset($_POST['actualizar'])){
///////////// Informacion enviada por el formulario /////////////
$idper=trim($_POST['idper']);
$id_plantel=trim($_POST['id_plantel']);
$numperi=trim($_POST['numper']);
$starperi=trim($_POST['fechaini']);
$endperi=trim($_POST['fechafin']);
$nomperi=trim($_POST['nompr']);
$fecha=trim($_POST['fecha']);

///////// Fin informacion enviada por el formulario /// 

////////////// Actualizar la tabla /////////
$consulta = "UPDATE periodos
SET `id_plantel`= :id_plantel, `numero_periodo` = :numero_periodo, `fecha_inicio` = :fecha_inicio, `fecha_fin` = :fecha_fin, `nombre_periodo` = :nombre_periodo, `fecha` = :fecha  WHERE `id_periodo` = :id_periodo";
$sql = $connect->prepare($consulta);
$sql->bindParam(':id_plantel',$id_plantel,PDO::PARAM_STR, 25);
$sql->bindParam(':numero_periodo',$numperi,PDO::PARAM_STR, 25);
$sql->bindParam(':fecha_inicio',$starperi,PDO::PARAM_STR,25);
$sql->bindParam(':fecha_fin',$endperi,PDO::PARAM_STR,25);
$sql->bindParam(':nombre_periodo',$nomperi,PDO::PARAM_INT);
$sql->bindParam(':fecha',$fecha,PDO::PARAM_INT);
$sql->bindParam(':id_periodo',$idper,PDO::PARAM_INT);

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


