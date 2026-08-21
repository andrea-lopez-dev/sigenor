<?php
error_reporting(E_PARSE);

// Configuración de conexión a la base de datos
define("USER", "root");
define("SERVER", "localhost");
define("BD", "sigenor");
define("PASS", "");
define("BACKUP_PATH", "../backup/");

date_default_timezone_set('America/Caracas');

// Clase para gestionar la base de datos y backups
class SGBD {
    private static $con;

    public static function conectar() {
        self::$con = new mysqli(SERVER, USER, PASS, BD);
        if (self::$con->connect_error) {
            die("Error en la conexión: " . self::$con->connect_error);
        }
        self::$con->set_charset("utf8");
    }

    public static function sql($query) {
        if (!self::$con) {
            self::conectar();
        }
        $consul = self::$con->query($query);
        return $consul ? $consul : false;
    }

    public static function backupDB() {
        $day = date("d");
        $month = date("m");
        $year = date("Y");
        $hour = date("H-i-s");
        $fecha = "{$day}_{$month}_{$year}";
        $DataBASE = "{$fecha}_({$hour}_hrs).sql";

        $tables = [];
        $result = self::sql("SHOW TABLES");
        if ($result) {
            while ($row = $result->fetch_row()) {
                $tables[] = $row[0];
            }

            $sql = "SET FOREIGN_KEY_CHECKS=0;\n\n";
            $sql .= "CREATE DATABASE IF NOT EXISTS " . BD . ";\n\n";
            $sql .= "USE " . BD . ";\n\n";

            foreach ($tables as $table) {
                $result = self::sql("SELECT * FROM $table");
                if ($result) {
                    $numFields = $result->field_count;
                    $row2 = self::sql("SHOW CREATE TABLE $table")->fetch_row();
                    $sql .= "\n\n" . $row2[1] . ";\n\n";

                    while ($row = $result->fetch_row()) {
                        $sql .= "INSERT INTO $table VALUES(";
                        for ($j = 0; $j < $numFields; $j++) {
                            $row[$j] = addslashes($row[$j]);
                            $row[$j] = str_replace("\n", "\\n", $row[$j]);

                            $sql .= isset($row[$j]) ? "\"{$row[$j]}\"" : '""';
                            if ($j < ($numFields - 1)) {
                                $sql .= ',';
                            }
                        }
                        $sql .= ");\n";
                    }
                    $sql .= "\n\n";
                } else {
                    return false;
                }
            }

            chmod(BACKUP_PATH, 0777);
            $sql .= "SET FOREIGN_KEY_CHECKS=1;";
            if (file_put_contents(BACKUP_PATH . $DataBASE, $sql)) {
                return "Copia de seguridad realizada";
            } else {
                return "Ocurrió un error";
            }
        } else {
            return "Error al obtener tablas";
        }
    }

    public static function restoreDB($filePath) {
        if (!file_exists($filePath)) {
            return "El archivo no existe.";
        }

        $sql = file_get_contents($filePath);
        $queries = explode(";", $sql);

        self::conectar();
        foreach ($queries as $query) {
            $query = trim($query);
            if (!empty($query)) {
                self::sql($query);
            }
        }

        return "Restauración completada.";
    }
}

SGBD::conectar();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="stylesheet" href="styles.css">
	<title>Backup y Restore</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function showAlert(message, type) {
            Swal.fire({
                title: message,
                icon: type,
                confirmButtonText: 'Aceptar',
                allowOutsideClick: false
            }).then(() => {
                window.location.href = 'index.php'; // Redirige a la página principal
            });
        }
    </script>
</head>
<body>
<div class="loading-overlay" id="loading">
    <img src="loading.gif" alt="Cargando...">
</div>

    <h2>Gestión de Base de Datos</h2>

    <form method="POST">
        <button class="btn" type="submit" name="backup">Realizar copia de seguridad</button>
    </form>

    <?php
    if (isset($_POST['backup'])) {
        $message = SGBD::backupDB();
        $type = (strpos($message, "error") !== false) ? "error" : "success";
        echo "<script>showAlert('$message', '$type');</script>";
    }
    ?>

    <form action="" method="POST">
        <label>Selecciona un punto de restauración</label><br>
        <select name="restorePoint">
            <option value="" disabled selected>Selecciona un punto de restauración</option>
            <?php
                $ruta = BACKUP_PATH;
                if (is_dir($ruta)) {
                    $aux = opendir($ruta);
                    if ($aux) {
                        while (($archivo = readdir($aux)) !== false) {
                            if ($archivo !== "." && $archivo !== "..") {
                                $nombrearchivo = str_replace(".sql", "", $archivo);
                                $nombrearchivo = str_replace("-", ":", $nombrearchivo);
                                $ruta_completa = $ruta . $archivo;

                                if (!is_dir($ruta_completa)) {
                                    echo '<option value="' . htmlspecialchars($ruta_completa) . '">' . htmlspecialchars($nombrearchivo) . '</option>';
                                }
                            }
                        }
                        closedir($aux);
                    } else {
                        echo "<option disabled>No se pudo abrir el directorio</option>";
                    }
                } else {
                    echo "<option disabled>$ruta no es una ruta válida</option>";
                }
            ?>
        </select>
        <button class="btn" type="submit" name="restore">Restaurar</button>
    </form>

    <?php
    if (isset($_POST['restore']) && isset($_POST['restorePoint'])) {
        $message = SGBD::restoreDB($_POST['restorePoint']);
        $type = (strpos($message, "error") !== false) ? "error" : "success";
        echo "<script>showAlert('$message', '$type');</script>";
    }
    ?>

    <br><br>
    <a href="../Views/admin/pages-admin.php"><button class="btn-home">Volver a la página principal</button></a>

</body>
<?php
echo "<script>showLoading();</script>";
?>
<script>
    function showLoading() {
        document.getElementById("loading").style.display = "block";
        setTimeout(() => { window.location.href = 'index.php'; }, 2000);
    }
</script>

</html>
