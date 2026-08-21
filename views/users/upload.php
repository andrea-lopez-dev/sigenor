<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if(isset($_POST['agregar'])) {
    $imgFile = $_FILES['foto']['name'];
    $tmp_dir = $_FILES['foto']['tmp_name'];
    $imgSize = $_FILES['foto']['size'];

    $upload_dir = '../../Assets/img/subidas/';
    $imgExt = strtolower(pathinfo($imgFile, PATHINFO_EXTENSION));
    $valid_extensions = array('jpeg', 'jpg', 'png', 'gif');
    $foto = rand(1000, 1000000).".".$imgExt;

    if(in_array($imgExt, $valid_extensions)) {
        if($imgSize < 40000000) { // Tamaño del archivo < 40MB
            if(move_uploaded_file($tmp_dir, $upload_dir.$foto)) {
                $_SESSION['foto'] = $upload_dir.$foto;
                $connect = new PDO("mysql:host=localhost;dbname=sigenor", " ", " ");
                $stmt = $connect->prepare("UPDATE usuarios SET foto=:foto WHERE id=:id");
                $stmt->bindParam(':foto', $foto);
                $stmt->bindParam(':id', $_SESSION['id']);

                if($stmt->execute()) {
                    echo '<script type="text/javascript">
                    swal("¡Registrado!", "Agregado correctamente", "success").then(function() {
                        window.location = "../mostrar.php";
                    });
                    </script>';
                } else {
                    $errMSG = "Error al insertar....";
                }
            } else {
                $errMSG = "Lo siento, hubo un error al subir tu archivo.";
            }
        } else {
            $errMSG = "Lo siento, tu archivo es demasiado grande.";
        }
    } else {
        $errMSG = "Lo siento, solo se permiten archivos JPG, JPEG, PNG y GIF.";
    }

    if(isset($errMSG)) {
        echo '<script type="text/javascript">alert("'.$errMSG.'");</script>';
    }
}
?>
