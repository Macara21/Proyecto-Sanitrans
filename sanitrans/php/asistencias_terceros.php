<?php
session_start();
require 'controller.php';

if (!isset($_SESSION["usuario_id"]) || $_SESSION["rol"] !== 'admin') {
    header("Location: login.php");
    exit();
}

$pdo = conectarDB();
$sql = "SELECT pa.*, at.nombre_asegurado, at.matricula_vehiculo, al.empresa, cp.aseguradora_compania, e.pais_origen 
        FROM partes_asistencia pa
        LEFT JOIN accidente_trafico at ON pa.id = at.parte_id
        LEFT JOIN accidente_laboral al ON pa.id = al.parte_id
        LEFT JOIN compania_privada cp ON pa.id = cp.parte_id
        LEFT JOIN extranjero e ON pa.id = e.parte_id
        ORDER BY pa.fecha_servicio DESC, pa.hora_servicio DESC";
$stmt = $pdo->query($sql);
$asistencias = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asistencias a Terceros - Sanitrans</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="\sanitrans\css\Estilo.css">
</head>
<body>
    <header class="container h-25 mw-100 p-3">
        <h1 class="m-2">Plataforma web transporte sanitario</h1>
    </header>

    <div class="container mt-5">
        <h1>Asistencias a Terceros</h1>
        <a href="panel_admin.php" class="btn btn-secondary mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8" />
            </svg>
            Volver al Panel
        </a>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Tipo de Incidente</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($asistencias as $asistencia): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($asistencia['fecha_servicio']); ?></td>
                        <td><?php echo htmlspecialchars($asistencia['hora_servicio']); ?></td>
                        <td><?php echo htmlspecialchars($asistencia['nombre_paciente']); ?></td>
                        <td><?php echo htmlspecialchars($asistencia['apellidos_paciente']); ?></td>
                        <td><?php echo htmlspecialchars($asistencia['tipo_incidente']); ?></td>
                        <td>
                            <a href="editar_asistencia.php?id=<?php echo $asistencia['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="eliminar_asistencia.php?id=<?php echo $asistencia['id']; ?>" class="btn btn-danger btn-sm">Eliminar</a>
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