<?php
$servidor = "localhost";
$base_datos = "tasa_insercion";
$usuario = "root";
$clave = "";

$conn = new mysqli($servidor, $usuario, $clave , $base_datos);

if ($conn->connect_error) {
    die("Conexion Fallida:" . $conn->connect_error);
}

$cod_carrera = $_POST['carrera'];

$sql = "SELECT cod_periodo, nom_periodo FROM periodos WHERE cod_carrera = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $cod_carrera);
$stmt->execute();
$result = $stmt->get_result();

$periodos = array();

while($row = $result->fetch_assoc()) {
    $periodos[] = $row;
}

echo json_encode($periodos);
$carrera = $_POST['carrera'];
$sql = "SELECT DISTINCT periodo FROM tabla WHERE carrera = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $carrera);
$stmt->execute();
$result = $stmt->get_result();
$periodos = $result->fetch_all(MYSQLI_ASSOC);
echo json_encode($periodos);

$conn->close();
?>