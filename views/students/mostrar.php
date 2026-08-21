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
  $foto = '../Assets/img/subidas/user.jpg'; // Ruta de la imagen por defecto en caso de no haber imagen en la sesión
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

                <li  class="active">
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
                                        <a href="#">👨‍🏫 6. REGISTRAR PROFESORES</a>
                                    </li>
                                    <li>
                                        <a href="#">✅ Ejemplo: Registrar al docente María González</a>
                                    </li>
                                    <li>
                                        <a href="#">🔹 Paso 1: Entra en el módulo "Profesores".</a>
                                    </li>
                                    <li>
                                        <a href="#">🔹 Paso 2: Clic en "Agregar Profesor".</a>
                                    </li>
                                    <li>
                                        <a href="#">🔹 Paso 3: Completa con cédula, nombre y correo.</a>
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
          <h2 class="ml-lg-2">Estudiantes</h2>
        </div>

        <div class="col-sm-12 p-0 d-flex justify-content-lg-end justify-content-center">
          <a href="#addEmployeeModal" class="btn btn-success" data-toggle="modal">
          <i class="material-icons">&#xE147;</i> </a>

          <a href="printestudiantes.php" class="btn btn-danger">
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
$sentencia = $connect->query("SELECT  COUNT(*) AS conteo , estudiantes.id_estudiante, estudiantes.lugar_nacimiento, estudiantes.entidad_federal, estudiantes_planteles.id_estudiante, estudiantes_planteles.numero_plantel, seccion.id_seccion, estudiantes.cedula, estudiantes.nombres, estudiantes.apellidos, estudiantes.edad, estudiantes.sexo, estudiantes.fecha_nacimiento, estudiantes.direccion, estudiantes.tlf_estudiante, estudiantes.correo, estudiantes.foto, estudiantes.fecha, estudiantes.estado
FROM estudiantes 
INNER JOIN estudiantes_planteles ON estudiantes.id_estudiante = estudiantes_planteles.id_estudiante 
INNER JOIN seccion ON estudiantes.id_seccion = seccion.id_seccion");

$conteo = $sentencia->fetchObject()->conteo ?? 0; // Asigna 0 si no hay resultados
$paginas = ($conteo > 0) ? ceil($conteo / $productosPorPagina) : 1; // Evita división por 0



// Definir y asignar un valor a la variable $id_estudiante


// Verificar que $id_estudiante tiene un valor antes de ejecutar la consulta

    // Consulta para obtener los datos de los estudiantes
    $sentencia = $connect->prepare("SELECT  seccion.nombre_seccion, estudiantes.lugar_nacimiento, estudiantes.entidad_federal, estudiantes.id_estudiante, estudiantes.id_seccion, estudiantes.cedula, estudiantes.nombres, estudiantes.apellidos, estudiantes.edad, estudiantes.sexo, estudiantes.fecha_nacimiento, estudiantes.direccion, estudiantes.tlf_estudiante, estudiantes.correo, estudiantes.foto, estudiantes.fecha, estudiantes.estado, COUNT(estudiantes_planteles.id_plantel) AS numero_plantel
    FROM estudiantes 
    INNER JOIN seccion ON estudiantes.id_seccion = seccion.id_seccion
    INNER JOIN estudiantes_planteles ON estudiantes.id_estudiante = estudiantes_planteles.id_estudiante
    GROUP BY estudiantes.id_estudiante, estudiantes.nombres LIMIT ? OFFSET ?");


$sentencia->execute([$limit, $offset]);
$productos = $sentencia->fetchAll(PDO::FETCH_ASSOC);

?>


    <table class="table table-striped table-hover">
      <thead>
        <tr>
            
          <th>Planteles</th>        
          <th>Seccion</th>
          <th>Cedula</th>
          <th>Nombres</th>
          <th>Apellidos</th>
          <th>Edad</th>
          <th>Sexo</th>
          <th>Lugar de nacimiento</th>
          <th>Fecha de nacimiento</th>
          <th>Entidad federal</th>
          <th>Direccion</th>
          <th>Telefono</th>
          <th>Correo</th>
          <th>foto</th>
          <th>Fecha</th>
          <th>Estado</th>
          <th>Entrar</th>
          <th>Imprimir</th>
          <th>Editar</th>
          <th>Eliminar</th>
        </tr>
      </thead>
      <tbody>
          <?php foreach($productos as $producto){ ?>
            <tr>
           
            
            <td><?php echo $producto['numero_plantel'] ?></td>
            <td><?php echo $producto['nombre_seccion'] ?></td>
            <td><?php echo $producto['cedula'] ?></td>
        <td><?php echo $producto['nombres'] ?></td>
        <td><?php echo $producto['apellidos'] ?></td>
        <td><?php echo $producto['edad'] ?></td>
        <td><?php echo $producto['sexo'] ?></td>
        <td><?php echo $producto['lugar_nacimiento'] ?></td>
        <td><?php echo $producto['fecha_nacimiento'] ?></td>
        <td><?php echo $producto['entidad_federal'] ?></td>
        <td><?php echo $producto['direccion'] ?></td>
        <td><?php echo $producto['tlf_estudiante'] ?></td>
        <td><?php echo $producto['correo'] ?></td>
        <td><img src="../../Assets/img/subidas/<?php echo $producto['foto'] ?>" width='90'></td>
        <td><?php echo $producto['fecha'] ?></td>
               
               <td>
                       

                        <?php if($producto['estado']==1 )  { ?> 
        <span class="badge badge-success">Activo</span>
    <?php  }   else {?> 
        <span class="badge badge-danger">No activo</span>
        <?php  } ?>  
                            
                    </td>
                    <td>
    <a href="entrar.php?id=<?php echo htmlspecialchars($producto['id_estudiante']); ?>" 
       class="btn btn-primary text-white">
       <i class="material-icons" data-toggle="tooltip" title="Entrar">login</i>
    </a>
</td>
 <td>
<a href="plantilla.php?id=<?php echo htmlspecialchars($producto['id_estudiante']); ?>" 
       class="btn btn-danger text-white">
       <i class="material-icons" data-toggle="tooltip" title="Imprimir">print</i>
    </a>
    </td>
               <td>
<form method='POST' action='<?php $_SERVER['PHP_SELF'] ?>'>
<input type='hidden' name='id_estudiante' value="<?php echo  $producto['id_estudiante']; ?>">
<button name='editar' class='btn btn-warning text-white'><i class='material-icons' data-toggle='tooltip' title='Edit'>&#xE254;</i></button>
</form>
                   
               </td>
               <td>
<form  onsubmit="return confirm('Realmente desea eliminar el registro?');" method='POST' action='<?php $_SERVER['PHP_SELF'] ?>'>
<input type='hidden' name='id_estudiante' value="<?php echo  $producto['id_estudiante']; ?>">
<button name='eliminar' class='btn btn-square btn-outline-danger m-2 text-red' ><i class='material-icons'  title='Delete'>&#xE872;</i></button>
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
    $idstu = $_POST['id_estudiante'];

    // Consulta para obtener los datos del estudiante
    $sql = "SELECT estudiantes.id_estudiante, estudiantes_planteles.id_estudiante, seccion.id_seccion, estudiantes.cedula, estudiantes.nombres, estudiantes.apellidos, estudiantes.edad, estudiantes.sexo, estudiantes.fecha_nacimiento, estudiantes.lugar_nacimiento, estudiantes.entidad_federal, estudiantes.direccion, estudiantes.tlf_estudiante, estudiantes.correo, estudiantes.foto, estudiantes.fecha, estudiantes.estado 
            FROM estudiantes 
            INNER JOIN seccion ON estudiantes.id_seccion = seccion.id_seccion 
            INNER JOIN estudiantes_planteles ON estudiantes.id_estudiante = estudiantes_planteles.id_estudiante
            WHERE estudiantes.id_estudiante = :id_estudiante";
          

    $stmt = $connect->prepare($sql);
    $stmt->bindParam(':id_estudiante', $idstu, PDO::PARAM_INT); 
    $stmt->execute();
    $obj = $stmt->fetchObject();

    // Asegurarse de que las variables estén definidas
    $id_estudiante = isset($obj->id_estudiante) ? $obj->id_estudiante : '';
    $id_plantel = isset($obj->id_plantel) ? $obj->id_plantel : '';
    $id_seccion = isset($obj->id_seccion) ? $obj->id_seccion : '';
    $cedula = isset($obj->cedula) ? $obj->cedula : '';
    $nombres = isset($obj->nombres) ? $obj->nombres : '';
    $apellidos = isset($obj->apellidos) ? $obj->apellidos : '';
    $edad = isset($obj->edad) ? $obj->edad : ''; // Asegurarse de que la variable $edad esté definida
    $sexo = isset($obj->sexo) ? $obj->sexo : '';
    $lugar_nacimiento = isset($obj->lugar_nacimiento) ? $obj->lugar_nacimiento : '';
    $fecha_nacimiento = isset($obj->fecha_nacimiento) ? $obj->fecha_nacimiento : '';
    $entidad_federal = isset($obj->entidad_federal) ? $obj->entidad_federal : '';
    $direccion = isset($obj->direccion) ? $obj->direccion : '';
    $tlf_estudiante = isset($obj->tlf_estudiante) ? $obj->tlf_estudiante : '';
    $correo = isset($obj->correo) ? $obj->correo : '';
    $fecha = isset($obj->fecha) ? $obj->fecha : '';
    ?>

    <div class="col-12 col-md-12"> 

<form role="form" method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>">
<input value="<?php echo $id_estudiante;?>" name="id_estudiante" type="hidden">
 <div class="form-row">

  
   <?php 
    $stmt = $connect->prepare('SELECT estudiantes_planteles.id_plantel, estudiantes_planteles.id_estudiante, estudiantes_planteles.numero_plantel 
    FROM estudiantes_planteles 
    INNER JOIN planteles ON estudiantes_planteles.id_plantel = planteles.id_plantel');
    $stmt->execute();
?>
 <div class="form-group col-md-12">   
    <label for="incrementSelect">Plantel</label>
               <div class="input-group" id="dinamic">                                    
                                    <i class="material-icons">
    <button type="button" id="agregar" class="form-control">   
                                        &#xE147;</i>
                                         </a>                                  
                                        </button>
                                        <script>
// Constantes para el div contenedor de los selects y el botón de agregar
const contenedor = document.querySelector('#dinamic');
const btnAgregar = document.querySelector('#agregar');

// Variable para el total de elementos agregados
let total = 1;
let planteles = [];

/**
 * Obtener planteles desde la base de datos y almacenarlos en la variable `planteles`
 */
fetch('obtener_planteles.php')
    .then(response => response.json())
    .then(data => {
        planteles = data;
    })
    .catch(error => console.error('Error:', error));

/**
 * Método que se ejecuta cuando se da clic al botón de agregar elementos
 */
btnAgregar.addEventListener('click', e => {
    if (total <= 5) {
        let div = document.createElement('div');
        div.innerHTML = `<label >${total++}</label> - <select name="planteles[]" required>${getPlantelesOptions()}</select><button type="button" onclick="eliminar(this)">Eliminar</button>`;
        contenedor.appendChild(div);
    } else {
        alert("No se pueden agregar más de 5 planteles.");
    }
})

/**
 * Método para obtener las opciones de planteles en formato HTML
 * @returns {string}
 */
const getPlantelesOptions = () => {
    let options = '<option value="">Seleccione un plantel</option>';
    planteles.forEach(plantel => {
        options += `<option value="${plantel.id_plantel}">${plantel.nombre}</option>`;
    });
    return options;
}

/**
 * Método para eliminar el div contenedor del select
 * @param {this} e 
 */
const eliminar = (e) => {
    const divPadre = e.parentNode;
    contenedor.removeChild(divPadre);
    actualizarContador();
};

/**
 * Método para actualizar el contador de los elementos agregados
*/
const actualizarContador = () => {
    let divs = contenedor.total++;
    total = 1;
    for (let i = 0; i < divs.length; i++) {
        divs[i].children[0].innerHTML = total++;
    }//end for
};
</script>
  </div>
</div>

<script>
    let currentValue = 0; // Inicializa en 0

function incrementValue() {
    currentValue += 1; // Incrementa el valor de uno en uno
    document.getElementById('autoIncrementInput').value = currentValue; // Asigna el valor incrementado al input
}

// Inicializa el valor del input cuando se carga la página
document.addEventListener('DOMContentLoaded', (event) => {
    document.getElementById('autoIncrementInput').value = currentValue;
});
</script> 

 <div class="form-group col-md-6"> 
    <label for="seccionSelect">Seleccionar Seccion</label>
    <select id="seccionSelect" class="form-control" onchange="updateSeccionInput()">
    <?php 
    $stmt = $connect->prepare('SELECT seccion.id_seccion, seccion.nombre_seccion FROM seccion');
    $stmt->execute();

while($row=$stmt->fetch(PDO::FETCH_ASSOC))
        {
            extract($row);
            ?>
            <option value="<?php echo $id_seccion; ?>"><?php echo $nombre_seccion; ?></option>
            <?php
        }
        ?>
   
   
    </select>
</div>
 

    <div class="form-group col-md-6">
      <label for="nombres">Cedula</label>
      <input value="<?php echo $cedula;?>" name="cedula" type="text" class="form-control" placeholder="Cedula">
    </div>

     <div class="form-group col-md-6">
      <label for="nombres">Nombres</label>
      <input value="<?php echo $nombres;?>" name="nombres" type="text" class="form-control" placeholder="nombres">
      
    </div>



   
  <div class="form-group col-md-6">
      <label for="nombres">Apellidos</label>
      <input value="<?php echo $apellidos;?>" name="apellidos" type="text" class="form-control" placeholder="Apellidos">
   
</div>
  <div class="form-group col-md-6">
      <label for="nombres">Edad</label>
      <input value="<?php echo $edad;?>" name="edad" type="text" class="form-control" placeholder="Edad">
    </div>

 

    <div class="form-group col-md-6">
      <label for="nombres">Sexo</label>
      <select value="<?php echo $obj->sexo;?>" required name="sexo" class="form-control">    
      <option value="Femenino">FEMENINO</option>
      <option value="Masculino">MASCULINO</option>
   
    </select>
    </div>

     <div class="form-group col-md-6">
      <label for="nombres">Lugar de nacimiento</label>
      <input value="<?php echo $lugar_nacimiento;?>" name="lugar_nacimiento" type="text" class="form-control" placeholder="Fecha de nacimiento">
    </div>

  <div class="form-group col-md-6">
      <label for="nombres">Fecha de nacimiento</label>
      <input value="<?php echo $fecha_nacimiento;?>" name="fecha_nacimiento" type="date" class="form-control" placeholder="Fecha de nacimiento">
    </div>
 
    <div class="form-group col-md-6">
      <label for="nombres">Entidad federal</label>
      <input value="<?php echo $entidad_federal;?>" name="entidad_federal" type="text" class="form-control" placeholder="Entidad federal">
 </div>
  <div class="form-group col-md-6">
      <label for="direccion">Direccion</label>
      <input value="<?php echo $direccion;?>" name="direccion" type="text" class="form-control" placeholder="Direccion">
    </div>
 
    <div class="form-group col-md-6">
      <label for="nombres">Telefono</label>
      <input value="<?php echo $tlf_estudiante ;?>" name="tlf_estudiante" type="number" class="form-control" placeholder="Telefono">
    </div>

    <div class="form-group col-md-6">
      <label for="nombres">Correo</label>
      <input value="<?php echo $correo ;?>" name="correo" type="text" class="form-control" placeholder="Correo">
    </div>



    <div class="form-group col-md-6">
      <label for="nombres">Fecha de inscripcion</label>
      <input value="<?php echo $fecha ;?>" name="fecha" type="date-time" class="form-control" placeholder="fecha de inscripcion">
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
        <form enctype="multipart/form-data" method="POST" autocomplete="off">
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
                                    <div class="input-group" id="dinamic">                                    
                                    <i class="material-icons">
                                    <button required type="button" id="agregar" class="form-control">   
                                        &#xE147;</i>
                                         </a>                                  
                                        </button>
                                        <script>
// Constantes para el div contenedor de los selects y el botón de agregar
const contenedor = document.querySelector('#dinamic');
const btnAgregar = document.querySelector('#agregar');

// Variable para el total de elementos agregados
let total = 1;
let planteles = [];

/**
 * Obtener planteles desde la base de datos y almacenarlos en la variable `planteles`
 */
fetch('obtener_planteles.php')
    .then(response => response.json())
    .then(data => {
        planteles = data;
    })
    .catch(error => console.error('Error:', error));

/**
 * Método que se ejecuta cuando se da clic al botón de agregar elementos
 */
btnAgregar.addEventListener('click', e => {
    if (total <= 5) {
        let div = document.createElement('div');
        div.innerHTML = `<label>${total++}</label> - <select name="planteles[]" required>${getPlantelesOptions()}</select><button type="button" onclick="eliminar(this)">Eliminar</button>`;
        contenedor.appendChild(div);
    } else {
        alert("No se pueden agregar más de 5 planteles.");
    }
})

/**
 * Método para obtener las opciones de planteles en formato HTML
 * @returns {string}
 */
const getPlantelesOptions = () => {
    let options = '<option value="">Seleccione un plantel</option>';
    planteles.forEach(plantel => {
        options += `<option value="${plantel.id_plantel}">${plantel.nombre}</option>`;
    });
    return options;
}

/**
 * Método para eliminar el div contenedor del select
 * @param {this} e 
 */
const eliminar = (e) => {
    const divPadre = e.parentNode;
    contenedor.removeChild(divPadre);
    actualizarContador();
};

/**
 * Método para actualizar el contador de los elementos agregados
*/
const actualizarContador = () => {
    let divs = contenedor.total++;
    total = 1;
    for (let i = 0; i < divs.length; i++) {
        divs[i].children[0].innerHTML = total++;
    }//end for
};
</script>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <div class="input-group">
                                        <select required id="seccionSelect" class="form-control" name="id_seccion">
                                            <?php
                                            $stmt = $connect->prepare('SELECT seccion.id_seccion, seccion.nombre_seccion FROM seccion');
                                            $stmt->execute();

                                            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                echo '<option required value="' . $row["id_seccion"] . '">' . $row["nombre_seccion"] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="text" name="txtdnis" placeholder="Cedula" required class="form-control"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="text" name="txtnoms" placeholder="Nombres" required class="form-control"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="text" name="txtapell" placeholder="Apellidos" required class="form-control"/>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="email" name="txtcors" required class="form-control" placeholder="Correo"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <div class="input-group">
                                        <select class="form-control" required name="txtsexs">
                                            <option selected>GÉNERO</option>
                                            <option value="Masculino">MASCULINO</option>
                                            <option value="Femenino">FEMENINO</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="text" name="txtedas" required class="form-control" placeholder="Edad"/>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="text" name="txtdirs" required class="form-control" placeholder="Dirección"/>
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
                                        <input type="text" name="txtlu" required class="form-control" placeholder="Lugar de nacimiento"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="date" name="txtfecs" required class="form-control" placeholder="Fecha de nacimiento"/>
                                    </div>
                                </div>
                            </div>
                           
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="text" name="txttlf" required class="form-control" placeholder="Telefono"/>
                                    </div>
                                </div>
                            </div>
                      
                        
                            <div class="col-sm-6">
                                 <div class="form-group">
                                    <div class="input-group">
                                        <input type="text" name="txtend" required class="form-control" placeholder="Entidad federal"/>
                                    </div>
                                </div>                               
                            </div>
                            

                            <div class="col-sm-10">
                                <div class="form-group">
                                    <label for="modal_contact_firstname">Foto</label>
                                    <div class="input-group" style="">
                                        <input type="file" id="imagen" name="foto" onchange="readURL(this);" data-toggle="tooltip">
                                        <img src="../../Assets/img/subidas/user.png" id="blah" alt="your image" style="max-width:90px;"/>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <button name='agregar' type="submit">GUARDAR</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">CANCELAR</button>
                    </div>
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
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['agregar'])) {
    try {
        $connect->beginTransaction(); // Inicia la transacción

        // Información enviada por el formulario
        $id_seccion = isset($_POST['id_seccion']) ? trim($_POST['id_seccion']) : null;
        $dnist = isset($_POST['txtdnis']) ? trim($_POST['txtdnis']) : null;
        $nomstu = isset($_POST['txtnoms']) ? trim($_POST['txtnoms']) : null;
        $apellidos = isset($_POST['txtapell']) ? trim($_POST['txtapell']) : null;
        $edast = isset($_POST['txtedas']) ? trim($_POST['txtedas']) : null;
        $direce = isset($_POST['txtdirs']) ? trim($_POST['txtdirs']) : null;
        $correo = isset($_POST['txtcors']) ? trim($_POST['txtcors']) : null;
        $sexes = isset($_POST['txtsexs']) ? trim($_POST['txtsexs']) : null;
        $lugar_nacimiento = isset($_POST['txtlu']) ? trim($_POST['txtlu']) : null;
        $entidad_federal = isset($_POST['txtend']) ? trim($_POST['txtend']) : null;
        $fenac = isset($_POST['txtfecs']) ? trim($_POST['txtfecs']) : null;
        $tlf_estudiante = isset($_POST['txttlf']) ? trim($_POST['txttlf']) : null;

        $imgFile = isset($_FILES['foto']['name']) ? $_FILES['foto']['name'] : null;
        $tmp_dir = isset($_FILES['foto']['tmp_name']) ? $_FILES['foto']['tmp_name'] : null;
        $imgSize = isset($_FILES['foto']['size']) ? $_FILES['foto']['size'] : null;



$upload_dir = '../../Assets/img/subidas/';
$defaultImage = 'user.png'; // nombre de la imagen predeterminada en esa carpeta

        if (!empty($imgFile)) {
            $imgExt = strtolower(pathinfo($imgFile, PATHINFO_EXTENSION));
            $valid_extensions = array('jpeg', 'jpg', 'png', 'gif');
            $foto = rand(1000, 1000000) . "." . $imgExt;

            if (in_array($imgExt, $valid_extensions) && $imgSize < 5000000) {
                move_uploaded_file($tmp_dir, $upload_dir . $foto);
            } else {
                throw new Exception("Error: Invalid image file or size too large.");
            }
        } else {
            $foto = $defaultImage;
        }

        // Inserción en la tabla `estudiantes`
        $stmt = $connect->prepare("INSERT INTO estudiantes (id_seccion, cedula, nombres, apellidos, edad, sexo, lugar_nacimiento, entidad_federal, fecha_nacimiento, direccion, tlf_estudiante, correo, estado, foto) 
        VALUES (:id_seccion, :cedula, :nombres, :apellidos, :edad, :sexo, :lugar_nacimiento, :entidad_federal, :fecha_nacimiento, :direccion, :tlf_estudiante, :correo, '1', :foto)");

        $stmt->bindParam(':id_seccion', $id_seccion);
        $stmt->bindParam(':cedula', $dnist);
        $stmt->bindParam(':nombres', $nomstu);
        $stmt->bindParam(':apellidos', $apellidos);
        $stmt->bindParam(':edad', $edast);
        $stmt->bindParam(':sexo', $sexes);
        $stmt->bindParam(':lugar_nacimiento', $lugar_nacimiento);
        $stmt->bindParam(':entidad_federal', $entidad_federal);
        $stmt->bindParam(':fecha_nacimiento', $fenac);
        $stmt->bindParam(':direccion', $direce);
        $stmt->bindParam(':tlf_estudiante', $tlf_estudiante);
        $stmt->bindParam(':correo', $correo);
        $stmt->bindParam(':foto', $foto);

        if ($stmt->execute()) {
            $id_estudiante = $connect->lastInsertId(); // Obtener el ID del estudiante recién insertado

            // Inserción en la tabla `estudiantes_planteles`
            $stmt2 = $connect->prepare("INSERT INTO estudiantes_planteles (id_estudiante, id_plantel) VALUES (:id_estudiante, :id_plantel)");

            foreach ($_POST['planteles'] as $id_plantel) {
                $stmt2->bindParam(':id_estudiante', $id_estudiante);
                $stmt2->bindParam(':id_plantel', $id_plantel);
                $stmt2->execute();
            }

            $connect->commit(); // Confirma la transacción

            echo '<script type="text/javascript">
            swal("¡Registrado!", "Agregado correctamente", "success").then(function() {
                window.location = "mostrar";
            });
            </script>';
        } else {
            throw new Exception("Error: Failed to insert student data.");
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
    }, 3000);
});

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




<?php  
if (isset($_POST['eliminar'])) {
    require '../../Config/config.php'; // Asegurar la conexión

    try {
        // Obtener ID de estudiante desde el formulario
        $id_estudiante = isset($_POST['id_estudiante']) ? trim($_POST['id_estudiante']) : null;

        // Validar que el ID no esté vacío
        if (empty($id_estudiante)) {
            die("<div class='alert alert-danger'>Error: ID de estudiante no válido.</div>");
        }

        // Iniciar la transacción
        $connect->beginTransaction();

        // Eliminar el registro en `estudiantes_planteles`
        $consultaEliminarRelacion = "DELETE FROM estudiantes_planteles WHERE id_estudiante = :id_estudiante";
        $stmtRelacion = $connect->prepare($consultaEliminarRelacion);
        $stmtRelacion->bindParam(':id_estudiante', $id_estudiante, PDO::PARAM_INT);
        $stmtRelacion->execute();

        // Eliminar el estudiante de la tabla `estudiantes`
        $consultaEliminarEstudiante = "DELETE FROM estudiantes WHERE id_estudiante = :id_estudiante";
        $stmtEstudiante = $connect->prepare($consultaEliminarEstudiante);
        $stmtEstudiante->bindParam(':id_estudiante', $id_estudiante, PDO::PARAM_INT);
        $stmtEstudiante->execute();

        // Verificar si la eliminación fue exitosa
        if ($stmtEstudiante->rowCount() > 0) {
            // Confirmar los cambios en la base de datos
            $connect->commit();

            echo '<script type="text/javascript">
            swal("¡Eliminado!", "Registro Eliminado correctamente", "success").then(function() {
            window.location = "mostrar";
        });
            </script>';
            exit(); // Detener ejecución después de la alerta
        } else {
            // Revertir cambios si el estudiante no fue eliminado
            $connect->rollBack();
            echo "<div class='alert alert-warning'>Error: No se encontró el registro para eliminar.</div>";
        }
    } catch (PDOException $e) {
        // Revertir cambios en caso de error
        $connect->rollBack();
        echo "<div class='alert alert-danger'>Error: " . htmlspecialchars($e->getMessage()) . "</div>";
    }
}

?>


  


  <?php
require '../../Config/config.php';

if (isset($_POST['actualizar'])) {
    try {
        $connect->beginTransaction(); // Inicia la transacción

        // Validar la existencia de `id_estudiante`
        if (!isset($_POST['id_estudiante'])) {
            throw new Exception("Error: ID del estudiante no proporcionado.");
        }

        $id_estudiante = trim($_POST['id_estudiante']);
        $dnist = filter_var(trim($_POST['cedula']), FILTER_SANITIZE_STRING);
        $nomstu = filter_var(trim($_POST['nombres']), FILTER_SANITIZE_STRING);
        $apellidos = filter_var(trim($_POST['apellidos']), FILTER_SANITIZE_STRING);
        $edast = trim($_POST['edad']);
        $sexes = trim($_POST['sexo']);
        $fenac = trim($_POST['fecha_nacimiento']);
        $lugar_nacimiento = filter_var(trim($_POST['lugar_nacimiento']), FILTER_SANITIZE_STRING);
        $entidad_federal = filter_var(trim($_POST['entidad_federal']), FILTER_SANITIZE_STRING);
        $direce = filter_var(trim($_POST['direccion']), FILTER_SANITIZE_STRING);
        $tlf_estudiante = trim($_POST['tlf_estudiante']);
        $correo = filter_var(trim($_POST['correo']), FILTER_VALIDATE_EMAIL);
        $fecha = trim($_POST['fecha']);

        if (empty($id_estudiante) || empty($dnist) || empty($nomstu) || empty($apellidos)) {
            throw new Exception("Error: Todos los campos obligatorios deben estar completos.");
        }

        // Actualizar datos del estudiante
        $sql = "UPDATE estudiantes SET 
                cedula = :cedula, nombres = :nombres, apellidos = :apellidos, edad = :edad, 
                sexo = :sexo, lugar_nacimiento = :lugar_nacimiento, entidad_federal = :entidad_federal, 
                fecha_nacimiento = :fecha_nacimiento, direccion = :direccion, tlf_estudiante = :tlf_estudiante, 
                correo = :correo, fecha = :fecha
                WHERE id_estudiante = :id_estudiante";

        $stmt = $connect->prepare($sql);
        $stmt->bindParam(':id_estudiante', $id_estudiante, PDO::PARAM_INT);
        $stmt->bindParam(':cedula', $dnist, PDO::PARAM_STR);
        $stmt->bindParam(':nombres', $nomstu, PDO::PARAM_STR);
        $stmt->bindParam(':apellidos', $apellidos, PDO::PARAM_STR);
        $stmt->bindParam(':edad', $edast, PDO::PARAM_INT);
        $stmt->bindParam(':sexo', $sexes, PDO::PARAM_STR);
        $stmt->bindParam(':lugar_nacimiento', $lugar_nacimiento, PDO::PARAM_STR);
        $stmt->bindParam(':entidad_federal', $entidad_federal, PDO::PARAM_STR);
        $stmt->bindParam(':fecha_nacimiento', $fenac, PDO::PARAM_STR);
        $stmt->bindParam(':direccion', $direce, PDO::PARAM_STR);
        $stmt->bindParam(':tlf_estudiante', $tlf_estudiante, PDO::PARAM_STR);
        $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
        $stmt->bindParam(':fecha', $fecha, PDO::PARAM_STR);

        if (!$stmt->execute()) {
            throw new Exception("Error: Falló la actualización del estudiante.");
        }

        // Eliminar registros previos en `estudiantes_planteles`
        $stmtDel = $connect->prepare("DELETE FROM estudiantes_planteles WHERE id_estudiante = :id_estudiante");
        $stmtDel->bindParam(':id_estudiante', $id_estudiante, PDO::PARAM_INT);
        $stmtDel->execute();

        // Insertar nuevos registros en `estudiantes_planteles`
        if (!empty($_POST['planteles'])) {
            $stmt2 = $connect->prepare("INSERT INTO estudiantes_planteles (id_estudiante, id_plantel) VALUES (:id_estudiante, :id_plantel)");
            foreach ($_POST['planteles'] as $id_plantel) {
                $stmt2->bindParam(':id_estudiante', $id_estudiante);
                $stmt2->bindParam(':id_plantel', $id_plantel);
                $stmt2->execute();
            }
        }

        $connect->commit(); // Confirmar transacción

        echo '<script type="text/javascript">
        swal("¡Actualizado!", "Actualizado correctamente", "success").then(function() {
            window.location = "mostrar";
        });
        </script>';

    } catch (Exception $e) {
        $connect->rollBack(); // Revertir cambios en caso de error
        echo "<div class='content alert alert-danger'> Error: " . $e->getMessage() . "</div>";
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


