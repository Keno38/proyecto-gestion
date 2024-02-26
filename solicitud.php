<?php
session_start();
include_once 'db.php';

$host = "localhost";
$usuario = "root";
$clave = "";
$base_de_datos = "gestion";

$conexion = mysqli_connect($host, $usuario, $clave, $base_de_datos);

// Verifica si el usuario ha iniciado sesión
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
    exit();
}

$nombreDepartamento = isset($_SESSION['usuario']['departamento']) ? $_SESSION['usuario']['departamento'] : '';

// Obtener lista de insumos desde la base de datos
$consultaInsumos = "SELECT id_insumo, insumo FROM insumo";
$resultadoInsumos = mysqli_query($conexion, $consultaInsumos);

if (!$resultadoInsumos) {
    // Manejar el error de la consulta de insumos
    echo "Error en la consulta de insumos: " . mysqli_error($conexion);
    exit();
}

$insumos = mysqli_fetch_all($resultadoInsumos, MYSQLI_ASSOC);
mysqli_free_result($resultadoInsumos);

$mensaje = "";
$disabled = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $insumo = $_POST["insumo"];
    $cantidad = $_POST["cantidad"];



    $consultaVerificacion = "SELECT * FROM solicitud WHERE id_usuario = {$usuario['id']} AND id_insumo = $insumo AND fecha_solicitud = NOW()";
    $resultadoVerificacion = mysqli_query($conexion, $consultaVerificacion);

    if ($resultadoVerificacion !== null && mysqli_num_rows($resultadoVerificacion) > 0) {
        // Ya existe una solicitud para este usuario e insumo
        $mensaje = "Ya existe una solicitud para este usuario e insumo.";
        $disabled = "disabled"; // Deshabilitar el botón de envío

    } else {
        // No existe, proceder con la inserción
        $consultaInsercion = "INSERT INTO solicitud (id_usuario, id_insumo, cantidad, fecha_solicitud) 
                                 VALUES ({$usuario['id']}, $insumo, $cantidad, NOW())";

        if (mysqli_query($conexion, $consultaInsercion)) {
            $mensaje = "Solicitud insertada exitosamente.";
            // Redirigir al usuario de vuelta al contenedor dentro del cliente
            header('Location: cliente.php');
            exit();
        } else {
            // Manejar el error al insertar la solicitud
            $mensaje = "Error al insertar la solicitud: " . mysqli_error($conexion);
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
    <title>Solicitud</title>
</head>

<body>

    <div>
        <div id="mensaje-container" class="mensaje-exito">
            <?php echo $mensaje; ?>
        </div>
        <form action="solicitud.php" method="post">
            <h2>Formulario de Solicitud de Insumos</h2>

            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" value="<?php echo $usuario['nombre']; ?>" required readonly>

            <label for="departamento">Departamento:</label>
            <input type="text" name="departamento" value="<?php echo $nombreDepartamento; ?>" required readonly>

            <label for="insumo">Insumo solicitado:</label>
            <select name="insumo" required>
                <?php foreach ($insumos as $insumoItem) : ?>
                    <option value="<?php echo $insumoItem['id_insumo']; ?>"><?php echo $insumoItem['insumo']; ?></option>
                <?php endforeach; ?>
            </select>

            <label for="cantidad">Cantidad:</label>
            <input type="number" placeholder="Cantidad" name="cantidad" required>
            <input type="hidden" name="fecha_solicitud" value="<?php echo date('Y-m-d H:i:s'); ?>">

            <hr>
            <input type="submit" value="Enviar Solicitud" <?php echo $disabled; ?>>


        </form>
    </div>

    <script>
        // Actualizar el contenido del contenedor de mensaje usando JavaScript
        document.getElementById('mensaje-container').innerHTML = '<?php echo addslashes($mensaje); ?>';
    </script>
</body>

</html>