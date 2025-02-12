<?php
session_start();
require 'controller.php';

if (!isset($_SESSION["usuario_id"]) || $_SESSION["rol"] !== 'empleado') {
    header("Location: login.php");
    exit();
}

$empleado_id = $_SESSION["usuario_id"];
$matricula = $_POST['matricula'];

if (iniciarTurno($empleado_id, $matricula)) {
    header("Location: panel_empleado.php");
    exit();
} else {
    echo "La matrícula no existe.";
}
?>