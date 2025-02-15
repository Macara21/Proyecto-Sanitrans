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
            <button type="submit" class="btn btn-primary"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check2-all" viewBox="0 0 16 16">
                    <path d="M12.354 4.354a.5.5 0 0 0-.708-.708L5 10.293 1.854 7.146a.5.5 0 1 0-.708.708l3.5 3.5a.5.5 0 0 0 .708 0zm-4.208 7-.896-.897.707-.707.543.543 6.646-6.647a.5.5 0 0 1 .708.708l-7 7a.5.5 0 0 1-.708 0" />
                    <path d="m5.354 7.146.896.897-.707.707-.897-.896a.5.5 0 1 1 .708-.708" />
                </svg>&nbsp;Registrar</button>
        </form>
        <a href="panel_empleado.php" class="btn btn-secondary mt-3"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-return-left" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M14.5 1.5a.5.5 0 0 1 .5.5v4.8a2.5 2.5 0 0 1-2.5 2.5H2.707l3.347 3.346a.5.5 0 0 1-.708.708l-4.2-4.2a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 8.3H12.5A1.5 1.5 0 0 0 14 6.8V2a.5.5 0 0 1 .5-.5" />
            </svg>&nbsp;Volver al Panel</a>
    </div>
</body>

</html>