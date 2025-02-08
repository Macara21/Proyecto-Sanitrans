<?php
session_start();
require 'db.php';



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Buscar el usuario en la base de datos
    $stmt = $pdo->prepare("SELECT id, nombre, pswd, rol FROM empleados WHERE email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);


    if ($usuario && password_verify($password, $usuario["pswd"])) {
        $_SESSION["usuario_id"] = $usuario["id"];
        $_SESSION["nombre"] = $usuario["nombre"];
        $_SESSION["rol"] = $usuario["rol"];

        header("Location: dashboard.php");  // Redirigir al panel de control
        exit;
    } else {
        $error = "Correo o contraseña incorrectos.";
    }
}
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sanitrans</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="d-flex justify-content-center align-items-center vh-100">
    
    <div class="card p-4">
    <div class="logo">
    <img src="\img\LogoSanitrans2.png" alt="Logo Sanitrans" width="200" height="141"/>
    </div>
    <img src="\img\Sanitrans.png" alt="Texto Sanitrans" width="200" height="141"/>
        <h3 class="text-center">Iniciar Sesión</h3>
        <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
        <form method="POST">
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Contraseña</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Entrar</button>
        </form>
    </div>
</body>
</html>
