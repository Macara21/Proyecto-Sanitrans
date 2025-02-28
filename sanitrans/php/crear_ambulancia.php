<?php
include 'controller.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matricula = $_POST['matricula'];
    $tipo = $_POST['tipo'];
    $estado = $_POST['estado'];

    if (crearAmbulancia($matricula, $tipo, $estado)) {
        header("Location: gestion_ambulancias.php");
    } else {
        echo "Error al crear la ambulancia.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nueva Ambulancia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/sanitrans\css\Estilo.css">

</head>

<body>
    <header class="container h-25 mw-100 p-3">
        <h1 class="m-2">Plataforma web transporte sanitario</h1>
    </header>

    <div class="container mt-5">
        <h3>Alta nueva ambulancia</h3>
        <form method="POST">
            <div class="mb-3">
                <label for="matricula" class="form-label">Matrícula</label>
                <input type="text" class="form-control" id="matricula" name="matricula" required>
            </div>
            <div class="mb-3">
                <label for="tipo" class="form-label">Tipo</label>
                <select class="form-select" id="tipo" name="tipo" required>
                    <option value="SVA">SVA</option>
                    <option value="SVB">SVB</option>
                    <option value="Convencional">Convencional</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="estado" class="form-label">Estado</label>
                <select class="form-select" id="estado" name="estado" required>
                    <option value="Operativa">Operativa</option>
                    <option value="En mantenimiento">En mantenimiento</option>
                    <option value="Fuera de servicio">Fuera de servicio</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary mb-3">Alta Ambulancia</button>
            <a href="gestion_ambulancias.php" class="btn btn-secondary mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8" />
                </svg>
                Volver a gestión
            </a>
        </form>

    </div>
</body>

</html>