<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$servidor = "localhost";
$base_datos = "tasa_insercion";
$usuario = "root";
$clave = "";

$conn = new mysqli($servidor, $usuario, $clave , $base_datos);

if ($conn->connect_error) {
    die("Conexion Fallida:" . $conn->connect_error);
}

$periodoSeleccionado = $_POST['periodoSeleccionado'];

$periodoInicial = determinaPeriodoInicial($periodoSeleccionado);
$sql = "INSERT INTO periodos (cod_carrera, periodoSeleccionado, periodoInicial) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $cod_carrera, $periodoSeleccionado, $periodoInicial);
$stmt->execute();

$conn->close();

$filename = $cod_carrera . '_' . $periodoInicial . '.xlsx'; 

echo "cod_carrera: $cod_carrera<br>";
echo "periodoSeleccionado: $periodoSeleccionado<br>";
echo "periodoInicial: $periodoInicial<br>";
echo "filename: $filename<br>";

if (!file_exists($filename)) {
    die("El archivo $filename no existe.");
}
$spreadsheet = IOFactory::load($filename);
$worksheet = $spreadsheet->getActiveSheet();

echo '<table>';

foreach ($worksheet->getRowIterator() as $row) {
    echo '<tr>';
    $cellIterator = $row->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(FALSE); 
    foreach ($cellIterator as $cell) {
        echo '<td>' . $cell->getValue() . '</td>';
    }
    echo '</tr>';
}

echo '</table>';

// Función para determinar el periodoInicial basado en el periodoSeleccionado
function determinaPeriodoInicial($periodoSeleccionado) {
    // Aquí va tu lógica para determinar el periodoInicial
    // Por ahora, solo devolvemos el mismo periodoSeleccionado
    return $periodoSeleccionado;
}
?>