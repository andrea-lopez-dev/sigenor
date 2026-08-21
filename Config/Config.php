





<?php
if (!isset($_SESSION)) {
    session_start();
}

// Verifica si las constantes ya están definidas antes de definirlas
if (!defined('dbhost')) {
    define('dbhost', 'localhost');
}
if (!defined('dbuser')) {
    define('dbuser', 'root');
}
if (!defined('dbpass')) {
    define('dbpass', '');
}
if (!defined('dbname')) {
    define('dbname', 'sigenor');
}

require_once 'config.php'; // Asegúrate de añadir el punto y coma aquí

// Conectando a la base de datos
try {
    $connect = new PDO("mysql:host=" . dbhost . ";dbname=" . dbname, dbuser, dbpass);
    $connect->query("set names utf8;");
    $connect->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $connect->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
} catch (PDOException $e) {
    echo $e->getMessage();
}
?>


