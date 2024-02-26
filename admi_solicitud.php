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

// Obtener el nombre del departamento
$idDepartamento = $usuario['id_dpto'];
$consultaDepartamento = "SELECT departamento FROM departamento WHERE id_dpto = '$idDepartamento'";
$resultadoDepartamento = mysqli_query($conexion, $consultaDepartamento);

if ($resultadoDepartamento) {
    $filaDepartamento = mysqli_fetch_assoc($resultadoDepartamento);
    $_SESSION['usuario']['departamento'] = $filaDepartamento['departamento'];
    mysqli_free_result($resultadoDepartamento);
} else {
    // Manejar el error de la consulta del departamento
    echo "Error en la consulta del departamento: " . mysqli_error($conexion);
}

$nombreDepartamento = isset($_SESSION['usuario']['departamento']) ? $_SESSION['usuario']['departamento'] : '';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>Ver Insumos</title>
</head>

<body>
    <div>
        <h2>Insumos Solicitados e Ingresados</h2>
        <div>
            <from action="procesar_solicitud.php" method="post">
                <div class="sol-div">
                    <?php
                    // Consulta para obtener los insumos solicitados e ingresados
                    $consultaInsumos = "SELECT s.id_solicitud, s.id_usuario, s.id_insumo, s.cantidad,s.fecha_solicitud, u.nombre AS nombreu, i.nombre AS nombre , d.departamento 
                            FROM solicitud s 
                            INNER JOIN usuarios u ON s.id_usuario = u.id 
                            INNER JOIN insumo i ON s.id_insumo = i.id_insumo 
                            INNER JOIN departamento d ON u.id_dpto = d.id_dpto WHERE u.id_dpto ";

                    $resultadoInsumos = mysqli_query($conexion, $consultaInsumos);

                    if ($resultadoInsumos && mysqli_num_rows($resultadoInsumos) > 0) {
                        echo "<table class='mi-tabla'>
                    <tr>
                        <th>Seleccionar</th>
                        <th>ID Solicitud</th>
                        <th>Nombre Usuario</th>
                        <th>Insumo</th>
                        <th>Cantidad</th>  
                        <th>Departamento</th>
                        <th>fecha_solicitud</th>
                    </tr>";

                        while ($fila = mysqli_fetch_assoc($resultadoInsumos)) {
                            echo "<tr>
                        <td><input type='checkbox' name='seleccion[]' value='{$fila['id_solicitud']}'></td>   
                        <td>{$fila['id_solicitud']}</td>
                        <td>{$fila['nombreu']}</td>
                        <td>{$fila['nombre']}</td>
                        <td>{$fila['cantidad']}</td>                      
                        <td>{$fila['departamento']}</td>
                        <td>{$fila['fecha_solicitud']}</td>
                        
                    </tr>";
                        }

                        echo "</table>";
                    } else {
                        echo "No hay insumos solicitados e ingresados.";
                    }

                    mysqli_free_result($resultadoInsumos);
                    ?>
                    <input type="submit" value="Ver Detalle ">
                </div>


                </form>
        </div>
    </div>

    <script>
        // Actualizar el contenido del contenedor de mensaje usando JavaScript
        document.getElementById('mensaje-container').innerHTML = '<?php echo addslashes($mensaje); ?>';
    </script>


</body>

</html>

<?php
mysqli_close($conexion);
?>