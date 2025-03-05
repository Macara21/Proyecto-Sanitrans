<?php
session_start();
require 'controller.php';

if (!isset($_SESSION["usuario_id"]) || $_SESSION["rol"] !== 'admin') {
    header("Location: index.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: asistencias_terceros.php");
    exit();
}

$id = $_GET['id'];
$pdo = conectarDB();
$sql = "DELETE FROM partes_asistencia WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id]);

header("Location: asistencias_terceros.php");
exit();
?>