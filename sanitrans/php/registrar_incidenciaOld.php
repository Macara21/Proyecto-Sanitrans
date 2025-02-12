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

// Procesar el formulario de incidencia
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $descripcion = $_POST['descripcion'];
    $fecha_incidencia = date('Y-m-d H:i:s'); // Fecha y hora actual

    // Insertar la incidencia
    $stmt = $pdo->prepare("INSERT INTO incidencias (turno_id, descripcion, fecha) VALUES (:turno_id, :descripcion, :fecha)");
    $stmt->execute([
        'turno_id' => $turno['id'],
        'descripcion' => $descripcion,
        'fecha' => $fecha_incidencia
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
    <title>Registrar Incidencia - Sanitrans</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Registrar Incidencia</h1>
        <form method="POST">
            <div class="mb-3">
                <label for="descripcion">Descripción de la incidencia:</label>
                <textarea id="descripcion" name="descripcion" class="form-control" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Registrar</button>
        </form>
        <a href="panel_empleado.php" class="btn btn-secondary mt-3">Volver al Panel</a>
    </div>
</body>
</html>