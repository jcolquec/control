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

    $columnData[] = $value;

}

// Ordenar los datos de la columna
sort($columnData);

// Obtener el dato desde la URL
$data = isset($_GET['data']) ? htmlspecialchars($_GET['data']) : '';

// Verificar si el dato está presente
if (!$data) {
    echo "No se ha proporcionado ningún dato.";
    exit;
}

// Buscar el dato en el array de datos de la columna y guardarlo en una variable
$foundData = null;
foreach ($columnData as $item) {
    if (substr($item, 0, 7) === $data) {
        $foundData = $item;
        break;
    }
}
// Verificar si el dato fue encontrado
if ($foundData === null) {
    echo "El dato proporcionado no se encuentra en la columna.";
    exit;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle del Dato</title>
    <script>
        function agregarCampo() {
            const contenedor = document.getElementById('contenedor-enlaces');
            const nuevoCampo = document.createElement('div');
            nuevoCampo.innerHTML = `
                <input type="text" name="nombres[]" placeholder="Nombre del Archivo" required>
                <input type="url" name="uris[]" placeholder="Enlace URI" required>
                <button type="button" onclick="eliminarCampo(this)">Eliminar</button>
                <br><br>
            `;
            contenedor.appendChild(nuevoCampo);
        }

        function eliminarCampo(boton) {
            boton.parentElement.remove();
        }
    </script>
</head>
<body>
    <h1>Detalle del Dato: <?php echo substr($foundData, 0, 7); ?></h1>
    <p>El dato completo es: <?php echo $foundData; ?></p>
    <form action="procesar_form.php" method="post">
        <label for="dato">Dato:</label>
        <input type="text" id="dato" name="dato" value="<?php echo $data; ?>" readonly><br><br>
        
        <p>Pregunta: ¿Está realizado el control?</p>

        <label for="respuesta">Respuesta:</label>
        <input type="text" id="respuesta" name="respuesta" placeholder="Si o No"><br><br>

        <label for="observaciones">Observaciones:</label>
        <input type="text" id="observaciones" name="observaciones"><br><br>
        
        <input type="submit" value="Enviar">
    </form>

    <h2>Archivos Relacionados</h2>
    <table>
        <tr>
            <th>Descripción/Nombre del Archivo</th>
            <th>Enlace URI</th>
        </tr>
        <?php
        // Leer archivos relacionados desde un archivo JSON
        $archivos = json_decode(file_get_contents('archivos.json'), true);

        foreach ($archivos as $archivo) {
            echo "<tr>";
            echo "<td>{$archivo['nombre']}</td>";
            echo "<td><a href=\"{$archivo['uri']}\" target=\"_blank\">{$archivo['uri']}</a></td>";
            echo "</tr>";
        }
        ?>
    </table>

    <h2>Agregar Nuevo Archivo</h2>
    <form action="agregar_archivo.php" method="post">
        <div id="contenedor-enlaces"></div>
        <button type="button" onclick="agregarCampo()">Agregar Enlace</button><br><br>
        <input type="submit" value="Agregar Archivo">
    </form>
</body>
</html>