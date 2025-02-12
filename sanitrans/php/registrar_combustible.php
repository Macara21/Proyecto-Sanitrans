<?php
session_start();
require 'controller.php';

if (!isset($_SESSION["usuario_id"]) || $_SESSION["rol"] !== 'empleado') {
    header("Location: login.php");
    exit();
}

$empleado_id = $_SESSION["usuario_id"];

// Obtener el turno activo del empleado
$turno = obtenerTurnoActivo($empleado_id);

if (!$turno) {
    echo "No tienes un turno activo.";
    exit();
}

// Procesar el formulario de combustible
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $litros = $_POST['litros'];
    $coste = $_POST['coste'];

    if (registrarCombustible($turno['id'], $litros, $coste)) {
        header("Location: panel_empleado.php"); // Redirigir al panel
        exit();
    } else {
        echo "Error al registrar el combustible.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Combustible - Sanitrans</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Registrar Combustible</h1>
        <form method="POST">
            <div class="mb-3">
                <label for="litros">Litros repostados:</label>
                <input type="number" id="litros" name="litros" class="form-control" step="0.01" required>
            </div>
            <div class="mb-3">
                <label for="coste">Coste total:</label>
                <input type="number" id="coste" name="coste" class="form-control" step="0.01" required>
            </div>
            <button type="submit" class="btn btn-primary">Registrar</button>
        </form>
        <a href="panel_empleado.php" class="btn btn-secondary mt-3">Volver al Panel</a>
    </div>
</body>
</html>