<?php
// Configuración de la base de datos
$host = "localhost";
$usuario = "root";  // Cambia esto si tienes un usuario distinto
$password = "";   // Pon la contraseña si la has configurado en XAMPP
$base_de_datos = "sanitrans";

try {
    // Crear conexión usando PDO
    $pdo = new PDO("mysql:host=$host;dbname=$base_de_datos;charset=utf8", $usuario, $password); //UTF-8 para evitar problemas con acentos y caracteres especiales.
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   
} catch (PDOException $e) {
    die("Error en la conexión: " . $e->getMessage());
}
?>
