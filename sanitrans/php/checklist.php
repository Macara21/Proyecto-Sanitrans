<?php
session_start();
require 'controller.php';

if (!isset($_SESSION["usuario_id"]) || $_SESSION["rol"] !== 'empleado') {
    header("Location: login.php");
    exit();
}

$empleado_id = $_SESSION["usuario_id"];

// Obtener el turno activo del empleado
$turno = obtenerTurnoActivo($empleado_id);

if (!$turno) {
    echo "No tienes un turno activo.";
    exit();
}

// Verificar si ya existe un checklist para este turno
if (tieneChecklist($turno['id'])) {
    header("Location: panel_empleado.php?error=checklist_existente");
    exit();
}

// Procesar el formulario de checklist
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $datos_checklist = [
        'turno_id' => $turno['id'],

        //Con condicionales ternarios
        'ruedas' => isset($_POST['ruedas']) ? 1 : 0,
        'aceite' => isset($_POST['aceite']) ? 1 : 0,
        'anticongelante' => isset($_POST['anticongelante']) ? 1 : 0,
        'golpes_exteriores' => isset($_POST['golpes_exteriores']) ? 1 : 0,
        'luces' => isset($_POST['luces']) ? 1 : 0,
        'sirena' => isset($_POST['sirena']) ? 1 : 0,
        'limpieza' => isset($_POST['limpieza']) ? 1 : 0,
        'camilla' => isset($_POST['camilla']) ? 1 : 0,
        'ferulas' => isset($_POST['ferulas']) ? 1 : 0,
        'ambu' => isset($_POST['ambu']) ? 1 : 0,
        'desfibrilador' => isset($_POST['desfibrilador']) ? 1 : 0,
        'camilla_pala' => isset($_POST['camilla_pala']) ? 1 : 0,
        'tablero_espinal' => isset($_POST['tablero_espinal']) ? 1 : 0,
        'collarines' => isset($_POST['collarines']) ? 1 : 0,
        'guedels' => isset($_POST['guedels']) ? 1 : 0,
        'sueros' => isset($_POST['sueros']) ? 1 : 0,
        'silla_evacuacion' => isset($_POST['silla_evacuacion']) ? 1 : 0,
        'extintor' => isset($_POST['extintor']) ? 1 : 0,
    ];

    if (registrarChecklist($datos_checklist)) {
        header("Location: panel_empleado.php"); // Redirigir al panel
        exit();
    } else {
        echo "Error al registrar el checklist.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checklist - Sanitrans</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h1>Checklist de la Ambulancia</h1>

        <div class="card mb-1">
            <div class="card-body">
                <form method="POST">
                    <div class="row">
                        <!-- Lista de elementos con switches -->
                        <div class="col-6">
                            <div class="mb-3 form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="ruedas" name="ruedas">
                                <label class="form-check-label" for="ruedas">Ruedas</label>
                            </div>
                            <div class="mb-3 form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="aceite" name="aceite">
                                <label class="form-check-label" for="aceite">Aceite</label>
                            </div>
                            <div class="mb-3 form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="anticongelante" name="anticongelante">
                                <label class="form-check-label" for="anticongelante">Anticongelante</label>
                            </div>
                            <div class="mb-3 form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="golpes_exteriores" name="golpes_exteriores">
                                <label class="form-check-label" for="golpes_exteriores">Golpes exteriores</label>
                            </div>
                            <div class="mb-3 form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="luces" name="luces">
                                <label class="form-check-label" for="luces">Luces</label>
                            </div>
                            <div class="mb-3 form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="sirena" name="sirena">
                                <label class="form-check-label" for="sirena">Sirena</label>
                            </div>
                            <div class="mb-3 form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="limpieza" name="limpieza">
                                <label class="form-check-label" for="limpieza">Limpieza</label>
                            </div>
                            <div class="mb-3 form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="camilla" name="camilla">
                                <label class="form-check-label" for="camilla">Camilla</label>
                            </div>
                            <div class="mb-3 form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="ferulas" name="ferulas">
                                <label class="form-check-label" for="ferulas">Férulas</label>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3 form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="ambu" name="ambu">
                                <label class="form-check-label" for="ambu">Ambú</label>
                            </div>
                            <div class="mb-3 form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="desfibrilador" name="desfibrilador">
                                <label class="form-check-label" for="desfibrilador">Desfibrilador</label>
                            </div>
                            <div class="mb-3 form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="camilla_pala" name="camilla_pala">
                                <label class="form-check-label" for="camilla_pala">Camilla de pala</label>
                            </div>
                            <div class="mb-3 form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="tablero_espinal" name="tablero_espinal">
                                <label class="form-check-label" for="tablero_espinal">Tablero espinal</label>
                            </div>
                            <div class="mb-3 form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="collarines" name="collarines">
                                <label class="form-check-label" for="collarines">Collarines</label>
                            </div>
                            <div class="mb-3 form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="guedels" name="guedels">
                                <label class="form-check-label" for="guedels">Guedels</label>
                            </div>
                            <div class="mb-3 form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="sueros" name="sueros">
                                <label class="form-check-label" for="sueros">Sueros</label>
                            </div>
                            <div class="mb-3 form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="silla_evacuacion" name="silla_evacuacion">
                                <label class="form-check-label" for="silla_evacuacion">Silla de evacuación</label>
                            </div>
                            <div class="mb-3 form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="extintor" name="extintor">
                                <label class="form-check-label" for="extintor">Extintor</label>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-ui-checks" viewBox="0 0 16 16">
                            <path d="M7 2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5zM2 1a2 2 0 0 0-2 2v2a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2zm0 8a2 2 0 0 0-2 2v2a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2v-2a2 2 0 0 0-2-2zm.854-3.646a.5.5 0 0 1-.708 0l-1-1a.5.5 0 1 1 .708-.708l.646.647 1.646-1.647a.5.5 0 1 1 .708.708zm0 8a.5.5 0 0 1-.708 0l-1-1a.5.5 0 0 1 .708-.708l.646.647 1.646-1.647a.5.5 0 0 1 .708.708zM7 10.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5zm0-5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m0 8a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5" />
                        </svg>&nbsp;Enviar Checklist</button>
                </form>
            </div>
        </div>
        <a href="panel_empleado.php" class="btn btn-secondary mt-3"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-return-left" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M14.5 1.5a.5.5 0 0 1 .5.5v4.8a2.5 2.5 0 0 1-2.5 2.5H2.707l3.347 3.346a.5.5 0 0 1-.708.708l-4.2-4.2a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 8.3H12.5A1.5 1.5 0 0 0 14 6.8V2a.5.5 0 0 1 .5-.5" />
            </svg>&nbsp;Volver al Panel</a>
    </div>
</body>

</html>