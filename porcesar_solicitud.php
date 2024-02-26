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

// Recupera los datos del usuario de la sesión
$usuario = $_SESSION['usuario'];

// Verificar si se ha enviado el formulario con selecciones
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['seleccion'])) {
    $seleccion = $_POST['seleccion'];

    // Mostrar el formulario para procesar las solicitudes seleccionadas
    echo "<h2>Procesar Solicitudes Seleccionadas</h2>";
    echo "<form action='porcesar_solicitud.php' method='post'>";
    echo "<div class='sol-div'>";
    echo "<table class='mi-tabla'>
            <tr>
                <th>ID Solicitud</th>
                <th>ID Usuario</th>
                <th>Insumo</th>
                <th>Cantidad</th>  
                <th>Fecha Solicitud</th>
                <th>Acción</th>
            </tr>";

    // Mostrar las solicitudes seleccionadas
    foreach ($seleccion as $idSolicitud) {
        $consultaSolicitud = "SELECT id_solicitud, id_usuario, id_insumo, cantidad, fecha_solicitud 
                            FROM solicitud 
                            WHERE id_solicitud = $idSolicitud";
        $resultadoSolicitud = mysqli_query($conexion, $consultaSolicitud);

        if ($resultadoSolicitud && mysqli_num_rows($resultadoSolicitud) == 1) {
            $fila = mysqli_fetch_assoc($resultadoSolicitud);
            echo "<tr>
                    <td>{$fila['id_solicitud']}</td>
                    <td>{$fila['id_usuario']}</td>
                    <td>{$fila['id_insumo']}</td>
                    <td>{$fila['cantidad']}</td>                      
                    <td>{$fila['fecha_solicitud']}</td>
                    <td>
                        <input type='hidden' name='seleccion[]' value='{$fila['id_solicitud']}'>
                        <select name='accion[]'>
                            <option value='resolver'>Resolver</option>
                            <option value='eliminar'>Eliminar</option>
                        </select>
                    </td>
                </tr>";
        }
        mysqli_free_result($resultadoSolicitud);
    }

    echo "</table>";
    echo "<input type='submit' value='Procesar' class='btn btn-primary'>";
    echo "</div>";
    echo "</form>";
} else {
    // Consulta para obtener las solicitudes pendientes
    $consultaSolicitudes = "SELECT id_solicitud, id_usuario, id_insumo, cantidad, fecha_solicitud 
                            FROM solicitud 
                            WHERE estado = 'Pendiente'";

    $resultadoSolicitudes = mysqli_query($conexion, $consultaSolicitudes);
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/style.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <title>Procesar Solicitudes</title>
    </head>

    <body>
        <div>
            <h2>Procesar Solicitudes</h2>
            <form action="procesar_solicitud.php" method="post">
                <div class="sol-div">
                    <?php
                    if ($resultadoSolicitudes && mysqli_num_rows($resultadoSolicitudes) > 0) {
                        echo "<table class='mi-tabla'>
                        <tr>
                            <th>Seleccionar</th>
                            <th>ID Solicitud</th>
                            <th>ID Usuario</th>
                            <th>Insumo</th>
                            <th>Cantidad</th>  
                            <th>Fecha Solicitud</th>
                           
                        </tr>";

                        while ($fila = mysqli_fetch_assoc($resultadoSolicitudes)) {
                            echo "<tr>
                            
                            <td><input type='checkbox' name='seleccion[]' value='{$fila['id_solicitud']}'></td>
                            <td>{$fila['id_solicitud']}</td>
                            <td>{$fila['id_usuario']}</td>
                            <td>{$fila['id_insumo']}</td>
                            <td>{$fila['cantidad']}</td>                      
                            <td>{$fila['fecha_solicitud']}</td>
                            
                        </tr>";
                        }

                        echo "</table>";
                        echo "<input type='submit' value='Procesar Seleccionadas' class='btn btn-primary'>";
                    } else {
                        echo "No hay solicitudes pendientes.";
                    }

                    mysqli_free_result($resultadoSolicitudes);
                    ?>
                </div>
            </form>
        </div>
    </body>

    </html>

<?php
}
mysqli_close($conexion);
?>