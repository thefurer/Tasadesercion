<?php
$servidor = "localhost";
$base_datos = "tasa_insercion";
$usuario = "root";
$clave = "";

$conn = new mysqli($servidor, $usuario, $clave , $base_datos);

if ($conn->connect_error) {
    die("Conexion Fallida:" . $conn->connect_error);
}


$sql = "SELECT cod_facultad, nom_facultad FROM facultades";
$result = $conn->query($sql);

$facultades = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $facultades[] = $row;
    }
} 

echo json_encode($facultades);

$conn->close();
?>