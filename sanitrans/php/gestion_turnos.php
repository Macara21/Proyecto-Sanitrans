<?php
session_start();
require 'controller.php';

// Verificar si es admin
if (!isset($_SESSION["usuario_id"]) || $_SESSION["rol"] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Obtener todos los turnos y empleados
$turnos = obtenerTodosTurnosProgramados();
$empleados = obtenerEmpleados();

// Procesar formulario de creación/edición
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['crear_turno'])) {
        crearTurnoProgramado(
            $_POST['empleado_id'],
            $_POST['fecha'],
            $_POST['hora_inicio'],
            $_POST['hora_fin'],
            $_POST['descripcion']
        );
    } elseif (isset($_POST['editar_turno'])) {
        editarTurnoProgramado(
            $_POST['id'],
            $_POST['empleado_id'],
            $_POST['fecha'],
            $_POST['hora_inicio'],
            $_POST['hora_fin'],
            $_POST['descripcion']
        );
    }
    header("Location: gestion_turnos.php"); // Recargar la página
    exit();
}

// Procesar eliminación de turno
if (isset($_GET['eliminar'])) {
    eliminarTurnoProgramado($_GET['eliminar']);
    header("Location: gestion_turnos.php"); // Recargar la página
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Turnos - Sanitrans</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/sanitrans\css\Estilo.css">
</head>

<body>

    <header class="container h-25 mw-100 p-3">
        <h1 class="m-2">Plataforma web transporte sanitario</h1>
    </header>

    <div class="container mt-5">
        <!-- Botón para volver al panel de administrador -->
        <a href="panel_admin.php" class="btn btn-secondary mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8" />
            </svg>
            Volver al Panel
        </a>

        <h2>Gestión de Turnos</h2>

        <!-- Formulario para crear/editar turnos -->
        <div class="card mb-4">
            <div class="card-body">
                <h3><?php echo isset($_GET['editar']) ? 'Editar Turno' : 'Crear Turno'; ?></h3>
                <form method="POST">
                    <?php if (isset($_GET['editar'])): ?>
                        <input type="hidden" name="id" value="<?php echo $_GET['editar']; ?>">
                    <?php endif; ?>
                    <div class="mb-3">
                        <label class="form-label">Empleado</label>
                        <select class="form-select" name="empleado_id" required>
                            <?php foreach ($empleados as $empleado): ?>
                                <option value="<?php echo $empleado['id']; ?>"
                                    <?php if (isset($_GET['empleado_id']) && $_GET['empleado_id'] == $empleado['id']) echo 'selected'; ?>>
                                    <?php echo htmlspecialchars($empleado['nombre'] . ' ' . $empleado['apellidos']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fecha</label>
                        <input type="date" class="form-control" name="fecha"
                            value="<?php echo isset($_GET['fecha']) ? $_GET['fecha'] : ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Hora de Inicio</label>
                        <input type="time" class="form-control" name="hora_inicio"
                            value="<?php echo isset($_GET['hora_inicio']) ? $_GET['hora_inicio'] : ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Hora de Fin</label>
                        <input type="time" class="form-control" name="hora_fin"
                            value="<?php echo isset($_GET['hora_fin']) ? $_GET['hora_fin'] : ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea class="form-control" name="descripcion"><?php echo isset($_GET['descripcion']) ? $_GET['descripcion'] : ''; ?></textarea>
                    </div>
                    <button type="submit" name="<?php echo isset($_GET['editar']) ? 'editar_turno' : 'crear_turno'; ?>"
                        class="btn btn-primary">
                        <?php echo isset($_GET['editar']) ? 'Guardar Cambios' : 'Crear Turno'; ?>
                    </button>
                </form>
            </div>
        </div>

        <!-- Lista de turnos programados -->
        <div class="card">
            <div class="card-body">
                <h3>Turnos Programados</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Empleado</th>
                            <th>Fecha</th>
                            <th>Hora Inicio</th>
                            <th>Hora Fin</th>
                            <th>Descripción</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($turnos as $turno): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($turno['nombre'] . ' ' . $turno['apellidos']); ?></td>
                                <td><?php echo htmlspecialchars($turno['fecha']); ?></td>
                                <td><?php echo htmlspecialchars($turno['hora_inicio']); ?></td>
                                <td><?php echo htmlspecialchars($turno['hora_fin']); ?></td>
                                <td><?php echo htmlspecialchars($turno['descripcion']); ?></td>
                                <td>
                                    <a href="gestion_turnos.php?editar=<?php echo $turno['id']; ?>&empleado_id=<?php echo $turno['empleado_id']; ?>&fecha=<?php echo $turno['fecha']; ?>&hora_inicio=<?php echo $turno['hora_inicio']; ?>&hora_fin=<?php echo $turno['hora_fin']; ?>&descripcion=<?php echo urlencode($turno['descripcion']); ?>"
                                        class="btn btn-warning btn-sm">Editar</a>
                                    <a href="gestion_turnos.php?eliminar=<?php echo $turno['id']; ?>"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('¿Estás seguro de eliminar este turno?');">Eliminar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>