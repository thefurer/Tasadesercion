<?php
$servidor = "localhost";
$base_datos = "tasa_insercion";
$usuario = "root";
$clave = "";

$conn = new mysqli($servidor, $usuario, $clave , $base_datos);

$periodoEvaluacion = '';
$periodoSeleccionado = '';

if ($conn->connect_error) {
    die("Conexion Fallida:" . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $periodoSeleccionado = $_POST['periodo'];
    $anoSeleccionado = substr($periodoSeleccionado, 3); 
    $anoEvaluacion = $anoSeleccionado - 2;
    $periodoEvaluacion = substr($periodoSeleccionado, 0, 2) . '-' . $anoEvaluacion; 
}

$conn->close();
?>
