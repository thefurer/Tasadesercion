<?php
$servidor = "localhost";
$base_datos = "tasa_insercion";
$usuario = "root";
$clave = "";

$conn = new mysqli($servidor, $usuario, $clave, $base_datos);

if ($conn->connect_error) {
    die("Conexion Fallida:" . $conn->connect_error);
}

require 'vendor/autoload.php'; // Asegúrate de incluir el archivo de autoload de PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\IOFactory;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["validar_evaluacion"])) {
    // Verificar si se cargó un archivo
    if (isset($_FILES["archivo_excel_evaluacion"]) && $_FILES["archivo_excel_evaluacion"]["error"] == UPLOAD_ERR_OK) {
        // Obtener los campos seleccionados
        $fields = isset($_POST["fields"]) ? $_POST["fields"] : [];

        // Llamar a la función importar_excel
        importar_excel($_FILES["archivo_excel_evaluacion"]["tmp_name"], $fields, $conn);

        // Cerrar la conexión a la base de datos (movido fuera del if para asegurar que siempre se cierre)
        $conn->close();
    } else {
        echo "Error al cargar el archivo Excel.";
    }
}

function importar_excel($archivo_temporal, $fields, $conn)
{
    try {
        // Cargar el archivo Excel
        $objPHPExcel = IOFactory::load($archivo_temporal);
    } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
        echo "Error al cargar el archivo Excel: " . $e->getMessage();
        return;
    }

    // Obtener la hoja activa del archivo Excel
    $hoja = $objPHPExcel->getActiveSheet();

    // Verificar si hay datos en la hoja
    if ($hoja->getHighestRow() === 1) {
        echo "El archivo Excel está vacío.";
        return;
    }

    // Mostrar campos seleccionados en la página
    echo "<h2>Campos Seleccionados:</h2>";
    echo "<ul>";
    foreach ($fields as $field) {
        echo "<li>$field</li>";
    }
    echo "</ul>";

    // Mostrar datos del Excel en la página
    echo "<h2>Datos del Excel:</h2>";
    echo "<table border='1'>";
    echo "<tr>";
    foreach ($fields as $field) {
        echo "<th>$field</th>";
    }
    echo "</tr>";

    // Iterar sobre las filas del archivo Excel (empezando desde la segunda fila)
    foreach ($hoja->getRowIterator(2) as $row) {
        $datos = [];
        $cellIterator = $row->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(false);

        // Iterar sobre las celdas de la fila y guardar los datos de las columnas seleccionadas
        foreach ($cellIterator as $cell) {
            $columnIndex = $cell->getColumn();
            $columnHeader = $hoja->getCell($columnIndex . '1')->getValue();

            // Verificar si la columna está seleccionada
            if (in_array($columnHeader, $fields)) {
                $datos[$columnHeader] = $cell->getValue();
            }
        }

        // Insertar datos en las tablas correspondientes
        if (!empty($datos)) {
            try {
                $conn->begin_transaction();

                // Insertar datos en la tabla Estudiante
                $stmt = $conn->prepare("INSERT INTO Estudiante (CODIGO_IES, CIUDAD_CARRERA, TIPO_IDENTIFICACION, IDENTIFICACION, PRIMER_APELLIDO, SEGUNDO_APELLIDO, NOMBRES, SEXO, FECHA_NACIMIENTO, EMAIL_PERSONAL, EMAIL_INSTITUCIONAL, FECHA_INICIO_PRIMER_NIVEL, CANTIDAD_MIEMBROS_HOGAR, TIPO_COLEGIO, POLITICA_CUOTA) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssssssssssiss", $datos['CODIGO_IES'], $datos['CIUDAD_CARRERA'], $datos['TIPO_IDENTIFICACION'], $datos['IDENTIFICACION'], $datos['PRIMER_APELLIDO'], $datos['SEGUNDO_APELLIDO'], $datos['NOMBRES'], $datos['SEXO'], $datos['FECHA_NACIMIENTO'], $datos['EMAIL_PERSONAL'], $datos['EMAIL_INSTITUCIONAL'], $datos['FECHA_INICIO_PRIMER_NIVEL'], $datos['CANTIDAD_MIEMBROS_HOGAR'], $datos['TIPO_COLEGIO'], $datos['POLITICA_CUOTA']);
                $stmt->execute();
                $id_estudiante = $stmt->insert_id;

                // Insertar datos en la tabla Direccion
                $stmt2 = $conn->prepare("INSERT INTO Direccion (id_estudiante, PAIS_RESIDENCIA, PROVINCIA_RESIDENCIA, CANTON_RESIDENCIA, DIRECCION) VALUES (?, ?, ?, ?, ?)");
                $stmt2->bind_param("issss", $id_estudiante, $datos['PAIS_RESIDENCIA'], $datos['PROVINCIA_RESIDENCIA'], $datos['CANTON_RESIDENCIA'], $datos['DIRECCION']);
                $stmt2->execute();

                // Insertar datos en la tabla Discapacidad
                $stmt3 = $conn->prepare("INSERT INTO Discapacidad (id_estudiante, DISCAPACIDAD, PORCENTAJE_DISCAPACIDAD, NUMERO_CONADIS) VALUES (?, ?, ?, ?)");
                $stmt3->bind_param("isss", $id_estudiante, $datos['DISCAPACIDAD'], $datos['PORCENTAJE_DISCAPACIDAD'], $datos['NUMERO_CONADIS']);
                $stmt3->execute();

                // Insertar datos en la tabla Etnia
                $stmt4 = $conn->prepare("INSERT INTO Etnia (id_estudiante, ETNIA) VALUES (?, ?)");
                $stmt4->bind_param("is", $id_estudiante, $datos['ETNIA']);
                $stmt4->execute();

                // Insertar datos en la tabla Nacionalidad
                $stmt5 = $conn->prepare("INSERT INTO Nacionalidad (id_estudiante, NACIONALIDAD) VALUES (?, ?)");
                $stmt5->bind_param("is", $id_estudiante, $datos['NACIONALIDAD']);
                $stmt5->execute();

                $conn->commit();
            } catch (Exception $e) {
                $conn->rollback();
                echo "Error al insertar datos en la base de datos: " . $e->getMessage();
                return;
            }
        }

        // Mostrar los datos de la fila en la tabla de la página web
        echo "<tr>";
        foreach ($fields as $field) {
            echo "<td>{$datos[$field]}</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
}
?>

<html>
<head>
    <title>Importar Datos de Evaluación</title>
</head>
<body>
<form method="post" enctype="multipart/form-data">
    <label for="archivo_excel_evaluacion">Selecciona un archivo Excel de Evaluación:</label><br>
    <input type="file" name="archivo_excel_evaluacion" id="archivo_excel_evaluacion"><br>
    <label>Selecciona los campos:</label> <br>

    <!-- Aquí se muestran los checkboxes para seleccionar los campos -->
    <input type="checkbox" id="CODIGO_IES" name="fields[]" value="CODIGO_IES">
    <label for="CODIGO_IES">CODIGO_IES</label><br>
    <input type="checkbox" id="CIUDAD_CARRERA" name="fields[]" value="CIUDAD_CARRERA">
    <label for="CIUDAD_CARRERA">CIUDAD_CARRERA</label><br>
    <input type="checkbox" id="TIPO_IDENTIFICACION" name="fields[]" value="TIPO_IDENTIFICACION">
    <label for="TIPO_IDENTIFICACION">TIPO_IDENTIFICACION</label><br>
    <input type="checkbox" id="IDENTIFICACION" name="fields[]" value="IDENTIFICACION">
    <label for="IDENTIFICACION">IDENTIFICACION</label><br>
    <input type="checkbox" id="PRIMER_APELLIDO" name="fields[]" value="PRIMER_APELLIDO">
    <label for="PRIMER_APELLIDO">PRIMER APELLIDO:</label><br>
    <input type="checkbox" id="SEGUNDO_APELLIDO" name="fields[]" value="SEGUNDO_APELLIDO">
    <label for="SEGUNDO_APELLIDO">SEGUNDO APELLIDO:</label><br>
    <input type="checkbox" id="NOMBRES" name="fields[]" value="NOMBRES">
    <label for="NOMBRES">NOMBRES:</label><br>
    <input type="checkbox" id="SEXO" name="fields[]" value="SEXO">
    <label for="SEXO">SEXO:</label><br>
    <input type="checkbox" id="FECHA_NACIMIENTO" name="fields[]" value="FECHA_NACIMIENTO">
    <label for="FECHA_NACIMIENTO">FECHA NAIMIENTO:</label><br>
    <input type="checkbox" id="PAIS_ORIGEN" name="fields[]" value="PAIS_ORIGEN">
    <label for="PAIS_ORIGEN">PAIS ORIGEN:</label><br>
    <input type="checkbox" id="DISCAPACIDAD" name="fields[]" value="DISCAPACIDAD">
    <label for="DISCAPACIDAD">DISCAPACIDAD:</label><br>
    <input type="checkbox" id="PORCENTAJE_DISCAPACIDAD" name="fields[]" value="PORCENTAJE_DISCAPACIDAD">
    <label for="PORCENTAJE_DISCAPACIDAD">PORCENTAJE DISCAPACIDAD:</label><br>
    <input type="checkbox" id="NUMERO_CONADIS" name="fields[]" value="NUMERO_CONADIS">
    <label for="NUMERO_CONADIS">NUMERO CONADIS:</label><br>
    <input type="checkbox" id="ETNIA" name="fields[]" value="ETNIA">
    <label for="ETNIA">ETNIA:</label><br>
    <input type="checkbox" id="NACIONALIDAD" name="fields[]" value="NACIONALIDAD">
    <label for="NACIONALIDAD">NACIONALIDAD:</label><br>
    <input type="checkbox" id="DIRECCION" name="fields[]" value="DIRECCION">
    <label for="DIRECCION">DIRECCION:</label><br>
    <input type="checkbox" id="EMAIL_PERSONAL" name="fields[]" value="EMAIL_PERSONAL">
    <label for="EMAIL_PERSONAL">EMAIL PERSONAL:</label><br>
    <input type="checkbox" id="EMAIL_INSTITUCIONAL" name="fields[]" value="EMAIL_INSTITUCIONAL">
    <label for="EMAIL_INSTITUCIONAL">EMAIL INSTITUCIONAL:</label><br>
    <input type="checkbox" id="FECHA_INICIO_PRIMER_NIVEL" name="fields[]" value="FECHA_INICIO_PRIMER_NIVEL">
    <label for="FECHA_INICIO_PRIMER_NIVEL">FECHA INICIO PRIMER NIVEL:</label><br>
    <input type="checkbox" id="PAIS_RESIDENCIA" name="fields[]" value="PAIS_RESIDENCIA">
    <label for="PAIS_RESIDENCIA">PAIS RESIDENCIA:</label><br>
    <input type="checkbox" id="PROVINCIA_RESIDENCIA" name="fields[]" value="PROVINCIA_RESIDENCIA">
    <label for="PROVINCIA_RESIDENCIA">PROVINCIA RESIDENCIA:</label><br>
    <input type="checkbox" id="CANTON_RESIDENCIA" name="fields[]" value="CANTON_RESIDENCIA">
    <label for="CANTON_RESIDENCIA">CANTON RESIDENCIA:</label><br>
    <input type="checkbox" id="NIVEL_FORMACION_PADRE" name="fields[]" value="NIVEL_FORMACION_PADRE">
    <label for="NIVEL_FORMACION_PADRE">NIVEL FORMACION PADRE:</label><br>
    <input type="checkbox" id="NIVEL_FORMACION_MADRE" name="fields[]" value="NIVEL_FORMACION_MADRE">
    <label for="NIVEL_FORMACION_MADRE">NIVEL FORMACION MADRE:</label><br>
    <input type="checkbox" id="CANTIDAD_MIEMBROS_HOGAR" name="fields[]" value="CANTIDAD_MIEMBROS_HOGAR">
    <label for="CANTIDAD_MIEMBROS_HOGAR">CANTIDAD MIEMBROS HOGAR:</label><br>
    <input type="checkbox" id="POLITICA_CUOTA" name="fields[]" value="POLITICA_CUOTA">
    <label for="POLITICA_CUOTA">POLITICA CUOTA</label><br>
    <input type="submit" name="validar_evaluacion" value="Importar Datos">
</form>
</body>
</html>
