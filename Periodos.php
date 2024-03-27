<?php
$servidor = "localhost";
$base_datos = "tasa_insercion";
$usuario = "root";
$clave = "";

$conn = new mysqli($servidor, $usuario, $clave , $base_datos);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $periodoSeleccionado = isset($_POST['periodo']) ? $_POST['periodo'] : null;
    $periodoInicial = isset($_POST['periodoInicial']) ? $_POST['periodoInicial'] : null;
    $cod_carrera = isset($_POST['cod_carrera']) ? $_POST['cod_carrera'] : null;

    if ($periodoSeleccionado && $periodoInicial && $cod_carrera) {
        // Verificar si ya existe un registro con el mismo cod_carrera y periodoSeleccionado
        $sql = "SELECT * FROM periodos WHERE cod_carrera = '$cod_carrera' AND periodoSeleccionado = '$periodoSeleccionado'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Si existe un registro, mostrar un mensaje de error
            echo "Error: Ya existe una evaluación para este código de carrera y periodo seleccionado";
        } else {
            // Si no existe un registro, insertar los datos
            $sql = "INSERT INTO periodos (cod_carrera, periodoSeleccionado, periodoInicial) VALUES ('$cod_carrera', '$periodoSeleccionado', '$periodoInicial')";

            if ($conn->query($sql) === TRUE) {
                echo "New record created successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    } else {
        echo "Error: Missing required fields";
    }
    $conn->close();
}
?>