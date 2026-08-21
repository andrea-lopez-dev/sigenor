<?php
error_reporting(E_PARSE);

// Configuración de conexión
define("USER", "root");
define("SERVER", "localhost");
define("BD", "sigenor");
define("PASS", "");
define("BACKUP_PATH", "../backup/");

date_default_timezone_set('America/El_Salvador');

// Clase para la conexión y consultas
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
        if (!$consul) {
            echo "Error en la consulta SQL ejecutada: " . self::$con->error;
            return false;
        }
        return $consul;
    }

    public static function limpiarCadena($valor) {
        if (!self::$con) {
            self::conectar();
        }
        return self::$con->real_escape_string($valor);
    }

    public static function cerrarConexion() {
        if (self::$con) {
            self::$con->close();
        }
    }
}

SGBD::conectar();
$result = SGBD::sql("SHOW TABLES");

if ($result) {
    while ($row = $result->fetch_row()) {
        echo "Tabla encontrada: " . $row[0] . "<br>";
    }
} else {
    echo "Error obteniendo las tablas.";
}

SGBD::cerrarConexion();
?>
