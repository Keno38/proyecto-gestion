<?php
session_start();
include_once 'db.php';

$host = "localhost";
$usuario = "root";
$clave = "";
$base_de_datos = "gestion";

$conexion = mysqli_connect($host, $usuario, $clave, $base_de_datos);

$mensaje = ""; // Inicializar la variable $mensaje

// Realizar validación del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar los campos del formulario
    if (empty($_POST['nombre']) || empty($_POST['especificaciones']) || empty($_POST['cantidad'])) {
        $mensaje = "Por favor complete todos los campos.";
    } else {
        // Obtener los datos del formulario
        $nombre = $_POST['nombre'];
        $especificaciones = $_POST['especificaciones'];
        $cantidad = $_POST['cantidad'];

        // Ingresar un nuevo insumo
        $consultaIngreso = "INSERT INTO insumo (nombre, especificaciones, cantidad) VALUES ('$nombre', '$especificaciones', $cantidad)";
        if (mysqli_query($conexion, $consultaIngreso)) {
            $mensaje = "Insumo ingresado con éxito.";
            // Redireccionar a admin.php después de mostrar el mensaje
            header('Location: admin.php');
            exit(); // Asegúrate de terminar el script después de redirigir
        } else {
            $mensaje = "Error al ingresar el insumo: " . mysqli_error($conexion);
        }
    }
}

mysqli_close($conexion);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/style.csss">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>Gestión de Insumos</title>
</head>

<body>
    <div>
        <div id="mensaje-container">
            <?php echo $mensaje; ?>
        </div>
        <form action="ingreso_insumo.php" method="post">
            <h2>Ingreso de Insumos</h2>

            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" value="" required>

            <label for="especificaciones">Especificaciones:</label>
            <input type="text" name="especificaciones" value="" required>

            <label for="cantidad">Cantidad:</label>
            <input type="number" name="cantidad" value="" required>

            <hr>
            <input type="submit" value="Ingresar">
        </form>
    </div>
</body>

</html>