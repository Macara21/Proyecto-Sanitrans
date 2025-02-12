<?php
session_start();
if (!isset($_SESSION["usuario_id"]) || $_SESSION["rol"] !== 'empleado') {
    header("Location: login.php");
    exit();
}

require 'db.php';

$empleado_id = $_SESSION["usuario_id"];

// Obtener el ID del turno actual del empleado
$stmt = $pdo->prepare("SELECT id FROM turnos WHERE empleado_id = :empleado_id AND fin_turno IS NULL");
$stmt->execute(['empleado_id' => $empleado_id]);
$turno = $stmt->fetch();

if (!$turno) {
    echo "No tienes un turno activo.";
    exit();
}

// Procesar el formulario de combustible
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $litros = $_POST['litros'];
    $coste = $_POST['coste'];
    $fecha_repostaje = date('Y-m-d H:i:s'); // Fecha y hora actual

    // Insertar el registro de combustible
    $stmt = $pdo->prepare("INSERT INTO combustible (turno_id, litros, coste, fecha) VALUES (:turno_id, :litros, :coste, :fecha)");
    $stmt->execute([
        'turno_id' => $turno['id'],
        'litros' => $litros,
        'coste' => $coste,
        'fecha' => $fecha_repostaje
    ]);

    header("Location: panel_empleado.php"); // Redirigir al panel
    exit();
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