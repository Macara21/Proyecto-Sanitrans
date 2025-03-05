<?php
session_start();
require 'controller.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    $usuario = verificarCredenciales($email, $password);

    if ($usuario) {
        $_SESSION["usuario_id"] = $usuario["id"];
        $_SESSION["nombre"] = $usuario["nombre"];
        $_SESSION["rol"] = $usuario["rol"];

        header("Location: dashboard.php");  // Redirigir al panel de control
        exit();
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="\sanitrans\css\Estilo.css">
</head>

<body>
    <header>
        Plataforma web de transporte sanitario
    </header>

    <div class="d-flex justify-content-center align-items-center vh-30 mt-5">
        <div class="login card p-4">
            <div class="d-flex justify-content-center">
                <img src="/sanitrans/img/LogoSanitrans2.png" alt="Logo Sanitrans" width="180" height="121" />
            </div>
            <div class="mb-2 d-flex justify-content-center">
                <img src="/sanitrans/img/Sanitrans.png" alt="Texto Sanitrans" width="257" height="53" />
            </div>
            <h3 class="text-center">Credenciales</h3>
            <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
            <form method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-check" viewBox="0 0 16 16">
                            <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m1.679-4.493-1.335 2.226a.75.75 0 0 1-1.174.144l-.774-.773a.5.5 0 0 1 .708-.708l.547.548 1.17-1.951a.5.5 0 1 1 .858.514M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0M8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4" />
                            <path d="M8.256 14a4.5 4.5 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10q.39 0 .74.025c.226-.341.496-.65.804-.918Q8.844 9.002 8 9c-5 0-6 3-6 4s1 1 1 1z" />
                        </svg>
                        Email
                    </label>
                    <input type="email" name="email" id="email" class="form-control" required>
                </div>
                <div class="mb-3 position-relative">
                    <label for="password" class="form-label">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-lock" viewBox="0 0 16 16">
                            <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2m3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2M5 8h6a1 1 0 0 1 1 1v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1" />
                        </svg>
                        Contraseña
                    </label>
                    <input type="password" name="password" id="password" class="form-control" required>
                    <span class="password-toggle" onclick="togglePassword('password', 'eyeIcon')">
                        <i id="eyeIcon" class="bi bi-eye"></i> <!-- Ícono de ojo -->
                    </span>
                </div>
                <button type="submit" class="btn btn-primary w-100">Entrar</button>
            </form>
        </div>
    </div>

    <footer>
        Proyecto desarrollo de aplicaciones web<br>
        Mario Carmona Ramos
    </footer>

    <script src="../js/scripts.js"></script>
</body>

</html>