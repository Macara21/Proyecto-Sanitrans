<?php
session_start();
if (!isset($_SESSION["usuario_id"]) || $_SESSION["rol"] !== 'admin') {
    header("Location: login.php");
    exit();
}

require 'db.php';

$usuario_id = $_SESSION["usuario_id"];
$nombre = $_SESSION["nombre"];
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrador - Sanitrans</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
        <h1>Bienvenido, <?php echo htmlspecialchars($nombre); ?></h1>
        <p>Rol: Administrador</p>
</div>
    
</body>
</html>