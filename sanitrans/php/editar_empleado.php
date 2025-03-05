<?php
include 'controller.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $dni = $_POST['dni'];
    $email = $_POST['email'];
    $pswd = $_POST['pswd'];
    $rol = $_POST['rol'];

    if (editarEmpleado($id, $nombre, $apellidos, $dni, $email, $pswd, $rol)) {
        header("Location: gestion_empleados.php");
    } else {
        echo "Error al actualizar el empleado.";
    }
}

$id = $_GET['id'];
$empleado = obtenerEmpleadoPorId($id);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Empleado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="\sanitrans\css\Estilo.css">
</head>

<body>
    <header class="container h-25 mw-100 p-3">
        <h1 class="m-2">Plataforma web transporte sanitario</h1>
    </header>

    <div class="container mt-5">
        <h3>Editar Empleado</h3>
        <form method="POST">
            <input type="hidden" name="id" value="<?php echo $empleado['id']; ?>">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $empleado['nombre']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="apellidos" class="form-label">Apellidos</label>
                <input type="text" class="form-control" id="apellidos" name="apellidos" value="<?php echo $empleado['apellidos']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="dni" class="form-label">DNI</label>
                <input type="text" class="form-control" id="dni" name="dni" value="<?php echo $empleado['dni']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $empleado['email']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="pswd" class="form-label">Contraseña (dejar vacío para no cambiar)</label>
                <input type="password" class="form-control" id="pswd" name="pswd">
            </div>
            <div class="mb-3">
                <label for="rol" class="form-label">Rol</label>
                <select class="form-select" id="rol" name="rol" required>
                    <option value="empleado" <?php echo ($empleado['rol'] == 'empleado') ? 'selected' : ''; ?>>Empleado</option>
                    <option value="admin" <?php echo ($empleado['rol'] == 'admin') ? 'selected' : ''; ?>>Administrador</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary mb-3">Guardar Cambios</button>
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
</body>


</html>