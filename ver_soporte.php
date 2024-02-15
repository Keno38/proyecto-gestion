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
    <title>Ver Soportes</title>
</head>

<body>
    <div>
        <h2>Soportes Pendientes</h2>
        <div>
            <form action="procesar_soporte.php" method="post">
                <div class="sol-div">
                    <?php
                    // Consulta para obtener los soportes pendientes
                    $consultaSoporte = "SELECT s.id_soporte, s.id_usuario, s.descripcion, s.fecha_soporte, u.nombre AS nombre, d.departamento 
                    FROM soporte s 
                    INNER JOIN usuarios u ON s.id_usuario = u.id 
                    INNER JOIN departamento d ON u.id_dpto = d.id_dpto WHERE u.id_dpto ";

                    $resultadoSoporte = mysqli_query($conexion, $consultaSoporte);

                    if ($resultadoSoporte && mysqli_num_rows($resultadoSoporte) > 0) {
                        echo "<table class='mi-tabla'>
        <tr>
            <th>ID Soporte</th>
            <th>Nombre Usuario</th>
            <th>Descripcion</th>
            <th>Fecha Soporte</th>              
            <th>Seleccionar</th>
        </tr>";

                        while ($fila = mysqli_fetch_assoc($resultadoSoporte)) {
                            echo "<tr>
            <td>{$fila['id_soporte']}</td>
            <td>{$fila['nombre']}</td>
            <td>{$fila['descripcion']}</td>                               
            <td>{$fila['fecha_soporte']}</td>
            <td><input type='checkbox' name='seleccion[]' value='{$fila['id_soporte']}'></td>
        </tr>";
                        }

                        echo "</table>";
                        echo "<input type='submit' name='tomar' value='Tomar Seleccionadas' class='btn btn-primary'>";
                        echo "<input type='submit' name='eliminar' value='Eliminar Seleccionadas' class='btn btn-danger'>";
                    } else {
                        echo "No hay soportes pendientes.";
                    }

                    mysqli_free_result($resultadoSoporte);
                    ?>

                </div>
            </form>
        </div>
    </div>
</body>

</html>

<?php
mysqli_close($conexion);
?>