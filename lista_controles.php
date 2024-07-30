<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Html;

// Cargar el archivo Excel
//$spreadsheet = IOFactory::load('Copia JorgeRiesgosProceso-de-Compra Rec 12JUL.xlsx');

// Cargar el archivo Excel
$spreadsheet = IOFactory::load('Libro1.xlsx');

// Acceder a una hoja específica por nombre
$sheet = $spreadsheet->getSheetByName('Tabla Controles17JUL');

// Obtener el índice de la columna deseada (por ejemplo, columna 'A')
$columnIndex = 'C';

// Inicializar un array para almacenar los datos de la columna
$columnData = [];

// Iterar sobre las filas de la columna deseada
foreach ($sheet->getRowIterator() as $row) {
    $cell = $sheet->getCell($columnIndex . $row->getRowIndex());
    $value = $cell->getValue();

    // Verificar si el valor es un objeto RichText
    if ($value instanceof \PhpOffice\PhpSpreadsheet\RichText\RichText) {
        $value = $value->getPlainText();
    }

    // Obtener los primeros 7 caracteres del valor
    $shortValue = substr($value, 0, 7);

    $columnData[] = $shortValue;
}

// Ordenar los datos de la columna
sort($columnData);

// Generar la tabla HTML
/*
echo "<table border='1'>";
echo "<tr><th>Datos de la Columna $columnIndex</th></tr>";
foreach ($columnData as $data) {
    echo "<tr><td>" . htmlspecialchars($data) . "</td></tr>";
}
echo "</table>";
*/

// Parámetros de paginación
$itemsPerPage = 10;
$totalItems = count($columnData);
$totalPages = ceil($totalItems / $itemsPerPage);

// Obtener el número de página actual desde la URL, por defecto es 1
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max(1, min($totalPages, $page));

// Calcular el índice de inicio y fin para los datos de la página actual
$startIndex = ($page - 1) * $itemsPerPage;
$endIndex = min($startIndex + $itemsPerPage, $totalItems);

// Generar la tabla HTML
echo "<table border='1'>";
echo "<tr><th>Datos de la Columna $columnIndex</th></tr>";
for ($i = $startIndex; $i < $endIndex; $i++) {
    $data = htmlspecialchars($columnData[$i]);
    echo "<tr><td><a href=\"form_control.php?data=$data\">$data</a></td></tr>";
}
echo "</table>";

// Generar los enlaces de paginación
echo "<div>";
for ($i = 1; $i <= $totalPages; $i++) {
    if ($i == $page) {
        echo "<strong>$i</strong> ";
    } else {
        echo "<a href=\"?page=$i\">$i</a> ";
    }
}
echo "</div>";