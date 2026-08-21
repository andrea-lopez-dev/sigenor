<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'sigenor';

// Intentar la conexión a la base de datos usando mysqli
try {
    $conn = new mysqli($host, $user, $password, $database);

    // Verificar la conexión
    if ($conn->connect_error) {
        throw new Exception('Connection failed: ' . $conn->connect_error);
    } else {
        echo "Connected successfully";
    }
} catch (Exception $e) {
    echo "Connection failed: " . $e->getMessage();
}



 
    if (isset($_POST['agregar'])) {
        // Información enviada por el formulario
        $id_plantel = isset($_POST['id_plantel']) ? trim($_POST['id_plantel']) : null;    
        $id_seccion = isset($_POST['id_seccion']) ? trim($_POST['id_seccion']) : null; // Verificar la existencia de id_seccion
        $dnist = isset($_POST['txtdnis']) ? trim($_POST['txtdnis']) : null;
        $nomstu = isset($_POST['txtnoms']) ? trim($_POST['txtnoms']) : null;
        $apellidos = isset($_POST['txtapell']) ? trim($_POST['txtapell']) : null;
        $edast = isset($_POST['txtedas']) ? trim($_POST['txtedas']) : null;
        $direce = isset($_POST['txtdirs']) ? trim($_POST['txtdirs']) : null;
        $correo = isset($_POST['txtcors']) ? trim($_POST['txtcors']) : null;
        $sexes = isset($_POST['txtsexs']) ? trim($_POST['txtsexs']) : null;
        $fenac = isset($_POST['txtfecs']) ? trim($_POST['txtfecs']) : null;
        $tlf_estudiante = isset($_POST['txttlf']) ? trim($_POST['txttlf']) : null;
    
        $imgFile = isset($_FILES['foto']['name']) ? $_FILES['foto']['name'] : null;
        $tmp_dir = isset($_FILES['foto']['tmp_name']) ? $_FILES['foto']['tmp_name'] : null;
        $imgSize = isset($_FILES['foto']['size']) ? $_FILES['foto']['size'] : null;
    
        if (empty($dnist)) {
            $errMSG = "Please enter your dni.";
        } else if (empty($nomstu)) {
            $errMSG = "Please Enter your name.";
        } else if (empty($edast)) {
            $errMSG = "Please Enter your age.";
        } else if (empty($direce)) {
            $errMSG = "Please Enter your address.";
        } else if (empty($id_estudiante)) {
            $errMSG = "Please Enter your plantel.";
        } else if (empty($id_seccion)) {
            $errMSG = "Please Enter your seccion.";
        } else if (empty($apellidos)) {
            $errMSG = "Please Enter your apellidos.";
        } else if (empty($tlf_estudiante)) {
            $errMSG = "Please Enter your tlf_estudiante.";
        } else if (empty($correo)) {
            $errMSG = "Please Enter your email.";
        } else if (empty($sexes)) {
            $errMSG = "Please Enter your sex.";
        } else if (empty($fenac)) {
            $errMSG = "Please Enter your birth.";
        } else if (empty($imgFile)) {
            $errMSG = "Please Select Image File.";
        } else {
            $upload_dir = '../../Assets/img/subidas/'; // upload directory
            $imgExt = strtolower(pathinfo($imgFile, PATHINFO_EXTENSION)); // get image extension
    
            // valid image extensions
            $valid_extensions = array('jpeg', 'jpg', 'png', 'gif'); // valid extensions
    
            // rename uploading image
            $foto = rand(1000, 1000000) . "." . $imgExt;
    
            // allow valid image file formats
            if (in_array($imgExt, $valid_extensions)) {
                // Check file size '5MB'
                if ($imgSize < 5000000) {
                    move_uploaded_file($tmp_dir, $upload_dir . $foto);
                } else {
                    $errMSG = "Sorry, your file is too large.";
                }
            } else {
                $errMSG = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            }
        }
    
      
        // if no error occurred, continue ...
        if (!isset($errMSG)) {
            // Inserción en la tabla `estudiantes`
            $stmt = $connect->prepare("INSERT INTO estudiantes (id_seccion, cedula, nombres, apellidos, edad, sexo, fecha_nacimiento, direccion, tlf_estudiante, correo, estado, foto) VALUES (:id_seccion, :cedula, :nombres, :apellidos, :edad, :sexo, :fecha_nacimiento, :direccion, :tlf_estudiante, :correo, '1', :foto)");
            $stmt->bindParam(':id_seccion', $id_seccion);
            $stmt->bindParam(':cedula', $dnist);
            $stmt->bindParam(':nombres', $nomstu);
            $stmt->bindParam(':apellidos', $apellidos);
            $stmt->bindParam(':edad', $edast);
            $stmt->bindParam(':sexo', $sexes);
            $stmt->bindParam(':fecha_nacimiento', $fenac);
            $stmt->bindParam(':direccion', $direce);
            $stmt->bindParam(':correo', $correo);
            $stmt->bindParam(':tlf_estudiante', $tlf_estudiante);
            $stmt->bindParam(':foto', $foto);
        
            if ($stmt->execute()) {
                // Obtener el ID del estudiante recién insertado
                $id_estudiante = $connect->lastInsertId();
                
                // Inserción en la tabla `estudiantes_planteles`
                $stmt2 = $connect->prepare("INSERT INTO estudiantes_planteles (id_estudiante, id_plantel) VALUES (:id_estudiante, :id_plantel)");
        
                // Recorrer los planteles seleccionados y agregarlos
                foreach ($_POST['id_plantel'] as $id_plantel) {
                    $stmt2->bindParam(':id_estudiante', $id_estudiante);
                    $stmt2->bindParam(':id_plantel', $id_plantel);
      
        
                echo '<script type="text/javascript">
                swal("¡Registrado!", "Agregado correctamente", "success").then(function() {
                    window.location = "mostrar";
                });
                </script>';
            } else {
                $errMSG = "Error while inserting....";
                print_r($stmt->errorInfo());
            }
        }
         }
          }
          
    ?>