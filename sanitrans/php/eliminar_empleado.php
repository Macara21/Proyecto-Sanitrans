<?php
include 'controller.php';

$id = $_GET['id'];
if (eliminarEmpleado($id)) {
    header("Location: gestion_empleados.php");
} else {
    echo "Error al eliminar el empleado.";
}
?>