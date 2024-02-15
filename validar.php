<?php
$usuario = $_POST['usuario'];
$contraseña = $_POST['contraseña'];
session_start();
$_SESSION['usuario'] = $usuario;

$conexion = mysqli_connect("localhost", "root", "", "gestion");
if (!$conexion) {
    die("Error en la conexión: " . mysqli_connect_error());
}

$consulta = "SELECT * FROM usuarios WHERE usuario='$usuario' AND contraseña='$contraseña'";
$resultado = mysqli_query($conexion, $consulta);

// Verificar si la consulta devuelve resultados
if ($resultado) {
    $filas = mysqli_fetch_assoc($resultado);

    if ($filas) {
        // Almacenar datos del usuario en la sesión
        $_SESSION['usuario'] = array(
            'id' => $filas['id'],
            'nombre' => $filas['nombre'],
            'usuario' => $filas['usuario'],
            'id_cargo' => $filas['id_cargo'],
            'id_dpto' => $filas['id_dpto']
            // Puedes agregar más campos según sea necesario
        );
        // Obtener el nombre del departamento
     

        if ($filas['id_cargo'] == 1) { // administrador
            header("location: admin.php");
        } elseif ($filas['id_cargo'] == 2) { // cliente
            header("location: cliente.php");
        } else {
            include("index.php");
            echo "<h1 class='bad'>ERROR EN LA AUTENTIFICACION</h1>";
        }
    } else {
        include("index.php");
        echo "<h1 class='bad'>ERROR EN LA AUTENTIFICACION</h1>";
    }

    mysqli_free_result($resultado);
} else {
    // Manejar el error de la consulta
    include("index.php");
    echo "<h1 class='bad'>ERROR EN LA CONSULTA</h1>";
}

mysqli_close($conexion);
