<?php
  require '../Config/Config.php';

  if(isset($_POST['login'])) {
    $errMsg = '';

    // Get data from FORM
    $nombre_usuario = $_POST['nombre_usuario'];

    $clave = $_POST['clave'];

    if($nombre_usuario == '')
      $errMsg = 'Digite su usuario';
    if($clave == '')
      $errMsg = 'Digite su contraseña';

    if($errMsg == '') {
      try {
$stmt = $connect->prepare('SELECT id_usuario, nombre_usuario,nombre_completo,correo,clave,rol, foto FROM usuarios WHERE
  nombre_usuario = :nombre_usuario');


        $stmt->execute(array(
          ':nombre_usuario' => $nombre_usuario


          ));
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if($data == false){
          $errMsg = "Usuario $nombre_usuario no encontrado.";
        }
        else {
          if($clave == $data['clave']) {

            $_SESSION['id_usuario'] = $data['id_usuario'];
            $_SESSION['nombre_usuario'] = $data['nombre_usuario'];
            $_SESSION['nombre_completo'] = $data['nombre_completo'];
            $_SESSION['correo'] = $data['correo'];
            $_SESSION['clave'] = $data['clave'];
            $_SESSION['rol'] = $data['rol'];
            $_SESSION['foto'] = $data['foto'];
            $_SESSION['fecha'] = $data['fecha'];


    if($_SESSION['rol'] == 1){
          header('Location: admin/pages-admin.php');

        }else if($_SESSION['rol'] == 2){
          header('Location: panel-cliente/cliente.php');
        }
            exit;
          }
          else
            $errMsg = 'Contraseña incorrecta.';
        }
      }
      catch(PDOException $e) {
        $errMsg = $e->getMessage();
      }
    }
  }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LOGIN SIGENOR</title>
    <link href="../Assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../Assets/css/awesome-bootstrap-checkbox.min.css" rel="stylesheet">
    <link href="../Assets/css/font-awesome.min.css" rel="stylesheet">
    <link href="http://localhost/sistema_escolar/Assets/css/style.css" rel="stylesheet">
    <link rel="icon" type="image/png" sizes="96x96" href="../Assets/img/logo_sistem.png">
   

  </head>
  
  <body>
  <div class="video-background">
  <video autoplay loop muted playsinline>
    <source src="../Assets/img/background.mp4" type="video/mp4">
  </video>
</div>
  <span class="header-ribbon">
  <div class="container-fluid">
    <div class="row">
      
        <div class="col-md-4 col-md-offset-4 col-centered">
           
            <div class="login-panel">
                <form  method="POST" autocomplete="off"   role="form">
                <img src="../Assets/img/logo_sistem.png" class="img-fluid" style="width:300px; height:200px; margin-left:25px;">
                <h4 class="login-panel-tagline" style="margin-left:30px">U.E.P. NOCT. BR. RAFAEL RANGEL</h4>
                <p class="login-panel-tagline" style="margin-left:90px" >GESTIÓN ACADÉMICA</p>
                  <?php
    if(isset($errMsg)){
    echo '<div style="color:#FF0000;text-align:center;font-size:20px;">'.$errMsg.'</div>';  
         }
?>
                <div class="login-panel-section">
                    <div class="form-group">
                        <div class="input-group margin-bottom-sm">
                            <span class="input-group-addon"><i class="fa fa-user fa-fw" aria-hidden="true"></i></span>
                            <input class="form-control" name="nombre_usuario" value="<?php if(isset($_POST['nombre_usuario'])) echo $_POST['nombre_usuario'] ?>" autocomplete="off" pattern="[a-zA-Z0-9]{4,20}" maxlength="20" required type="text" placeholder="Nombre del usuario">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-key fa-fw" aria-hidden="true"></i></span>
                            <input class="form-control" name="clave" value="<?php if(isset($_POST['clave'])) echo ($_POST['clave']) ?>" required type="password" placeholder="Contraseña">
                        </div>
                    </div>
                    <div class="checkbox checkbox-circle checkbox-success checkbox-small">
                        <input type="checkbox" id="checkbox1">
                        <label for="checkbox1">Recuérdame</label>
              
                    </div>
                </div>
                <div class="login-panel-section">
                    <button type="submit" name='login' class="btn btn-default"><i class="fa fa-sign-in fa-fw" aria-hidden="true"></i> Iniciar sesión</button>
                </div>
            </form>
            </div>

        </div>
      </div>
    </div>
    <script src="../Assets/js/jquery.min.js"></script>
    <script src="../Assets/js/bootstrap.min.js"></script>
    
<footer class="text-center py-3 bg-light">
  <small> S.I.G.E.N.O.R UPTMA v1.0 &copy; <?php echo date("Y"); ?>  Todos los derechos reservados.</small>
</footer>
<STYLE>
footer {
  position: relative;
  bottom: 22PX;
  width: 100%;
}
</style>
  </body>
</html>