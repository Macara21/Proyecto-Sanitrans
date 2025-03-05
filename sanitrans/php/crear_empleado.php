<?php
include 'controller.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $dni = $_POST['dni'];
    $email = $_POST['email'];
    $pswd = $_POST['pswd'];
    $confirmar_pswd = $_POST['confirmar_pswd'];
    $rol = $_POST['rol'];

    // Validar que las contraseñas coincidan
    if ($pswd !== $confirmar_pswd) {
        echo "<script>alert('Las contraseñas no coinciden.');</script>";
    } else {
        // Intentar crear el empleado
        $resultado = crearEmpleado($nombre, $apellidos, $dni, $email, $pswd, $rol);

        if ($resultado === true) {
            header("Location: gestion_empleados.php");
        } else {
            // Mostrar mensaje de error si el DNI o email ya existen
            echo "<script>alert('Error: " . addslashes($resultado) . "');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nuevo Empleado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="\sanitrans\css\Estilo.css">

</head>

<body>
    <header>
        Plataforma web de transporte sanitario
    </header>

    <div class="container mt-5">
        <h3>Alta nuevo empleado</h3>
        <form method="POST" onsubmit="return validarFormulario()">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="mb-3">
                <label for="apellidos" class="form-label">Apellidos</label>
                <input type="text" class="form-control" id="apellidos" name="apellidos" required>
            </div>
            <div class="mb-3">
                <label for="dni" class="form-label">DNI</label>
                <input type="text" class="form-control" id="dni" name="dni" required>
                <small id="errorDNI" class="text-danger"></small>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3 position-relative">
                <label for="pswd" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="pswd" name="pswd" required>
                <span class="password-toggle" onclick="togglePassword('pswd', 'eyeIconPswd')">
                    <i id="eyeIconPswd" class="bi bi-eye"></i> <!-- Ícono de ojo para la contraseña -->
                </span>
            </div>
            <div class="mb-3 position-relative">
                <label for="confirmar_pswd" class="form-label">Confirmar Contraseña</label>
                <input type="password" class="form-control" id="confirmar_pswd" name="confirmar_pswd" required>
                <span class="password-toggle" onclick="togglePassword('confirmar_pswd', 'eyeIconConfirmPswd')">
                    <i id="eyeIconConfirmPswd" class="bi bi-eye"></i> <!-- Ícono de ojo para confirmar contraseña -->
                </span>
                <small id="errorContrasena" class="text-danger"></small>
            </div>
            <div class="mb-3">
                <label for="rol" class="form-label">Rol</label>
                <select class="form-select" id="rol" name="rol" required>
                    <option value="empleado">Empleado</option>
                    <option value="admin">Administrador</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary mb-3">Alta Empleado</button>
            <a href="gestion_empleados.php" class="btn btn-secondary mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8" />
                </svg>
                Volver a gestión
            </a>
        </form>
    </div>

    <footer>
        Proyecto desarrollo de aplicaciones web<br>
        Mario Carmona Ramos
    </footer>

    <script src="../js/scripts.js"></script>
</body>

</html>