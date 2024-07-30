<?php 
include 'phpqrcode/qrlib.php';


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Docente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

</head>
<body>
<form action="index.php" method="POST" class="form group">
        <h2>Formulario de configuración inicial</h2>
            
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label"> Nombre </label>
                <input type="text" required name="nombre" class="form-control" placeholder="Ingrese su nombre y apellido">
            </div>

            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label"> Conceptos </label>
                <input type="text" required name="conceptos" class="form-control" placeholder="Ingrese conceptos a diagnosticar (ejemplo: suma,resta,...división.)">
            </div>

            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label"> Ocupación </label>
                <input type="text" required name="ocupacion" class="form-control" placeholder="Ingrese ocupación (ejemplo: profesor,tutor...ayudante.)">
            </div>

        <button type="submit" name="datos_form" class="btn btn-primary">Subir datos</button>
    </form>
    <?php 
    // Datos a codificar en el QR
    $data = 'https://localhost/parametros_formulario/form_estudiante.php?conceptos='.$_REQUEST['conceptos'];

    // Nombre del archivo donde se guardará el QR
    $filename = 'qrcode.png';

    // Generar el código QR
    QRcode::png($data, $filename);

    echo 'Código QR generado: <img src="'.$filename.'" />';
    
    ?>
</body>
</html>