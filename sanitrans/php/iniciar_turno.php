<?php
session_start();
require 'controller.php';

if (!isset($_SESSION["usuario_id"]) || $_SESSION["rol"] !== 'empleado') {
    header("Location: index.php");
    exit();
}

$empleado_id = $_SESSION["usuario_id"];
$matricula = $_POST['matricula'];
$kilometros = $_POST['kilometros'];

// Verificar si ya tiene un turno activo
if (tieneTurnoActivo($empleado_id)) {
    header("Location: panel_empleado.php?error=turno_activo");
    exit();
}

if (iniciarTurno($empleado_id, $matricula, $kilometros)) {
   header("Location: panel_empleado.php?turno_iniciado=1&matricula=" . urlencode($matricula));
    exit();
} else {
    header("Location: panel_empleado.php?error=error_inicio_turno");
    exit();
}
?>