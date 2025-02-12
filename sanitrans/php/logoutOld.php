<?php
session_start();
if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit();
}

require 'db.php';

$empleado_id = $_SESSION["usuario_id"];
$fin_turno = date('Y-m-d H:i:s'); // Fecha y hora actual

// Registrar el fin de turno
$stmt = $pdo->prepare("UPDATE turnos SET fin_turno = :fin_turno WHERE empleado_id = :empleado_id AND fin_turno IS NULL");
$stmt->execute([
    'fin_turno' => $fin_turno,
    'empleado_id' => $empleado_id
]);

session_destroy(); // Destruir la sesión
header("Location: login.php"); // Redirigir al login
exit();
?>