<?php
session_start();
if (!isset($_SESSION["usuario_id"]) || $_SESSION["rol"] !== 'empleado') {
    header("Location: login.php");
    exit();
}

require 'db.php';

$empleado_id = $_SESSION["usuario_id"];
$matricula = $_POST['matricula'];

// Verificar si la ambulancia existe
$stmt = $pdo->prepare("SELECT id FROM ambulancias WHERE matricula = :matricula");
$stmt->execute(['matricula' => $matricula]);
$ambulancia = $stmt->fetch();

if (!$ambulancia) {
    echo "La matrícula no existe.";
    exit();
}

// Registrar el inicio de turno
$inicio_turno = date('Y-m-d H:i:s'); // Fecha y hora actual
$stmt = $pdo->prepare("INSERT INTO turnos (empleado_id, ambulancia_id, inicio_turno) VALUES (:empleado_id, :ambulancia_id, :inicio_turno)");
$stmt->execute([
    'empleado_id' => $empleado_id,
    'ambulancia_id' => $ambulancia['id'],
    'inicio_turno' => $inicio_turno
]);

header("Location: panel_empleado.php"); // Redirigir al panel
exit();
?>