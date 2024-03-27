<!DOCTYPE html>
<html>
<head>
    <title>Validar Archivo Excel</title>
</head>
<body>

<?php
require 'vendor/autoload.php'; 
use PhpOffice\PhpSpreadsheet\IOFactory;


function validar_excel($archivo_temporal, $consultar = false)
{
    $objPHPExcel = IOFactory::load($archivo_temporal);
    $hoja = $objPHPExcel->getActiveSheet();
    $columnas = $hoja->toArray()[0]; 
    $columnasRequeridas = ['CARRERA', 'NIVEL'];
    $columnasFaltantes = array_diff($columnasRequeridas, $columnas);

    if (!empty($columnasFaltantes)) {
        echo "El archivo Excel no tiene las columnas: " . implode(', ', $columnasFaltantes) . ".";
        return;
    }

    if ($consultar) {
       
        $datos = $hoja->toArray();
        
        echo "<h2>Contenido de las columnas:</h2>";
        echo "<table border='1'>";
        echo "<tr><th>CARRERA</th><th>NIVEL</th></tr>";
      
        for ($i = 1; $i < count($datos); $i++) {
            $fila = $datos[$i];
            echo "<tr>";
            echo "<td>" . (isset($fila[0]) ? $fila[0] : 'N/A') . "</td>";
            echo "<td>" . (isset($fila[1]) ? $fila[1] : 'N/A') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "El archivo Excel tiene las columnas 'CARRERA' y 'NIVEL'.";
    }
}



?>

<form method="post" enctype="multipart/form-data">
    <label for="archivo_excel_evaluacion">Selecciona un archivo Excel de Evaluación:</label><br>
    <input type="file" name="archivo_excel_evaluacion" id="archivo_excel_evaluacion"><br>
    <input type="submit" name="validar_evaluacion" value="Validar Evaluación"><br>
    <input type="submit" name="consultar_evaluacion" value="Consultar Evaluación"><br>
    <label for="archivo_excel_inicio">Selecciona un archivo Excel de Inicio:</label><br>
    <input type="file" name="archivo_excel_inicio" id="archivo_excel_inicio"><br>
    <input type="submit" name="validar_inicio" value="Validar Inicio">
</form>

<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["validar_evaluacion"]) && isset($_FILES["archivo_excel_evaluacion"]) && $_FILES["archivo_excel_evaluacion"]["error"] == 0) {
        
        validar_excel($_FILES["archivo_excel_evaluacion"]["tmp_name"]);
    } elseif (isset($_POST["consultar_evaluacion"]) && isset($_FILES["archivo_excel_evaluacion"]) && $_FILES["archivo_excel_evaluacion"]["error"] == 0) {
       
        validar_excel($_FILES["archivo_excel_evaluacion"]["tmp_name"], true);
    } elseif (isset($_POST["validar_inicio"]) && isset($_FILES["archivo_excel_inicio"]) && $_FILES["archivo_excel_inicio"]["error"] == 0) {
        
        validar_excel($_FILES["archivo_excel_inicio"]["tmp_name"]);
    }
}
?>

</body>
</html>
