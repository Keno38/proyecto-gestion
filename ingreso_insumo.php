<?php
session_start();
include_once 'db.php';
$host = "localhost";
$usuario = "root";
$clave = "";
$base_de_datos = "gestion";

$conexion = mysqli_connect($host, $usuario, $clave, $base_de_datos);




?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/style.csss">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>Solicitud</title>
</head>

<body>

    <div>
        <div id="mensaje-container">
            <?php echo  $mensaje; ?>

        </div>
        <form action="solicitud.php" method="post">
            <h2>ingreso Insumos </h2>

            <label for="nombre">Insumo:</label>
            <input type="text" name="nombre" value="" required readonly>

            <label for="departamento">Especificaciones :</label>
            <input type="text" name="departamento" value="" required readonly>

            <label for="insumo">Proveedor </label>
            <input type="nam" name="departamento" value="" required readonly>


            <label for="cantidad">Cantidad:</label>
            <input type="number" placeholder="Cantidad" name="cantidad" required>

            <hr>
            <input type="submit" value="Enviar Solicitud">
            </hr>
        </form>
    </div>


</body>

</html>