<?php
session_start();
require 'controller.php';

if (!isset($_SESSION["usuario_id"]) || $_SESSION["rol"] !== 'admin') {
    header("Location: index.php");
    exit();
}

$turnos = obtenerTodosTurnosConDetalles();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Turnos - Sanitrans</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="\sanitrans\css\Estilo.css">
</head>

<body>
     <header>
        Plataforma web de transporte sanitario
    </header>

    <div class="container mt-5">
        <h1>Gestión de Turnos</h1>
        <a href="panel_admin.php" class="btn btn-secondary mb-3">Volver al Panel</a>

        <table class="table">
            <thead>
                <tr>
                    <th>Inicio de Turno</th>
                    <th>Fin de Turno</th>
                    <th>Total Horas</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>DNI</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($turnos as $turno): ?>
                    <?php
                    // Verificar si hay una incidencia no resuelta asociada al turno
                    $tieneIncidenciaNoResuelta = tieneIncidenciaNoResuelta($turno['id']);
                    $colorBoton = $tieneIncidenciaNoResuelta ? 'btn-danger' : 'btn-info';
                    ?>
                    <tr>
                        <td><?php echo $turno['inicio_turno']; ?></td>
                        <td><?php echo $turno['fin_turno']; ?></td>
                        <td><?php echo calcularHorasTrabajadas($turno['inicio_turno'], $turno['fin_turno']); ?></td>
                        <td><?php echo $turno['nombre']; ?></td>
                        <td><?php echo $turno['apellidos']; ?></td>
                        <td><?php echo $turno['dni']; ?></td>
                        <td>
                            <a href="detalles_ambulancia.php?id=<?php echo $turno['id']; ?>" class="btn <?php echo $colorBoton; ?>">Ambulancia</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <footer>
        Proyecto desarrollo de aplicaciones web<br>
        Mario Carmona Ramos
    </footer>
</body>

</html>