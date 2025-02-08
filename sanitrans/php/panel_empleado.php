<?php
session_start();
if (!isset($_SESSION["usuario_id"]) || $_SESSION["rol"] !== 'empleado') {
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
    <title>Panel de Empleado - Sanitrans</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Bienvenido, <?php echo htmlspecialchars($nombre); ?></h1>
        <p>Rol: Empleado</p>

        <!-- Formulario para iniciar turno -->
        <div class="card mb-4">
            <div class="card-body">
                <h2>Iniciar Turno</h2>
                <form action="iniciar_turno.php" method="POST">
                    <div class="mb-3">
                        <label for="matricula">Matrícula de la ambulancia:</label>
                        <input type="text" id="matricula" name="matricula" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Iniciar Turno</button>
                </form>
            </div>
        </div>

        <!-- Menú de opciones -->
        <div class="card mb-4">
            <div class="card-body">
                <h2>Opciones</h2>
                <a href="registrar_combustible.php" class="btn btn-secondary mb-2">Registrar Combustible</a>
                <a href="registrar_incidencia.php" class="btn btn-secondary mb-2">Registrar Incidencia</a>
                <a href="logout.php" class="btn btn-danger">Cerrar Sesión</a>
            </div>
        </div>
    </div>
</body>
</html>