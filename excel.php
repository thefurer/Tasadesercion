<?php
    // Conexión a la base de datos
    $servidor = "localhost";
    $base_datos = "tasa_insercion";
    $usuario = "root";
    $clave = "";

    $conn = new mysqli($servidor, $usuario, $clave , $base_datos);

    if ($conn->connect_error) {
        die("Conexion Fallida:" . $conn->connect_error);
    }

    $jsonData = '[]'; // Inicializa jsonData como un array vacío en formato JSON

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fields']) && !empty($_POST['fields'])) {
        // Verifica si 'fields' está definido y es un array. Si no, inicialízalo como un array vacío
        $fields = is_array($_POST['fields']) ? $_POST['fields'] : [];

        // Crea una consulta SQL con los campos seleccionados
        $query = "SELECT " . implode(", ", $fields) . " FROM ambientalp12021";

        $result = $conn->query($query);

        if (!$result) {
            die("Error en la consulta: " . $conn->connect_error);
        }

        // Almacena los datos en un array
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        // Codifica los datos en formato JSON
        $jsonData = json_encode($data);
    }
?>
<!-- HTML -->
<!DOCTYPE html>
<html>
<head>
    <title>Tabla de Datos</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
<form action="" method="post">
    <!-- Your checkboxes here -->
    <input type="checkbox" id="CODIGO_IES" name="fields[]" value="CODIGO_IES">
    <label for="CODIGO_IES">CODIGO_IES</label><br>
    <input type="checkbox" id="CODIGO_CARRERA" name="fields[]" value="CODIGO_CARRERA">
    <label for="CODIGO_CARRERA">CODIGO_CARRERA</label><br>
    <input type="checkbox" id="CIUDAD_CARRERA" name="fields[]" value="CIUDAD_CARRERA">
    <label for="CIUDAD_CARRERA">CIUDAD_CARRERA</label><br>
    <input type="checkbox" id="TIPO_IDENTIFICACION" name="fields[]" value="TIPO_IDENTIFICACION">
    <label for="TIPO_IDENTIFICACION">TIPO_IDENTIFICACION</label><br>
    <input type="checkbox" id="IDENTIFICACION" name="fields[]" value="IDENTIFICACION">
    <label for="IDENTIFICACION">IDENTIFICACION</label><br>
    <input type="checkbox" id="TOTAL_CREDITOS_APROBADOS" name="fields[]" value="TOTAL_CREDITOS_APROBADOS">
    <label for="TOTAL_CREDITOS_APROBADOS">TOTAL_CREDITOS_APROBADOS</label><br>
    <input type="checkbox" id="CREDITOS_APROBADOS" name="fields[]" value="CREDITOS_APROBADOS">
    <label for="CREDITOS_APROBADOS">CREDITOS_APROBADOS</label><br>
    <input type="checkbox" id="TIPO_MATRICULA" name="fields[]" value="TIPO_MATRICULA">
    <label for="TIPO_MATRICULA">TIPO_MATRICULA</label><br>
    <input type="checkbox" id="PARALELO" name="fields[]" value="PARALELO">
    <label for="PARALELO">PARALELO</label><br>
    <input type="checkbox" id="NIVEL_ACADEMICO" name="fields[]" value="NIVEL_ACADEMICO">
    <label for="NIVEL_ACADEMICO">NIVEL_ACADEMICO</label><br>
    <input type="checkbox" id="DURACION_PERIODO_ACADEMICO" name="fields[]" value="DURACION_PERIODO_ACADEMICO">
    <label for="DURACION_PERIODO_ACADEMICO">DURACION_PERIODO_ACADEMICO</label><br>
    <input type="checkbox" id="NUM_MATERIAS_SEGUNDA_MATRICULA" name="fields[]" value="NUM_MATERIAS_SEGUNDA_MATRICULA">
    <label for="NUM_MATERIAS_SEGUNDA_MATRICULA">NUM_MATERIAS_SEGUNDA_MATRICULA</label><br>
    <input type="checkbox" id="NUM_MATERIAS_TERCERA_MATRICULA" name="fields[]" value="NUM_MATERIAS_TERCERA_MATRICULA">
    <label for="NUM_MATERIAS_TERCERA_MATRICULA">NUM_MATERIAS_TERCERA_MATRICULA</label><br>
    <input type="checkbox" id="PERDIDA_GRATUIDAD" name="fields[]" value="PERDIDA_GRATUIDAD">
    <label for="PERDIDA_GRATUIDAD">PERDIDA_GRATUIDAD</label><br>
    <input type="checkbox" id="PERDIDA_GRATUIDAD" name="fields[]" value="PERDIDA_GRATUIDAD">
    <label for="PERDIDA_GRATUIDAD">PERDIDA_GRATUIDAD</label><br>
    <input type="checkbox" id="PLAN_CONTINGENCIA" name="fields[]" value="PLAN_CONTINGENCIA">
    <label for="INGRESO_TOTAL_HOGAR">INGRESO_TOTAL_HOGAR</label><br>
    <input type="checkbox" id="PLAN_CONTINGENCIA" name="fields[]" value="INGRESO_TOTAL_HOGAR">
    <label for="INGRESO_TOTAL_HOGAR">INGRESO_TOTAL_HOGAR</label><br>
    <input type="checkbox" id="ORIGEN_RECURSOS_ESTUDIOS" name="fields[]" value="ORIGEN_RECURSOS_ESTUDIOS">
    <label for="ORIGEN_RECURSOS_ESTUDIOS">ORIGEN_RECURSOS_ESTUDIOS</label><br>
    <input type="checkbox" id="TERMINO_PERIODO" name="fields[]" value="TERMINO_PERIODO">
    <label for="TERMINO_PERIODO">TERMINO_PERIODO</label><br>
    <input type="checkbox" id="TOTAL_HORAS_APROBADAS" name="fields[]" value="TOTAL_HORAS_APROBADAS">
    <label for="TOTAL_HORAS_APROBADAS">TOTAL_HORAS_APROBADAS</label><br>
    <input type="checkbox" id="HORAS_APROBADAS_PERIODO" name="fields[]" value="HORAS_APROBADAS_PERIODO">
    <label for="HORAS_APROBADAS_PERIODO">HORAS_APROBADAS_PERIODO</label><br>
    <input type="checkbox" id="MONTO_AYUDA_ECONOMICA" name="fields[]" value="MONTO_AYUDA_ECONOMICA">
    <label for="MONTO_AYUDA_ECONOMICA">MONTO_AYUDA_ECONOMICA</label><br>
    <input type="checkbox" id="MONTO_CREDITO_EDUCATIVO" name="fields[]" value="MONTO_CREDITO_EDUCATIVO">
    <label for="MONTO_CREDITO_EDUCATIVO">MONTO_CREDITO_EDUCATIVO</label><br>
    <input type="checkbox" id="ESTADO" name="fields[]" value="ESTADO">
    <label for="ESTADO">ESTADO</label><br>
    <input type="submit" name="P1-2021" value="P1-2021">
    <input type="submit" name="P2-2021" value="P1-2022">
    <button id="resetButton">Reset</button>
</form>
<table id="dataTable">
    
</table>
<script>
    var data = [];
    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        data = <?php echo $jsonData; ?>;
    <?php endif; ?>

    var table = document.getElementById('dataTable');
    if (data.length > 0 && data[0] !== null) { // Verifica si se seleccionaron campos
        table.style.visibility = 'visible'; // Muestra la tabla si hay datos
        table.innerHTML = "";
        for (var i = 0; i < data.length; i++) {
            var row = table.insertRow(-1);
            for (var key in data[i]) {
                var cell = row.insertCell(-1);
                cell.textContent = data[i][key];
            }
        }
    }
    document.getElementById('resetButton').addEventListener('click', function() {
        // Limpia la tabla
        table.innerHTML = "";
        table.style.visibility = 'none'; // Oculta la tabla cuando se presiona el botón de reset
    });
</script>
</body>
</html>