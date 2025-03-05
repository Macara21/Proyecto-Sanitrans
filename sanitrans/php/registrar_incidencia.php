<?php
session_start();
require 'controller.php';

if (!isset($_SESSION["usuario_id"]) || $_SESSION["rol"] !== 'empleado') {
    header("Location: index.php");
    exit();
}

$empleado_id = $_SESSION["usuario_id"];

// Obtener el turno activo del empleado
$turno = obtenerTurnoActivo($empleado_id);

if (!$turno) {
    header("Location: panel_empleado.php?error=turno_No_activo");
    exit();
}

// Procesar el formulario de incidencia
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $descripcion = $_POST['descripcion'];

    if (registrarIncidencia($turno['id'], $descripcion)) {
        header("Location: panel_empleado.php?parte_registrado=1");
        exit();
    } else {
        echo "Error al registrar la incidencia.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Incidencia - Sanitrans</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="\sanitrans\css\Estilo.css">
</head>

<body>

    <header>
        Plataforma web de transporte sanitario
    </header>


    <div class="container mt-5">
        <h1>Registrar Incidencia</h1>
        <form method="POST">
            <div class="mb-3">
                <label for="descripcion">Descripción de la incidencia:</label>
                <textarea id="descripcion" name="descripcion" class="form-control" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary mt-3"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                </svg>&nbsp;Registrar</button>
            <a href="panel_empleado.php" class="btn btn-secondary mt-3"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-return-left" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M14.5 1.5a.5.5 0 0 1 .5.5v4.8a2.5 2.5 0 0 1-2.5 2.5H2.707l3.347 3.346a.5.5 0 0 1-.708.708l-4.2-4.2a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 8.3H12.5A1.5 1.5 0 0 0 14 6.8V2a.5.5 0 0 1 .5-.5" />
                </svg>&nbsp;Volver al Panel</a>
        </form>

    </div>
    <footer>
        Proyecto desarrollo de aplicaciones web<br>
        Mario Carmona Ramos
    </footer>
</body>

</html>