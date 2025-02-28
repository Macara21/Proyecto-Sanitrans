<?php
include 'controller.php'; // Asegúrate de incluir el archivo con las funciones

// Obtener todas las ambulancias
$ambulancias = obtenerAmbulancias();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Ambulancias</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/sanitrans\css\Estilo.css">

</head>

<body>
    <header class="container h-25 mw-100 p-3">
        <h1 class="m-2">Plataforma web transporte sanitario</h1>
    </header>

    <div class="container mt-5">
        <h3>Gestión de Ambulancias</h3>
        <a href="crear_ambulancia.php" class="btn btn-success mb-3">Alta nueva ambulancia</a>
        <a href="panel_admin.php" class="btn btn-secondary mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8" />
            </svg>
            Volver al Panel
        </a>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Matrícula</th>
                    <th>Tipo</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Verificar si hay ambulancias
                if (count($ambulancias) > 0) {
                    foreach ($ambulancias as $ambulancia) {
                        echo "<tr>
                                <td>{$ambulancia['id']}</td>
                                <td>{$ambulancia['matricula']}</td>
                                <td>{$ambulancia['tipo']}</td>
                                <td>{$ambulancia['estado']}</td>
                                <td>
                                    <a href='editar_ambulancia.php?id={$ambulancia['id']}' class='btn btn-warning'>Editar</a>
                                    <a href='eliminar_ambulancia.php?id={$ambulancia['id']}' class='btn btn-danger'>Eliminar</a>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No hay ambulancias registradas</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>