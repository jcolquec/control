<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombres = $_POST['nombres'];
    $uris = $_POST['uris'];

    // Leer archivos existentes
    $archivos = json_decode(file_get_contents('archivos.json'), true);

    // Agregar nuevos archivos
    for ($i = 0; $i < count($nombres); $i++) {
        $archivos[] = ['nombre' => htmlspecialchars($nombres[$i]), 'uri' => htmlspecialchars($uris[$i])];
    }

    // Guardar de nuevo en el archivo JSON
    file_put_contents('archivos.json', json_encode($archivos));

    // Redirigir de vuelta al formulario principal
    header('Location: form_control.php');
    exit;
}
?>