<?php


session_start();
session_unset();
session_destroy();
header("Location: index.php"); // Cambia esto por la página de inicio de sesión o a donde quieras redirigir después de cerrar sesión
exit();
