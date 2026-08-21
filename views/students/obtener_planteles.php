<?php
$connect = new PDO('mysql:host=localhost;dbname=sigenor', 'root', '');

// Obtener los datos de la tabla planteles
$stmt = $connect->prepare('SELECT planteles.id_plantel, planteles.nombre FROM planteles');
$stmt->execute();

$planteles = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Enviar los datos en formato JSON
echo json_encode($planteles);
?>
