<?php
session_start();
require 'controller.php';

if (!isset($_SESSION["usuario_id"]) || $_SESSION["rol"] !== 'admin') {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administrador - Sanitrans</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="\sanitrans\css\Estilo.css">
</head>

<body>

    <header class="container h-25 mw-100 p-3">
        <h1 class="m-2">Plataforma web transporte sanitario</h1>
    </header>

    <div class="container mt-5">
        <h1>Panel de Administrador</h1>
        <p>Bienvenido, <?php echo htmlspecialchars($_SESSION["nombre"]); ?></p>

        <!-- Botón para gestionar turnos -->
        <div class="card mb-4">
            <div class="card-body">
                <h2>Opciones</h2>
                <a href="gestion_turnos.php" class="btn btn-primary mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-plus" viewBox="0 0 16 16">
                        <path d="M8 7a.5.5 0 0 1 .5.5V9H10a.5.5 0 0 1 0 1H8.5v1.5a.5.5 0 0 1-1 0V10H6a.5.5 0 0 1 0-1h1.5V7.5A.5.5 0 0 1 8 7" />
                        <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z" />
                    </svg>
                    &nbsp;Gestionar Turnos
                </a>

                <!-- Otros botones del panel -->
                <a href="gestion_empleados.php" class="btn btn-secondary mb-3">Gestionar Empleados</a>
                <a href="gestion_ambulancias.php" class="btn btn-secondary mb-3">Gestionar Ambulancias</a>
                <a href="logout.php" class="btn btn-danger mb-3">Cerrar Sesión</a>
            </div>
        </div>
    </div>
</body>

</html>