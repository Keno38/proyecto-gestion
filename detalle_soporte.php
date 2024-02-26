<!-- detalle_soporte.php -->

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

// Verificar si se recibieron los IDs de los soportes seleccionados
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['seleccion'])) {
    $seleccion = json_decode($_POST['seleccion'], true);

    // Consultar y mostrar los detalles de cada soporte
    foreach ($seleccion as $id_soporte) {
        $consultaSoporte = "SELECT * FROM soporte WHERE id_soporte = $id_soporte";
        $resultadoSoporte = mysqli_query($conexion, $consultaSoporte);

        if ($resultadoSoporte && mysqli_num_rows($resultadoSoporte) > 0) {
            $fila = mysqli_fetch_assoc($resultadoSoporte);
            // Mostrar detalles del soporte en campos de entrada
            echo "<form action='procesar_detalle_soporte.php' method='post'>";
            echo "ID Soporte: <input type='text' name='id_soporte' value='{$fila['id_soporte']}' readonly><br>";
            echo "Descripción: <input type='text' name='descripcion' value='{$fila['descripcion']}'><br>";
            // Otros campos y acciones que desees mostrar o realizar
            echo "<input type='submit' value='Actualizar'>";
            echo "</form>";
        } else {
            echo "No se encontró el detalle del soporte con ID: $id_soporte";
        }
    }
}

mysqli_close($conexion);
?>