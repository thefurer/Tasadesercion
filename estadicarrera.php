<?php
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

// Obtener los datos de la tabla 'faltantes'
$result = $conn->query("SELECT carrera, COUNT(*) AS total FROM faltantes GROUP BY carrera");

// Crear un array para almacenar los datos
$data = array();
$data[] = array('Carrera', 'Total');

// Añadir los datos a la matriz
while ($row = $result->fetch_assoc()) {
    $data[] = array($row['carrera'], (int)$row['total']);
}

// Convertir la matriz a JSON
$jsonData = json_encode($data);

$periodoInicial = str_replace("-", "_", $_POST["periodoInicial"]);

// Obtener todas las carreras
$result = $conn->query("SELECT DISTINCT carrera FROM $periodoInicial");
$carreras = $result->fetch_all(MYSQLI_ASSOC);

$tasaDesercionTotal = 0;
$numCarreras = count($carreras);

// Crear un array para almacenar los datos
$dataDesercion = array();
$dataDesercion[] = array('Carrera', 'Tasa de Deserción');

foreach ($carreras as $carrera) {
    $periodoSeleccionado = str_replace("-", "_", $_POST["periodoSeleccionado"]);

    // Primero, establecemos todos los estados a 0
    $conn->query("UPDATE $periodoInicial SET estado = 0 WHERE carrera = '{$carrera['carrera']}'");
    $conn->query("UPDATE $periodoSeleccionado SET estado = 0 WHERE carrera = '{$carrera['carrera']}'");

    // Luego, encontramos los estudiantes duplicados y los marcamos con estado = 1
    $conn->query("UPDATE $periodoInicial SET estado = 1 WHERE identificacion IN (SELECT identificacion FROM (SELECT identificacion FROM $periodoInicial WHERE carrera = '{$carrera['carrera']}' GROUP BY identificacion HAVING COUNT(*) > 1) as subquery)");
    $conn->query("UPDATE $periodoSeleccionado SET estado = 1 WHERE identificacion IN (SELECT identificacion FROM (SELECT identificacion FROM $periodoSeleccionado WHERE carrera = '{$carrera['carrera']}' GROUP BY identificacion HAVING COUNT(*) > 1) as subquery)");

    $result = $conn->query("SELECT COUNT(*) AS total FROM $periodoInicial WHERE carrera = '{$carrera['carrera']}'");
    $row = $result->fetch_assoc();
    $totalInicial = $row['total'];

    $result = $conn->query("SELECT COUNT(*) AS total FROM $periodoSeleccionado WHERE carrera = '{$carrera['carrera']}'");
    $row = $result->fetch_assoc();
    $totalSeleccionado = $row['total'];

    $desertores = $totalInicial - $totalSeleccionado;

    if ($totalInicial != 0) {
        $tasaDesercion = $desertores / $totalInicial;
        $tasaDesercion = round($tasaDesercion, 2);
    } else {
        $tasaDesercion = 0;
    }

    // Añadir los datos a la matriz
    $dataDesercion[] = array($carrera['carrera'], $tasaDesercion * 100);

    $tasaDesercionTotal += $tasaDesercion;
}

$tasaDesercionInstitucional = $tasaDesercionTotal / $numCarreras;
$tasaDesercionInstitucionalPorcentaje = $tasaDesercionInstitucional * 100;

// Convertir la matriz a JSON
$jsonDataDesercion = json_encode($dataDesercion);

$conn->close();
?>

<html>
  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);
      google.charts.setOnLoadCallback(drawChartDesercion);

      function drawChart() {
        var data = google.visualization.arrayToDataTable(<?php echo $jsonData; ?>);

        var options = {
          title: 'Estudiantes faltantes por carrera'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
      }

      function drawChartDesercion() {
        var data = google.visualization.arrayToDataTable(<?php echo $jsonDataDesercion; ?>);

        var options = {
          title: 'Tasa de deserción institucional por carrera',
          hAxis: {
            title: 'Carrera',
            slantedText: true,
            slantedTextAngle: 45
          },
          vAxis: {
            title: 'Tasa de Deserción (%)'
          },
          legend: { position: 'none' },
          bar: { groupWidth: '75%' }
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('chart_desercion'));

        chart.draw(data, options);
      }
    </script>
  </head>
  <body>
    <div id="piechart" style="width: 900px; height: 500px;"></div>
    <div id="chart_desercion" style="width: 900px; height: 500px;"></div>
  </body>
</html>