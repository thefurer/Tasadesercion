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

$sql = "SELECT cod_carrera, periodoSeleccionado FROM periodos ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $cod_carrera = $row['cod_carrera'];
    $periodoSeleccionado = $row['periodoSeleccionado'];
} else {
    echo "No se encontraron registros en la tabla periodos.";
}

$conn->close();

$filename = $cod_carrera . '_' . $periodoSeleccionado . '.xlsx'; 

echo "cod_carrera: $cod_carrera<br>";
echo "periodoSeleccionado: $periodoSeleccionado<br>";
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
?>