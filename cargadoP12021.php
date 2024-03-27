
<?php
if(isset($_POST["submit"])) {
    // Establecer la conexión a la base de datos
    $servidor = "localhost";
    $base_datos = "tasa_insercion";
    $usuario = "root";
    $clave = "";
    $conn = new mysqli($servidor, $usuario, $clave , $base_datos);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Procesar el archivo Excel si se ha subido correctamente
    if(isset($_FILES["archivo"]) && $_FILES["archivo"]["error"] == 0){
        $nombre_archivo = $_FILES["archivo"]["name"];
        $ruta_archivo = $_FILES["archivo"]["tmp_name"];

        // Cargar la librería PhpSpreadsheet
        require 'vendor/autoload.php';

        // Crear un nuevo objeto PhpSpreadsheet para leer el archivo Excel
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($ruta_archivo);

        // Obtener la hoja activa del archivo Excel
        $hoja = $spreadsheet->getActiveSheet();

        // Obtener el número de filas en la hoja
        $num_filas = $hoja->getHighestRow();

        // Iterar sobre las filas del archivo Excel, comenzando desde la novena fila que contiene los datos
        for ($fila = 9; $fila <= $num_filas; $fila++) {
            // Obtener el valor de la celda de la columna 'semestre' para esta fila
            $semestre = $hoja->getCell('F' . $fila)->getValue();

            // Verificar si el valor del semestre está en la lista permitida
            $semestres_permitidos = ["PRIMERO"];
            if (in_array($semestre, $semestres_permitidos)) {
                // Obtener los valores de las celdas de la fila actual
                $valores = $hoja->rangeToArray('A' . $fila . ':H' . $fila, NULL, TRUE, FALSE)[0];

                // Insertar los valores en la base de datos
                $stmt = $conn->prepare("INSERT INTO P1_2021 (periodo_academico, identificacion, nombres_apellidos, carrera, semestre, paralelo) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssss", $valores[1], $valores[2], $valores[3], $valores[4], $valores[5], $valores[6]);
                $stmt->execute();
                $stmt->close();
            }
        }

        // Cerrar la conexión a la base de datos
        $conn->close();

        echo "Los datos se han insertado correctamente.";
    } else {
        echo "Error al subir el archivo.";
    }
}
?>
