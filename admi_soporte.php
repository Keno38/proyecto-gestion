<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>Ver Soporte</title>
</head>

<body>
    <div>
        <h2>Soporte Solicitado</h2>
        <div id="main-content">
            <!-- Contenido principal de la página -->
            <div class="sol-div">
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

                // Verificar si se ha enviado el formulario con selecciones
                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['seleccion'])) {
                    $seleccion = $_POST['seleccion'];

                    // Lógica para eliminar los soportes seleccionados
                    foreach ($seleccion as $id_soporte) {
                        // Ejecuta la lógica para eliminar cada soporte
                        // Ejemplo: mysqli_query($conexion, "DELETE FROM soporte WHERE id_soporte = $id_soporte");
                    }
                }

                // Consulta para obtener los soportes ingresados
                $consultaSoporte = "SELECT s.id_soporte, s.id_usuario, s.descripcion, s.fecha_soporte, u.nombre AS nombre, d.departamento 
                    FROM soporte s 
                    INNER JOIN usuarios u ON s.id_usuario = u.id 
                    INNER JOIN departamento d ON u.id_dpto = d.id_dpto WHERE u.id_dpto ";

                $resultadoSoporte = mysqli_query($conexion, $consultaSoporte);

                if ($resultadoSoporte && mysqli_num_rows($resultadoSoporte) > 0) {
                    echo "<table class='mi-tabla'>
                        <tr>
                            <th>Seleccionar</th>
                            <th>ID Soporte</th>
                            <th>Nombre Usuario</th>
                            <th>Descripcion</th>
                            <th>Fecha Soporte</th>              
                        </tr>";

                    while ($fila = mysqli_fetch_assoc($resultadoSoporte)) {
                        echo "<tr>
                            <td><input type='checkbox' name='seleccion[]' value='{$fila['id_soporte']}'></td>
                            <td>{$fila['id_soporte']}</td>
                            <td>{$fila['nombre']}</td>
                            <td>{$fila['descripcion']}</td>                               
                            <td>{$fila['fecha_soporte']}</td>
                        </tr>";
                    }

                    echo "</table>";
                } else {
                    echo "No hay soportes pendientes.";
                }

                mysqli_free_result($resultadoSoporte);
                ?>
                <input type="button" value="Ver Detalle" id="verDetalle">
            </div>
        </div>
    </div>

    <script>
        document.getElementById('verDetalle').addEventListener('click', function() {
            var seleccion = document.querySelectorAll('input[name="seleccion[]"]:checked');
            var seleccionIds = [];
            for (var i = 0; i < seleccion.length; i++) {
                seleccionIds.push(seleccion[i].value);
            }

            // Utiliza AJAX para cargar detalle_soporte.php dentro de #main-content
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    document.getElementById("main-content").innerHTML = xhr.responseText;
                }
            };
            xhr.open("POST", "detalle_soporte.php", true); // Corregido aquí
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.send("seleccion=" + encodeURIComponent(JSON.stringify(seleccionIds)));
        });
    </script>
</body>

</html>

<?php
mysqli_close($conexion);
?>