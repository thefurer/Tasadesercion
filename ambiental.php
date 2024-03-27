<?php 
require 'vendor/autoload.php';
require 'confi/confi.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$nombreArchivo ='AMBIENTAL.xlsx';
$documento = IOFactory::load($nombreArchivo);
$totalHojas = $documento->getSheetCount();

$hojaActual = $documento ->getSheet(0);
$numeroFilas = $hojaActual->getHighestDataRow();
$letra = $hojaActual->getHighestColumn();

for($indiceFila = 1; $indiceFila <=$numeroFilas;$indiceFila++){
    $valorA = $hojaActual->getCell('A' . $indiceFila)->getValue();
    $valorB = $hojaActual->getCell('B' . $indiceFila)->getValue();
    $valorC = $hojaActual->getCell('C' . $indiceFila)->getValue();
    $valorD = $hojaActual->getCell('D' . $indiceFila)->getValue();
    $valorE = $hojaActual->getCell('E' . $indiceFila)->getValue();
    $valorF = $hojaActual->getCell('F' . $indiceFila)->getValue();
    $valorG = $hojaActual->getCell('G' . $indiceFila)->getValue();
    $valorH = $hojaActual->getCell('H' . $indiceFila)->getValue();
    $valorI = $hojaActual->getCell('I' . $indiceFila)->getValue();
    $valorJ = $hojaActual->getCell('J' . $indiceFila)->getValue();
    $valorK = $hojaActual->getCell('K' . $indiceFila)->getValue();
    $valorL = $hojaActual->getCell('L' . $indiceFila)->getValue();
    $valorM = $hojaActual->getCell('M' . $indiceFila)->getValue();
    $valorN = $hojaActual->getCell('N' . $indiceFila)->getValue();
    $valorO = $hojaActual->getCell('O' . $indiceFila)->getValue();
    $valorP = $hojaActual->getCell('P' . $indiceFila)->getValue();
    $valorQ = $hojaActual->getCell('Q' . $indiceFila)->getValue();
    $valorR = $hojaActual->getCell('R' . $indiceFila)->getValue();
    $valorS = $hojaActual->getCell('S' . $indiceFila)->getValue();
    $valorT = $hojaActual->getCell('T' . $indiceFila)->getValue();
    $valorU = $hojaActual->getCell('U' . $indiceFila)->getValue();
    $valorV = $hojaActual->getCell('V' . $indiceFila)->getValue();
    $valorW = $hojaActual->getCell('W' . $indiceFila)->getValue();
    $valorX = $hojaActual->getCell('X' . $indiceFila)->getValue();
  
    $sql= "INSERT INTO ambientalp12021(
        CODIGO_IES, CODIGO_CARRERA, CIUDAD_CARRERA, TIPO_IDENTIFICACION, IDENTIFICACION, TOTAL_CREDITOS_APROBADOS, 
        CREDITOS_APROBADOS, TIPO_MATRICULA, PARALELO, NIVEL_ACADEMICO, DURACION_PERIODO_ACADEMICO, 
        NUM_MATERIAS_SEGUNDA_MATRICULA, NUM_MATERIAS_TERCERA_MATRICULA, PERDIDA_GRATUIDAD, INGRESO_TOTAL_HOGAR, 
        ORIGEN_RECURSOS_ESTUDIOS, TERMINO_PERIODO, TOTAL_HORAS_APROBADAS, HORAS_APROBADAS_PERIODO, 
        MONTO_AYUDA_ECONOMICA, MONTO_CREDITO_EDUCATIVO, ESTADO
    ) VALUES ('$valorA','$valorB','$valorC','$valorD','$valorE','$valorF','$valorG','$valorH','$valorI','$valorJ','$valorK','$valorL','$valorM','$valorN','$valorO','$valorP','$valorQ','$valorR','$valorS','$valorT','$valorU','$valorV')";
    $conn->query($sql);
}
echo'Carga completa'
?>