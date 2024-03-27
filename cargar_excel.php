<!DOCTYPE html>
<html>
<head>
    <title>Validar Archivo Excel</title>
</head>
<body>

<?php
require 'vendor/autoload.php'; // Carga el autoloader de Composer
use PhpOffice\PhpSpreadsheet\IOFactory;

// Función para validar el archivo Excel
function validar_excel($archivo_temporal, $cod_carrera)
{
    // Cargar el archivo Excel
    $objPHPExcel = IOFactory::load($archivo_temporal);
    $hoja = $objPHPExcel->getActiveSheet();

    // Obtener las cabeceras de las columnas
    $columnas = $hoja->toArray()[0]; // Obtener la primera fila como arreglo

    // Verificar si las columnas requeridas están presentes
    $columnas_requeridas = ['CODIGO_CARRERA', 'FECHA_INICIO_PRIMER_NIVEL', 'NIVEL'];
    $faltantes = array_diff($columnas_requeridas, $columnas);

    // Verificar si falta alguna columna requerida o hay columnas adicionales
    if (!empty($faltantes) || count($columnas) != count($columnas_requeridas)) {
        echo "El archivo Excel no cumple con los requisitos. Debe tener las siguientes columnas: " . implode(', ', $columnas_requeridas);
        return;
    }

    // Verificar si el código de carrera en el archivo Excel es el mismo que el código ingresado
    $codigos_carrera = array_column($hoja->toArray(), 'CODIGO_CARRERA');
    if (!in_array($cod_carrera, $codigos_carrera)) {
        echo "El código de carrera ingresado no coincide con ningún código de carrera en el archivo Excel.";
        return;
    }

    echo "El archivo Excel cumple con todos los requisitos y el código de carrera coincide.";
}

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["archivo_excel"]) && isset($_POST["cod_carrera"])) {
    // Obtener el código de carrera ingresado
    $cod_carrera = $_POST["cod_carrera"];
    // Llamar a la función validar_excel y pasar el archivo y el código de carrera
    validar_excel($_FILES["archivo_excel"]["tmp_name"], $cod_carrera);
}
?>

<!-- Formulario para cargar el archivo Excel -->
<form method="post" enctype="multipart/form-data">
    <label for="archivo_excel">Selecciona un archivo Excel:</label>
    <input type="file" name="archivo_excel" id="archivo_excel">
    <br>
    <label for="cod_carrera">Codigo de Carrera:</label><br>
    <input type="text" id="cod_carrera" name="cod_carrera"><br>
    <input type="submit" value="Validar">
</form>

</body>
</html>
