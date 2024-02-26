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
    <?php
    // Mostrar mensaje si está presente en la sesión
    if (isset($_SESSION['mensaje'])) {
        echo '<div class="alert alert-success">' . $_SESSION['mensaje'] . '</div>';
        // Limpiar el mensaje de la sesión para que no se muestre nuevamente
        unset($_SESSION['mensaje']);
    }
    ?>

    <div id="banner">

        <h1>Bienvenido, <?php echo $usuario['nombre']; ?></h1>

    </div>

    <div id="menu">
        <ul>
            <li><a href="#" onclick="cargarContenido('soporte.php')">Soporte Tecnico </a></li>
            <li><a href="#" onclick="cargarContenido('solicitud.php')">Solicitud Insumos </a></li>
            <li><a href="cerrar_sesion.php">Cerrar Sesión</a></li>
        </ul>
    </div>
    <div id="main-content">
        <!-- Contenido principal de la página -->

        <div id="fanpage-container">

            <iframe src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2Fmunicipalidaddedonihue&tabs=timeline&width=340&height=500&small_header=false&adapt_container_width=true&hide_cover=false&show_facepile=true&appId" width="100%" height="500" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>
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