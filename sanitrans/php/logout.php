<?php
session_start();
require 'controller.php';

if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit();
}

$empleado_id = $_SESSION["usuario_id"];

// Cerrar sesión y registrar el fin de turno
if (cerrarSesion($empleado_id)) {
    header("Location: login.php"); // Redirigir al login
    exit();
} else {
    echo "Error al cerrar la sesión.";
}
?>