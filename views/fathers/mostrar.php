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
        <title>Planteles | SIGENOR</title>
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

         <li  class="active">
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
                                        <a href="#">📅 3. REGISTRAR PERÍODOS</a>
                                    </li>
                                    <li>
                                        <a href="#">✅ Ejemplo: Crear el período UNO, 2024-2025</a>
                                    </li>
                                    <li>
                                        <a href="#">🔹 Paso 1: Abre el módulo "Períodos Académicos".</a>
                                    </li>
                                    <li>
                                        <a href="#">🔹 Paso 2: Clic en "Nuevo Período".</a>
                                    </li>
                                     <li>
                                        <a href="#">🔹 Paso 3: Ingresa nombre del período y fechas de inicio/fin.</a>
                                    </li>
                                  
                                     <li>
                                        <a href="#">🔹 Paso 4: Asocia el período a un plantel y haz clic en Guardar ✅.</a>
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
          <h2 class="ml-lg-2">Planteles</h2>
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

try {
    // Consulta para contar el total de planteles
    $sentencia = $connect->query("SELECT count(*) AS conteo FROM planteles");
    $conteo = $sentencia->fetchObject()->conteo;
    $paginas = ceil($conteo / $productosPorPagina);

    // Consulta para obtener los datos paginados
    $sentencia = $connect->prepare("SELECT * FROM planteles LIMIT ? OFFSET ?");
    $sentencia->bindParam(1, $limit, PDO::PARAM_INT);
    $sentencia->bindParam(2, $offset, PDO::PARAM_INT);
    $sentencia->execute();
    $productos = $sentencia->fetchAll(PDO::FETCH_OBJ);
} catch (Exception $e) {
    echo "<div class='content alert alert-danger'> Error al recuperar los planteles: " . $e->getMessage() . "</div>";
}
       ?>
    <table class="table table-striped table-hover">
      <thead>
      <tr>
                <th>Nº</th>
                <th>Código</th>
                <th>Nombre</th>
                <th>Dirección</th>
                <th>Teléfono</th>
                <th>Municipio</th>
                <th>Entidad Federal</th>
                <th>localidad</th>
                <th>Zona Educativa</th>
                <th>Director</th>
                <th>Cédula</th>
                <th>Fecha</th>
                <th>Editar</th>
                <th>Eliminar</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($productos as $producto) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($producto->numero_plantel); ?></td>
                    <td><?php echo htmlspecialchars($producto->codigo); ?></td>
                    <td><?php echo htmlspecialchars($producto->nombre); ?></td>
                    <td><?php echo htmlspecialchars($producto->direccion_plantel); ?></td>
                    <td><?php echo htmlspecialchars($producto->telefono); ?></td>
                    <td><?php echo htmlspecialchars($producto->municipio); ?></td>
                    <td><?php echo htmlspecialchars($producto->entidad_federal); ?></td>
                    <td><?php echo htmlspecialchars($producto->localidad); ?></td>
                    <td><?php echo htmlspecialchars($producto->zona_educativa); ?></td>
                    <td><?php echo htmlspecialchars($producto->director); ?></td>
                    <td><?php echo htmlspecialchars($producto->cedula_director); ?></td>
                    <td><?php echo htmlspecialchars($producto->fecha); ?></td>
                 
                  
                  
               <td>
<form method='POST' action='<?php $_SERVER['PHP_SELF'] ?>'>
<input type='hidden' name='id_plantel' value="<?php echo  $producto->id_plantel; ?>">
<button name='editar' class='btn btn-warning text-white'><i class='material-icons' data-toggle='tooltip' title='Edit'>&#xE254;</i></button>
</form>
                   
               </td>
               <td>
<form  onsubmit="return confirm('Realmente desea eliminar el registro?');" method='POST' action='<?php $_SERVER['PHP_SELF'] ?>'>
<input type='hidden' name='id_plantel' value="<?php echo  $producto->id_plantel; ?>">
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

                    <p>Mostrando <?php echo $productosPorPagina ?> de <?php echo $conteo ?> Planteles Recientes</p>
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
$idfa = $_POST['id_plantel'];
$sql= "SELECT planteles.id_plantel, planteles.numero_plantel, planteles.codigo, planteles.nombre, planteles.direccion_plantel, planteles.telefono, planteles.municipio, planteles.entidad_federal, planteles.zona_educativa, planteles.localidad, planteles.director, planteles.cedula_director, planteles.fecha FROM planteles INNER JOIN estudiantes_planteles ON planteles.id_plantel = estudiantes_planteles.id_plantel WHERE planteles.id_plantel = :id_plantel"; 
$sql = $connect->prepare($sql);
$sql->bindParam(':id_plantel', $idfa, PDO::PARAM_INT); 
$sql->execute();
$obj = $sql->fetchObject();
 

$id_plantel = isset($obj->id_plantel) ? $obj->id_plantel : '';
$nombre_plantel = isset($obj->nombre_plantel) ? $obj->nombre_plantel : '';
$nombre = isset($obj->nombre) ? $obj->nombre : '';
$numero_plantel = isset($obj->numero_plantel) ? $obj->numero_plantel : '';
$codigo = isset($obj->codigo) ? $obj->codigo : '';
$direccion_plantel = isset($obj->direccion_plantel) ? $obj->direccion_plantel : '';
$telefono = isset($obj->telefono ) ? $obj->telefono  : '';
$municipio= isset($obj->municipio) ? $obj->municipio : ''; // Asegurarse de que la variable $edad esté definida
$entidad_federal = isset($obj->entidad_federal) ? $obj->entidad_federal : '';
$localidad = isset($obj->localidad) ? $obj->localidad : '';
$zona_educativa = isset($obj->zona_educativa) ? $obj->zona_educativa : '';
$director = isset($obj->director) ? $obj->director : '';
$cedula_director = isset($obj->cedula_director) ? $obj->cedula_director : '';
$fecha = isset($obj->fecha) ? $obj->fecha : '';

?>


    <div class="col-12 col-md-12"> 

<form role="form" method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>">
    <input value="<?php echo $id_plantel;?>" name="idfa" type="hidden">
   
      <div class="form-row">
    <div class="form-group col-md-6">
      <label for="edad">Numero plantel</label>
      <input value="<?php echo $numero_plantel;?>" name="numb" type="numb" maxlength="1" placeholder="Numero de plantel" class="form-control">
    </div>
    <div class="form-group col-md-6">
      <label for="nombres">Codigo</label>
      <input value="<?php echo $codigo;?>" maxlength="8"  onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" name="dnifa" type="text" class="form-control"  placeholder="Codigo">
    </div>
    </div>

      <div class="form-row">
    <div class="form-group col-md-6">
      <label for="edad">Nombre</label>
      <input value="<?php echo $nombre;?>" name="nomfa" type="text" placeholder="Nombre del plantel" class="form-control">
    </div>

    <div class="form-group col-md-6">
      <label for="nombres">Direccion</label>
      <input value="<?php echo $direccion_plantel;?>" name="profefa" type="text" class="form-control" placeholder="Direccion">
    </div>
  </div>

        <div class="form-row">
     <div class="form-group col-md-6">
      <label for="nombres">Telefono</label>
      <input value="<?php echo $telefono;?>" name="telefa" type="text" class="form-control" placeholder="Telefono">
    </div>

    <div class="form-group col-md-6">
      <label for="nombres">Municipio</label>
      <input value="<?php echo $municipio;?>" name="correo" maxlength="9"  onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" type="text" class="form-control" placeholder="Municipio">
    </div>
  </div>

        <div class="form-row">
     <div class="form-group col-md-6">
      <label for="nombres">Entidad Federal</label>
      <input value="<?php echo $entidad_federal;?>" name="direc" type="text" class="form-control" placeholder="Entidad Federal">
  </div>
        <div class="form-group col-md-6">
        <label for="nombres">Localidad</label>
        <input value="<?php echo $localidad;?>" name="localidad" type="text" class="form-control" placeholder="Localidad">
        </div>   
         </div>  

 <div class="form-row">
     <div class="form-group col-md-6">
      <label for="nombres">Zona Educativa</label>
      <input value="<?php echo $zona_educativa;?>" name="zonae" type="text" class="form-control" placeholder="Zona Educativa">
  </div>
     <div class="form-group col-md-6">
      <label for="nombres">Director</label>
      <input value="<?php echo $director;?>" name="director" type="text" class="form-control" placeholder="Director">
    </div>
  </div>

    <div class="form-row">
     <div class="form-group col-md-6">
      <label for="nombres">Cedula del Director</label>
      <input value="<?php echo $cedula_director;?>" name="cedudir" type="text" class="form-control" placeholder="Cedula Director">
    </div>
  
     <div class="form-group col-md-6">
      <label for="nombres">Fecha</label>
      <input value="<?php echo $fecha;?>" name="fecha" type="text" class="form-control" placeholder="Fecha">
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
        <div class="modal-dialog " role="document">
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
                        <div class="form-group">
                                    <label for="modal_contact_firstname">Nº</label>
                                    <div class="input-group">                                       
                                        <input type="number"  name="numb" maxlength="1" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" required class="form-control" placeholder="Numero de Plantel" />
                                    </div>
                                </div>
                        <div class="form-row">                                                           
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="modal_contact_firstname">Codigo</label>
                                    <div class="input-group">                                       
                                        <input type="text"  name="txtdni" maxlength="10" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" required class="form-control" placeholder="Codigo Plantel" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="modal_contact_lastname">Nombre del Plantel</label>
                                    <div class="input-group">       
                                        <input type="text"  name="txtnom" placeholder="Nombre del Plantel" required class="form-control"/>
                                    </div>
                                </div>
                            </div>
                            
                        </div>

                                    

                        <div class="form-row">
                        <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="modal_contact_firstname">Dirección</label>
                                    <div class="input-group">
                                        <input type="text" name="txtdir" placeholder="Dirección" required class="form-control"/>
                                    </div>
                                </div>
                            </div>
                       
                          
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="modal_contact_firstname">Teléfono</label>
                                    <div class="input-group">
                                        <input type="text" name="txttel" maxlength="12" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" placeholder="Teléfono" required class="form-control"/>
                                    </div>
                                </div>
                            </div>
                         
                         <div class="form-row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="modal_contact_firstname">Municipio</label>
                                    <div class="input-group">
                                        <input type="text" name="txtmun"  placeholder="Municipio" required class="form-control"/>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="modal_contact_firstname">Entidad Federal</label>
                                    <div class="input-group">
                                        <input type="text" name="txtent" placeholder="Entidad Federal" required class="form-control"/>
                                    </div>
                                </div>
                            </div>

                             <div class="form-group">
                                    <label for="modal_contact_firstname">Localidad</label>
                                    <div class="input-group">
                                        <input type="text" name="txtloc" placeholder="Localidad" required class="form-control"/>
                                    </div>
                                </div>

                        </div>

                        <div class="form-row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="modal_contact_firstname">Zona Educativa</label>
                                    <div class="input-group">
                                    <input type="text" name="txtzon" placeholder="Zona Educativa" required class="form-control"/>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="modal_contact_firstname">Director</label>
                                    <div class="input-group">
                                        <input type="text" name="txtdirc" placeholder="Director" required class="form-control"/>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="modal_contact_firstname">Cedula Director</label>
                                    <div class="input-group">
                                        <input type="text" name="txtcd" placeholder="Cedula del Director" required class="form-control"/>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="modal_contact_lastname">Fecha de Emision</label>
                                    <div class="input-group">
                                    <input type="date" name="txtfec" placeholder="Fecha " required class="form-control"/> 
                                        
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
if (isset($_POST['agregar'])) {
    try {
        $connect->beginTransaction(); // Inicia la transacción

        // Información enviada por el formulario
        $numero_plantel = isset($_POST['numb']) ? trim($_POST['numb']) : null;
        $codigo = isset($_POST['txtdni']) ? trim($_POST['txtdni']) : null;
        $nombre = isset($_POST['txtnom']) ? trim($_POST['txtnom']) : null;
        $direccion = isset($_POST['txtdir']) ? trim($_POST['txtdir']) : null;
        $telefono = isset($_POST['txttel']) ? trim($_POST['txttel']) : null;
        $municipio = isset($_POST['txtmun']) ? trim($_POST['txtmun']) : null;
        $entidad_federal = isset($_POST['txtent']) ? trim($_POST['txtent']) : null;
        $localidad = isset($_POST['txtloc']) ? trim($_POST['txtloc']) : null; // Localidad
        $zona_educativa = isset($_POST['txtzon']) ? trim($_POST['txtzon']) : null;
        $director = isset($_POST['txtdirc']) ? trim($_POST['txtdirc']) : null;
        $cedula_director = isset($_POST['txtcd']) ? trim($_POST['txtcd']) : null;
        $fecha = isset($_POST['txtfec']) ? trim($_POST['txtfec']) : null;
        $estudiantes_cedulas = isset($_POST['cedulas_estudiantes']) ? $_POST['cedulas_estudiantes'] : []; // Cedulas de estudiantes

        // Validaciones básicas
        if (empty($codigo) || empty($numero_plantel) || empty($nombre) || empty($direccion) || empty($telefono)) {
            throw new Exception("Error: Los campos requeridos del plantel están incompletos.");
        }

        // Inserción en la tabla `planteles`
        $stmt1 = $connect->prepare("INSERT INTO planteles (numero_plantel, codigo, nombre, direccion_plantel, telefono, municipio, entidad_federal, localidad, zona_educativa, director, cedula_director, fecha) 
                                    VALUES (:numero_plantel, :codigo, :nombre, :direccion, :telefono, :municipio, :entidad_federal, :localidad, :zona_educativa, :director, :cedula_director, :fecha)");
        
        $stmt1->bindParam(':numero_plantel', $numero_plantel, PDO::PARAM_INT);
        $stmt1->bindParam(':codigo', $codigo);
        $stmt1->bindParam(':nombre', $nombre);
        $stmt1->bindParam(':direccion', $direccion);
        $stmt1->bindParam(':telefono', $telefono);
        $stmt1->bindParam(':municipio', $municipio);
        $stmt1->bindParam(':entidad_federal', $entidad_federal);
        $stmt1->bindParam(':localidad', $localidad);
        $stmt1->bindParam(':zona_educativa', $zona_educativa);
        $stmt1->bindParam(':director', $director);
        $stmt1->bindParam(':cedula_director', $cedula_director);
        $stmt1->bindParam(':fecha', $fecha);

        if ($stmt1->execute()) {
            $id_plantel = $connect->lastInsertId(); // Obtener el ID del plantel recién creado

            // Inserción en la tabla `estudiantes_planteles`
            $stmt2 = $connect->prepare("INSERT INTO estudiantes_planteles (id_estudiante, id_plantel) VALUES (:id_estudiante, :id_plantel)");

            foreach ($estudiantes_cedulas as $cedula) {
                // Buscar el ID del estudiante usando la cédula
                $stmt3 = $connect->prepare("SELECT id_estudiante FROM estudiantes WHERE cedula = :cedula");
                $stmt3->bindParam(':cedula', $cedula, PDO::PARAM_STR);
                $stmt3->execute();

                if ($stmt3->rowCount() > 0) {
                    $id_estudiante = $stmt3->fetchColumn();
                    // Insertar la relación en `estudiantes_planteles`
                    $stmt2->bindParam(':id_estudiante', $id_estudiante, PDO::PARAM_INT);
                    $stmt2->bindParam(':id_plantel', $id_plantel, PDO::PARAM_INT);
                    $stmt2->execute();
                } else {
                    throw new Exception("Error: No se encontró un estudiante con la cédula $cedula.");
                }
            }

            $connect->commit(); // Confirma la transacción

            echo '<script type="text/javascript">
            swal("¡Registro exitoso!", "El plantel y las asociaciones fueron creadas correctamente", "success").then(function() {
                window.location = "mostrar";
            });
            </script>';
        } else {
            throw new Exception("Error: Falló la inserción del plantel.");
        }
    } catch (Exception $e) {
        $connect->rollBack(); // Deshacer los cambios en caso de error
        echo "<div class='content alert alert-danger'> Error: " . $e->getMessage() . "</div>";
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
if (isset($_POST['eliminar'])) {
    try {
        // Consulta SQL para eliminar el plantel
        $consulta = "DELETE FROM `planteles` WHERE `id_plantel` = :id_plantel";
        $sql = $connect->prepare($consulta);
        
        // Obtener y limpiar el ID proporcionado por el formulario
        $idfa = trim($_POST['id_plantel']);
        $sql->bindParam(':id_plantel', $idfa, PDO::PARAM_INT);
        $sql->execute();

        // Verificar si se afectó alguna fila
        if ($sql->rowCount() > 0) {
            echo '<script type="text/javascript">
            swal("¡Eliminado!", "El plantel ha sido eliminado correctamente.", "success").then(function() {
                window.location = "mostrar";
            });
            </script>';
        } else {
            echo '<script type="text/javascript">
            swal("¡Error!", "No se pudo eliminar el plantel. Intente nuevamente.", "error");
            </script>';
        }
    } catch (Exception $e) {
        // Capturar y manejar errores de integridad referencial
        if ($e->getCode() === '23000') {
            echo '<script type="text/javascript">
            swal("¡Error!", "No se puede eliminar el plantel porque está asociado a los estudiantes. Primero elimine el estudiante o cambie sus planteles asignados.", "error");
            </script>';
        } else {
            // Capturar cualquier otro error inesperado
            echo '<script type="text/javascript">
            swal("¡Error!", "Ocurrió un error inesperado: ' . $e->getMessage() . '", "error");
            </script>';
        }
    }
}
?>

  


  <?php
    

///////////// Informacion enviada por el formulario /////////////




require '../../Config/config.php';

if (isset($_POST['actualizar'])) {
    try {
        
        $numero_plantel = trim($_POST['numb']);
        $idfa = trim($_POST['idfa']);
        $dnifa = trim($_POST['dnifa']);
        $nomfa = trim($_POST['nomfa']);
        $profefa = trim($_POST['profefa']);
        $telefa = trim($_POST['telefa']);
        $correo = trim($_POST['correo']);
        $direc = trim($_POST['direc']);
        $localidad = trim($_POST['localidad']);
        $zonae = trim($_POST['zonae']);
        $director = trim($_POST['director']);
        $cedudir = trim($_POST['cedudir']);
        $fecha = trim($_POST['fecha']);

        $sql = "UPDATE planteles SET `numero_plantel` = :numero_plantel, `codigo` = :codigo, `nombre` = :nombre, `direccion_plantel` = :direccion_plantel, `telefono` = :telefono, `municipio` = :municipio, `entidad_federal` = :entidad_federal, `localidad` = :localidad, `zona_educativa` = :zona_educativa, `director` = :director, `cedula_director` = :cedula_director, `fecha` = :fecha WHERE `id_plantel` = :id_plantel";
        $stmt = $connect->prepare($sql);

        $stmt->bindParam(':id_plantel', $idfa, PDO::PARAM_INT);
        $stmt->bindParam(':numero_plantel', $numero_plantel, PDO::PARAM_INT);
        $stmt->bindParam(':codigo', $dnifa);
        $stmt->bindParam(':nombre', $nomfa);
        $stmt->bindParam(':direccion_plantel', $profefa);
        $stmt->bindParam(':telefono', $correo);
        $stmt->bindParam(':municipio', $telefa);
        $stmt->bindParam(':entidad_federal', $direc);
        $stmt->bindParam(':localidad', $localidad);
        $stmt->bindParam(':zona_educativa', $zonae);
        $stmt->bindParam(':director', $director);
        $stmt->bindParam(':cedula_director', $cedudir);
        $stmt->bindParam(':fecha', $fecha);

        $stmt->execute();

        echo '<script type="text/javascript">
        swal("¡Actualizado!", "Actualizado correctamente", "success").then(function() {
            window.location = "mostrar";
        });
        </script>';
    } catch (Exception $e) {
        echo "<div class='content alert alert-danger'> Error al actualizar el plantel: " . $e->getMessage() . "</div>";
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


