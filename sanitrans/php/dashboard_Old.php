<?php
session_start();
include 'db.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header("login.php");
    exit();
}

try {
    // Obtener el ID del usuario logueado
    $usuario_id = $_SESSION['usuario_id'];

    // Consulta para obtener los turnos del usuario
    $sql = "SELECT t.id, t.inicio_turno, t.fin_turno, v.matricula 
            FROM turnos t 
            JOIN ambulancias v ON t.ambulancia_id = v.id 
            WHERE t.empleado_id = :usuario_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':usuario_id', $usuario_id);
    $stmt->execute();
    $turnos = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sanitrans</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Hola, <?php echo $_SESSION['nombre']; ?></h2>
        <h3>Mis Turnos</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID Turno</th>
                    <th>Inicio</th>
                    <th>Fin</th>
                    <th>Ambulancia</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($turnos as $turno): ?>
                <tr>
                    <td><?php echo $turno['id']; ?></td>
                    <td><?php echo $turno['inicio_turno']; ?></td>
                    <td><?php echo $turno['fin_turno']; ?></td>
                    <td><?php echo $turno['matricula']; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>