<?php
require 'vendor/autoload.php'; // Carga el autoloader de Composer
use PhpOffice\PhpSpreadsheet\IOFactory;

// Función para validar el archivo Excel
function validar_excel($archivo_temporal)
{
    // Cargar el archivo Excel
    $objPHPExcel = IOFactory::load($archivo_temporal);
    $hoja = $objPHPExcel->getActiveSheet();

    // Verificar si la columna 'CODIGO_CARRERA' está presente
    $columnas = $hoja->toArray()[0]; // Obtener la primera fila como arreglo
    if (in_array('CODIGO_CARRERA', $columnas)) {
        echo "El archivo Excel tiene la columna 'CODIGO_CARRERA'.";
    } else {
        echo "El archivo Excel no tiene la columna 'CODIGO_CARRERA'.";
    }
}

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["archivo_excel"])) {
    // Llamar a la función validar_excel y pasar el archivo
    validar_excel($_FILES["archivo_excel"]["tmp_name"]);
}
?>