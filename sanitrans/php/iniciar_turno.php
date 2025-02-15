<?php
session_start();
require 'controller.php';

if (!isset($_SESSION["usuario_id"]) || $_SESSION["rol"] !== 'empleado') {
    header("Location: login.php");
    exit();
}

$empleado_id = $_SESSION["usuario_id"];
$matricula = $_POST['matricula'];

// Verificar si ya tiene un turno activo
if (tieneTurnoActivo($empleado_id)) {
    header("Location: panel_empleado.php?error=turno_activo");
    exit();
}

if (iniciarTurno($empleado_id, $matricula)) {
   // header("Location: panel_empleado.php");//
   header("Location: panel_empleado.php?turno_iniciado=1&matricula=" . urlencode($matricula));
    exit();
} else {
    echo "La matrícula no existe.";
    exit();
}
?>