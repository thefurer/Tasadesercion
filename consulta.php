<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php
session_start(); // Asegúrate de que esta línea esté al principio de tu archivo

echo "<pre>";
print_r($_SESSION['datos']);
echo "</pre>";

if (isset($_SESSION['datos'])) {
    echo "<table>";
    foreach ($_SESSION['datos'] as $fila) {
        echo "<tr>";
        echo "<td>" . $fila['CARRERA'] . "</td>";
        echo "<td>" . $fila['NIVEL'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}
?>
</body>
</html>