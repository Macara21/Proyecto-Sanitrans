<?php
session_start();
if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit();
}

require 'db.php';

$rol = $_SESSION["rol"];

// Redirigir según el rol
if ($rol === 'admin') {
    header("Location: panel_admin.php");
} elseif ($rol === 'empleado') {
    header("Location: panel_empleado.php");
} else {
    echo "Rol no reconocido.";
    exit();
}
?>