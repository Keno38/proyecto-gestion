<?php
session_start();

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    // Si no ha iniciado sesión, redirige a la página de inicio de sesión o a donde sea necesario
    header('Location: index.php');
    exit();
}

// Recupera los datos del usuario de la sesión
$usuario = $_SESSION['usuario'];
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Usuario</title>
</head>

<body>
    <h1>Bienvenido, <?php echo $usuario['nombre']; ?></h1>
    <div>
        <ul>
            <li><a href="#" onclick="cargarContenido('admi_soporte.php')">Administrar Soporte Tecnico </a></li>
            <li><a href="#" onclick="cargarContenido('admi_solicitud.php')">Administrar Solicitud Insumos </a></li>
            <li><a href="#" onclick="cargarContenido('ingreso_insumo.php')">Inventario </a></li>
            <li><a href="cerrar_sesion.php">Cerrar Sesión</a></li>
        </ul>
        <div id="main-content">
            <!-- Contenido principal de la página -->
            <h3>Contenido principal de la página</h3>
        </div>
    </div>

    <script>
        function cargarContenido(url) {
            // Utiliza AJAX para cargar el contenido de la URL dentro de #main-content
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    document.getElementById("main-content").innerHTML = xhr.responseText;
                }
            };
            xhr.open("GET", url, true);
            xhr.send();
        }
    </script>


</body>

</html>