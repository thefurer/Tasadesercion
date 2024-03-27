<?php
$servidor = "localhost";
$base_datos = "tasa_insercion";
$usuario = "root";
$clave = "";

$conn = new mysqli($servidor, $usuario, $clave , $base_datos);

if ($conn->connect_error) {
    die("Conexion Fallida:" . $conn->connect_error);
}

$cod_facultad = $_POST['facultad'];

$sql = "SELECT cod_carrera, nom_carrera FROM carreras WHERE cod_facultad = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $cod_facultad);
$stmt->execute();
$result = $stmt->get_result();

$carreras = array();

while($row = $result->fetch_assoc()) {
    $carreras[] = $row;
}

echo json_encode($carreras);

$conn->close();
?>