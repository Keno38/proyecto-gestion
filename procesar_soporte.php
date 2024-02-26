<!-- procesar_detalle_soporte.php -->

<?php
session_start();
include_once 'db.php';

$host = "localhost";
$usuario = "root";
$clave = "";
$base_de_datos = "gestion";

$conexion = mysqli_connect($host, $usuario, $clave, $base_de_datos);

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    // Si no ha iniciado sesión, redirige a la página de inicio de sesión o a donde sea necesario
    header('Location: login.php');
    exit();
}

// Verificar si se recibieron los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_soporte'], $_POST['descripcion'])) {
    $id_soporte = $_POST['id_soporte'];
    $descripcion = $_POST['descripcion'];

    // Actualizar la descripción del soporte en la base de datos
    $actualizarSoporte = "UPDATE soporte SET descripcion = '$descripcion' WHERE id_soporte = $id_soporte";
    $resultadoActualizacion = mysqli_query($conexion, $actualizarSoporte);

    if ($resultadoActualizacion) {
        echo "La descripción del soporte con ID $id_soporte se ha actualizado correctamente.";
    } else {
        echo "Error al actualizar la descripción del soporte con ID $id_soporte: " . mysqli_error($conexion);
    }
}

mysqli_close($conexion);
?>