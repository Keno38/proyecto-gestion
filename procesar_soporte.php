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

// Verificar si se ha enviado el formulario desde ver_soportes.php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['tomar'])) {
        // Procesar la toma de soportes seleccionados
        if (isset($_POST['seleccion']) && is_array($_POST['seleccion'])) {
            foreach ($_POST['seleccion'] as $id_soporte) {
                // Aquí puedes realizar la lógica para "tomar" el soporte
                // Por ejemplo, puedes actualizar el estado del soporte en la base de datos
                // Ejemplo: UPDATE soporte SET estado = 'Tomado' WHERE id_soporte = $id_soporte;
            }
        }
    } elseif (isset($_POST['eliminar'])) {
        // Procesar la eliminación de soportes seleccionados
        if (isset($_POST['seleccion']) && is_array($_POST['seleccion'])) {
            foreach ($_POST['seleccion'] as $id_soporte) {
                // Aquí puedes realizar la lógica para "eliminar" el soporte
                // Por ejemplo, puedes eliminar el soporte de la base de datos
                // Ejemplo: DELETE FROM soporte WHERE id_soporte = $id_soporte;
            }
        }
    }
}

// Puedes redirigir a la página que desees después de procesar las acciones.

mysqli_close($conexion);
?>