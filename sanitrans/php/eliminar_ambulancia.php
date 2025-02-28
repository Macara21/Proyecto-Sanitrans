<?php
include 'controller.php';

$id = $_GET['id'];
if (eliminarAmbulancia($id)) {
    header("Location: gestion_ambulancias.php");
} else {
    echo "Error al eliminar la ambulancia.";
}
?>