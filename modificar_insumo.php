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

// Verificar si se enviaron datos desde el formulario anterior
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si se seleccionaron insumos para modificar
    if (isset($_POST['seleccion']) && is_array($_POST['seleccion'])) {
        // Obtener los IDs de los insumos seleccionados
        $insumosSeleccionados = $_POST['seleccion'];

        // Consultar la información de los insumos seleccionados
        $consultaInsumos = "SELECT id_insumo, nombre, especificaciones, cantidad FROM insumo WHERE id_insumo IN (" . implode(',', $insumosSeleccionados) . ")";
        $resultadoInsumos = mysqli_query($conexion, $consultaInsumos);

        if ($resultadoInsumos && mysqli_num_rows($resultadoInsumos) > 0) {
            // Mostrar el formulario para modificar cada insumo seleccionado
            echo "<h2>Modificar Insumos</h2>";
            echo "<form action='procesar_modificacion.php' method='post'>";
            echo "<table class='mi-tabla'>
                    <tr>
                        <th>ID Producto</th>
                        <th>Nombre</th>
                        <th>Especificaciones</th>  
                        <th>Cantidad</th>
                        <th>Nueva Cantidad</th>
                    </tr>";

            while ($fila = mysqli_fetch_assoc($resultadoInsumos)) {
                echo "<tr>
                        <td>{$fila['id_insumo']}</td>
                        <td>{$fila['nombre']}</td>
                        <td>{$fila['especificaciones']}</td>
                        <td>{$fila['cantidad']}</td>
                        <td><input type='number' name='nueva_cantidad[{$fila['id_insumo']}]' value='{$fila['cantidad']}'></td>
                        <input type='hidden' name='id_insumo[]' value='{$fila['id_insumo']}'> <!-- Campo oculto para enviar el ID del insumo -->
                    </tr>";
            }

            echo "</table>";
            echo "<input type='submit' value='Guardar Cambios' class='btn btn-primary'>";
            echo "</form>";
        } else {
            echo "No se encontraron insumos seleccionados.";
        }

        mysqli_free_result($resultadoInsumos);
    } else {
        echo "No se han seleccionado insumos para modificar.";
    }
}

// Aquí puedes colocar el resto del contenido de admin.php
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>Listar Productos Ingresados</title>
</head>

<body>
    <div id="main-content">
        <h2>Productos Ingresados al Sistema</h2>

        "<form action='modificar_insumo.php' method='post'>";
            <div class="sol-div">
                <?php
                // Consulta para obtener los productos ingresados al sistema
                $consultaProductos = "SELECT id_insumo, nombre, especificaciones, cantidad FROM insumo";
                $resultadoProductos = mysqli_query($conexion, $consultaProductos);

                if ($resultadoProductos && mysqli_num_rows($resultadoProductos) > 0) {

                    echo "<table class='mi-tabla'>
                        <tr>
                            <th>ID Producto</th>
                            <th>Nombre</th>
                            <th>Especificaciones</th>  
                            <th>Cantidad</th>
                            <th>Acciones</th>
                        </tr>";

                    while ($fila = mysqli_fetch_assoc($resultadoProductos)) {
                        echo "<tr>
                            <td>{$fila['id_insumo']}</td>
                            <td>{$fila['nombre']}</td>
                            <td>{$fila['especificaciones']}</td>
                            <td>{$fila['cantidad']}</td>
                            <td><input type='checkbox' name='seleccion[]' value='{$fila['id_insumo']}'></td>
                            
                        </tr>";
                    }

                    echo "</table>";
                    echo "<input type='submit' value='Modificar' class='btn btn-primary'>";
                    echo "<input type='submit' value='Eliminar' class='btn btn-primary'>";
                } else {
                    echo "No hay productos ingresados al sistema.";
                }

                mysqli_free_result($resultadoProductos);
                ?>

            </div>
        </form>
    </div>
    <script>
        // Actualizar el contenido del contenedor de mensaje usando JavaScript
        document.getElementById('mensaje-container').innerHTML = '<?php echo addslashes($mensaje); ?>';
    </script>

    < </body>

</html>