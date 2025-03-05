<?php
session_start();
require 'controller.php';

if (!isset($_SESSION["usuario_id"]) || $_SESSION["rol"] !== 'admin') {
    header("Location: index.php");
    exit();
}

$turno_id = $_GET['id'];
$detalles = obtenerDetallesAmbulanciaPorTurno($turno_id);

// Marcar la incidencia como resuelta si existe
if (isset($detalles['incidencia'])) {
    marcarIncidenciaComoResuelta($turno_id);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de Ambulancia - Sanitrans</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="\sanitrans\css\Estilo.css">
</head>

<body>
    <header class="container h-25 mw-100 p-3">
        <h1 class="m-2">Plataforma web transporte sanitario</h1>
    </header>

    <div class="container mt-5">
        <h1>Detalles de la Ambulancia</h1>
        <a href="gestion_turnos.php" class="btn btn-secondary mb-3">Volver a Turnos</a>

        <table class="table">
            <thead>
                <tr>
                    <th>Matrícula</th>
                    <th>Tipo</th>
                    <th>Kilómetros</th>
                    <th>Checklist</th>
                    <th>Litros de Combustible</th>
                    <th>Coste Total de Combustible</th>
                    <th>Incidencia</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo $detalles['matricula']; ?></td>
                    <td><?php echo $detalles['tipo']; ?></td>
                    <td><?php echo $detalles['kilometros']; ?></td>
                    <td><?php echo $detalles['checklist'] ? 'Sí' : 'No'; ?></td>
                    <td><?php echo $detalles['litros_combustible']; ?></td>
                    <td><?php echo $detalles['coste_combustible']; ?></td>
                    <td><?php echo $detalles['incidencia'] ?? 'No hay incidencias'; ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <footer>
        Proyecto desarrollo de aplicaciones web<br>
        Mario Carmona Ramos
    </footer>
</body>

</html>