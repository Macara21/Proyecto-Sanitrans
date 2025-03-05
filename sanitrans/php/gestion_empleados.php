<?php
include 'controller.php';

// Obtener todos los empleados
$empleados = obtenerEmpleados();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Empleados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="\sanitrans\css\Estilo.css">
</head>

<body>
    <header>
        Plataforma web de transporte sanitario
    </header>

    <div class="container mt-5">
        <h3>Gestión de Empleados</h3>
        <a href="crear_empleado.php" class="btn btn-success mb-3">Alta nuevo empleado</a>
        <a href="panel_admin.php" class="btn btn-secondary mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8" />
            </svg>
            Volver al Panel
        </a>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>DNI</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (count($empleados) > 0) {
                    foreach ($empleados as $empleado) {
                        echo "<tr>
                                <td>{$empleado['id']}</td>
                                <td>{$empleado['nombre']}</td>
                                <td>{$empleado['apellidos']}</td>
                                <td>{$empleado['dni']}</td>
                                <td>{$empleado['email']}</td>
                                <td>{$empleado['rol']}</td>
                                <td>
                                    <a href='editar_empleado.php?id={$empleado['id']}' class='btn btn-warning'>Editar</a>
                                    <a href='eliminar_empleado.php?id={$empleado['id']}' class='btn btn-danger'>Eliminar</a>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No hay empleados registrados</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <footer>
        Proyecto desarrollo de aplicaciones web<br>
        Mario Carmona Ramos
    </footer>
</body>

</html>