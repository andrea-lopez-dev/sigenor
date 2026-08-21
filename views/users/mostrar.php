<?php  

  session_start(); 
  if(!isset($_SESSION['rol'], $_SESSION['foto'] ) || $_SESSION['rol'] != 1){
    $foto = $_SESSION['foto'];
    header('location: ../home.php');
     }

?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
        <title>Usuarios | Sistema Escolar</title>
	    <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="http://localhost/sistema_escolar/Assets/css/bootstrap-1.min.css">
	    <!----css3---->
        <link rel="stylesheet" href="http:///sistema_escolar/Assets/css/custom.css">
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
				
				 <li  class="active">
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
                                        <a href="#">🏫 2. REGISTRAR PLANTELES</a>
                                    </li>
                                    <li>
                                        <a href="#">✅ Ejemplo: Agregar el plantel U.E. Simón Bolívar</a>
                                    </li>
                                    <li>
                                        <a href="#">🔹 Paso 1: Ve al módulo "Planteles".</a>
                                    </li>
                                    <li>
                                        <a href="#">🔹 Paso 2: Haz clic en "Nuevo Plantel".</a>
                                    </li>
                                     <li>
                                        <a href="#">🔹 Paso 3: Ingresa nombre, dirección, teléfono, etc.</a>
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
          <h2 class="ml-lg-2">Usuarios</h2>
        </div>

        <div class="col-sm-12 p-0 d-flex justify-content-lg-end justify-content-center">
          <a href="#addEmployeeModal" class="btn btn-success" data-toggle="modal">
          <i class="material-icons">&#xE147;</i> </a>

      
         
        </div>
      </div>


    </div>
    <table class="table table-striped table-hover" id="myTable">
      <thead>
        <tr>
        
          <th>Nombre</th>
          <th>Usuario</th>
          <th>Correo</th>
          <th>Clave</th>
          <th>Permiso</th>
          <th>Estado</th>
          <th>Editar</th>
          <th>Eliminar</th>
        </tr>
      </thead>
      <?php 
 require '../../Config/config.php';
       ?>
<?php
$sql = "SELECT usuarios.id_usuario, usuarios.nombre_usuario, usuarios.nombre_completo, usuarios.correo, usuarios.clave, usuarios.rol, usuarios.estado FROM usuarios"; 
$stmt = $connect -> prepare($sql); 
$stmt -> execute(); 
$results = $stmt -> fetchAll(PDO::FETCH_OBJ); 

if($stmt -> rowCount() > 0)   { 
foreach($results as $result) { 
echo "
<tbody>
<tr>
<td>".$result -> nombre_completo."</td>
<td>".$result -> nombre_usuario."</td>
<td>".$result -> correo."</td>
<td>".$result -> clave."</td>
<td>".$result -> rol."</td>
<td>".$result -> estado."</td>

<td>
<form method='POST' action='".$_SERVER['PHP_SELF']."'>
<input type='hidden' name='id' value='".$result -> id_usuario."'>
<button name='editar' class='btn btn-warning text-white'><i class='material-icons' data-toggle='tooltip' title='Edit'>&#xE254;</i></button>
</form>

</td>
<td>
<form  onsubmit=\"return confirm('Realmente desea eliminar el registro?');\" method='POST' action='".$_SERVER['PHP_SELF']."'>
<input type='hidden' name='id' value='".$result -> id_usuario."'>
<button name='eliminar' class='btn btn-danger text-white' ><i class='material-icons'  title='Delete'>&#xE872;</i></button>
</form>
</td>
</tr>
</tbody>";

   }
 }
?>
    </table>
  </div>
</div>

<?php 

if (isset($_POST['editar'])){
$id_usuario = $_POST['id'];
$sql= "SELECT * FROM usuarios WHERE id_usuario = :id_usuario"; 
$stmt = $connect->prepare($sql);
$stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT); 
$stmt->execute();
$obj = $stmt->fetchObject();
 
?>

    <div class="col-12 col-md-12"> 

<form role="form" method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>">
    <input value="<?php echo $obj->id;?>" name="id" type="hidden">
  <div class="form-row">
    <div class="form-group col-md-6">
      <label for="nombres">Nombre</label>
      <input value="<?php echo $obj->nombre_completo;?>" name="nombre" type="text" class="form-control" placeholder="Nombres">
    </div>
    <div class="form-group col-md-6">
      <label for="edad">Usuario</label>
      <input value="<?php echo $obj->nombre_usuario;?>" name="usuario" type="text" class="form-control" placeholder="Usuario">
    </div>
  </div>


  <div class="form-row">
    <div class="form-group col-md-6">
      <label for="nombres">Correo</label>
      <input value="<?php echo $obj->correo;?>" name="correo" type="text" class="form-control" placeholder="Correo">
    </div>
  </div>
  <div class="form-row">
    <div class="form-group col-md-6">
      <label for="nombres">Clave</label>
      <input value="<?php echo $obj->clave;?>" name="clave" type="password" class="form-control" placeholder="Clave">
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
                                    <label for="modal_contact_firstname">Nombre</label>
                                    <div class="input-group">
                                       
                                        <input type="text"  name="txtnomu" required class="form-control" placeholder="Nombre" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="modal_contact_lastname">Usuario</label>
                                    <div class="input-group">
                                         
                                        <input type="text"  name="txtusua" placeholder="Usuario" required class="form-control"/>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="form-row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="modal_contact_firstname">Contraseña</label>
                                    <div class="input-group">
                                       
                                        <input type="password"  name="txtcont" required class="form-control" placeholder="Contraseña" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="modal_contact_lastname">Permisos</label>
                                    <div class="input-group">
                                        <select class="form-control" required name="txtperm">
                                          <option selected>SELECCIONE</option>
                                          <option value="1">Administrador</option>
                                         
                                        </select>
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
                                         
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="modal_contact_firstname">Correo</label>
                                    <div class="input-group">
                                       
                                        <input type="email"  name="txtcorr" required class="form-control" placeholder="Correo" />
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
$usuario=$_POST['txtusua'];
$nombre=$_POST['txtnomu'];
$correo=$_POST['txtcorr'];
$clave=$_POST['txtcont'];
$rol=$_POST['txtperm'];
$estado=$_POST['txtesta'];
$sql = "INSERT INTO usuarios (nombre_usuario, nombre_completo, correo, clave, rol, estado) VALUES (:nombre_usuario, :nombre_completo,:correo,:clave,:rol,:estado)";
//Prepare our statement.
$statement = $connect->prepare($sql);

//Bind our values to our parameters (we called them :make and :model).
$statement->bindValue(':nombre_usuario', $usuario);
$statement->bindValue(':nombre_completo', $nombre);
$statement->bindValue(':correo', $correo);
$statement->bindValue(':clave', $clave);
$statement->bindValue(':rol', $rol);
$statement->bindValue(':estado',$estado);

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
////////////// Actualizar la tabla /////////
$consulta = "DELETE FROM `usuarios` WHERE `id_usuario`=:id_usuario";
$sql = $connect-> prepare($consulta);
$sql -> bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
$id_usuario=trim($_POST['id']);
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
$id_usuario=trim($_POST['id']);
$usuario=trim($_POST['usuario']);
$nombre=trim($_POST['nombre']);
$correo=trim($_POST['correo']);
$clave=trim($_POST['clave']);


///////// Fin informacion enviada por el formulario /// 

////////////// Actualizar la tabla /////////
$consulta = "UPDATE usuarios
SET `nombre_usuario`= :nombre_usuario, `nombre_completo` = :nombre_completo, `correo` = :correo, `clave` =:clave  WHERE `id_usuario` = :id_usuario";
$sql = $connect->prepare($consulta);
$sql->bindParam(':nombre_usuario',$usuario,PDO::PARAM_STR);
$sql->bindParam(':nombre_completo',$nombre,PDO::PARAM_STR);
$sql->bindParam(':correo',$correo,PDO::PARAM_STR);
$sql->bindParam(':clave',$clave,PDO::PARAM_STR);
$sql->bindParam(':id_usuario',$id_usuario,PDO::PARAM_INT);




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


}
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


