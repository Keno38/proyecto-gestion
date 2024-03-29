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
    header('Location: cliente.php');
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

$mensaje = "";

// Verificar si se ha enviado el formulario de soporte técnico
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recuperar valores del formulario
    $nombre = $_POST["nombre"];
    $descripcion = $_POST["descripcion"];

    // Construir y ejecutar la consulta de inserción
    $consultaInsercion = "INSERT INTO soporte (id_usuario, descripcion, fecha_soporte) 
                          VALUES ({$usuario['id']}, '$descripcion', NOW())";

    if (mysqli_query($conexion, $consultaInsercion)) {
        // Éxito al insertar la incidencia, definir el mensaje
        $mensaje = "La solicitud se envió exitosamente.";
    } else {
        // Error al insertar la incidencia
        echo "Error al insertar la incidencia: " . mysqli_error($conexion);
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
    <title>Soporte Técnico</title>
    <style>
        /* CSS para ocultar el formulario cuando se muestra el mensaje */
        .hidden {
            display: none;
        }
    </style>
</head>

<body>
    <div>
        <form action="soporte.php" method="post">
            <h2>Formulario de Soporte Técnico</h2>

            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" value="<?php echo $usuario['nombre']; ?>" required readonly>

            <label for="Departamento">Departamento:</label>
            <input type="text" name="departamento" value="<?php echo $nombreDepartamento; ?>" required readonly>

            <label for="descripcion">Soporte :</label>
            <input type="text" placeholder=" Descripcion del problema" name="descripcion" required>
            <input type="hidden" name="fecha_soporte" value="<?php echo date('Y-m-d H:i:s'); ?>">

            <hr>
            <input type="submit" value="Enviar">
            </hr>

        </form>
    </div>
    <script>
        // Mostrar un mensaje emergente con el mensaje de la sesión, luego eliminar el mensaje de la sesión
        <?php if (!empty($mensaje)) : ?>
            alert("<?php echo $mensaje; ?>");
            <?php unset($_SESSION['mensaje']); ?>
            window.location.href = 'cliente.php';
        <?php endif; ?>
    </script>
</body>

</html>