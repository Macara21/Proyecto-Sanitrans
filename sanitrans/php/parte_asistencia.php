<?php
session_start();
require 'controller.php';

if (!isset($_SESSION["usuario_id"]) || $_SESSION["rol"] !== 'empleado') {
    header("Location: login.php");
    exit();
}

$empleado_id = $_SESSION["usuario_id"];
$turno_activo = obtenerTurnoActivo($empleado_id);

if (!$turno_activo) {
    header("Location: panel_empleado.php?error=no_turno");
    exit();
}

// if (tieneParteAsistencia($turno_activo['id'])) {
//     header("Location: panel_empleado.php?error=parte_existente");
//     exit();
// }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $datos_principales = [
        'turno_id' => $turno_activo['id'],
        'fecha_servicio' => $_POST['fecha_servicio'],
        'hora_servicio' => $_POST['hora_servicio'],
        'nombre_paciente' => htmlspecialchars($_POST['nombre_paciente']),
        'apellidos_paciente' => htmlspecialchars($_POST['apellidos_paciente']),
        'telefono_paciente' => htmlspecialchars($_POST['telefono_paciente']),
        'dni_paciente' => htmlspecialchars($_POST['dni_paciente']),
        'domicilio_paciente' => htmlspecialchars($_POST['domicilio_paciente']),
        'poblacion_paciente' => htmlspecialchars($_POST['poblacion_paciente']),
        'provincia_paciente' => htmlspecialchars($_POST['provincia_paciente']),
        'codigo_postal' => htmlspecialchars($_POST['codigo_postal']),
        'tipo_asistencia' => $_POST['tipo_asistencia'],
        'diagnostico' => htmlspecialchars($_POST['diagnostico']),
        'tipo_incidente' => $_POST['tipo_incidente'],
        'detalles_incidente' => []
    ];

    switch ($_POST['tipo_incidente']) {
        case 'AccidenteTrafico':
            $datos_principales['detalles_incidente'] = [
                'tipo_incidente' => 'AccidenteTrafico',
                'rol' => $_POST['rol'],
                'lugar_accidente' => $_POST['lugar_accidente'],
                'nombre_asegurado' => $_POST['nombre_asegurado'],
                'matricula_vehiculo' => $_POST['matricula_vehiculo'],
                'marca_vehiculo' => $_POST['marca_vehiculo'],
                'aseguradora_vehiculo' => $_POST['aseguradora_vehiculo'],
                'numero_poliza' => $_POST['numero_poliza'],
                'nombre_asegurado_contrario' => $_POST['nombre_asegurado_contrario'] ?? '',
                'matricula_vehiculo_contrario' => $_POST['matricula_vehiculo_contrario'] ?? '',
                'marca_vehiculo_contrario' => $_POST['marca_vehiculo_contrario'] ?? '',
                'aseguradora_contrario' => $_POST['aseguradora_contrario'] ?? '',
                'numero_poliza_contrario' => $_POST['numero_poliza_contrario'] ?? ''
            ];
            break;

        case 'AccidenteLaboral':
            $datos_principales['detalles_incidente'] = [
                'tipo_incidente' => 'AccidenteLaboral',
                'empresa' => $_POST['empresa'],
                'mutua_accidente_trabajo' => $_POST['mutua']
            ];
            break;

        case 'CompaniaPrivada':
            $datos_principales['detalles_incidente'] = [
                'tipo_incidente' => 'CompaniaPrivada',
                'aseguradora_compania' => $_POST['aseguradora_compania'],
                'numero_poliza_compania' => $_POST['numero_poliza_compania']
            ];
            break;

        case 'Extranjero':
            $datos_principales['detalles_incidente'] = [
                'tipo_incidente' => 'Extranjero',
                'pais_origen' => $_POST['pais_origen']
            ];
            break;
    }

    if (registrarParteAsistencia($datos_principales)) {
        header("Location: panel_empleado.php?parte_registrado=1");
        exit();
    } else {
        $error = "Error al registrar el parte";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parte de Asistencia - Sanitrans</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Parte de Asistencia Facturable</h1>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" id="form-parte">
            <!-- Datos básicos -->
            <div class="card mb-4">
                <div class="card-header">Datos Generales</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Fecha del Servicio</label>
                            <input type="date" class="form-control" name="fecha_servicio" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Hora del Servicio</label>
                            <input type="time" class="form-control" name="hora_servicio" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Datos del paciente -->
            <div class="card mb-4">
                <div class="card-header">Datos del Paciente</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Nombre</label>
                            <input type="text" class="form-control" name="nombre_paciente" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Apellidos</label>
                            <input type="text" class="form-control" name="apellidos_paciente" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Teléfono</label>
                            <input type="tel" class="form-control" name="telefono_paciente" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">DNI/NIE</label>
                            <input type="text" class="form-control" name="dni_paciente" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Domicilio</label>
                            <input type="text" class="form-control" name="domicilio_paciente" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Población</label>
                            <input type="text" class="form-control" name="poblacion_paciente" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Provincia</label>
                            <input type="text" class="form-control" name="provincia_paciente" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Código Postal</label>
                            <input type="text" class="form-control" name="codigo_postal" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tipo de asistencia -->
            <div class="card mb-4">
                <div class="card-header">Tipo de Asistencia</div>
                <div class="card-body">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tipo_asistencia" value="SVA" required>
                        <label class="form-check-label">Asistencia por SVA</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tipo_asistencia" value="SVB">
                        <label class="form-check-label">Asistencia y traslado por SVB</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tipo_asistencia" value="Convencional">
                        <label class="form-check-label">Asistencia y traslado por Convencional</label>
                    </div>
                </div>
            </div>

            <!-- Diagnóstico -->
            <div class="mb-4">
                <label class="form-label">Diagnóstico</label>
                <textarea class="form-control" name="diagnostico" rows="3" required></textarea>
            </div>

            <!-- Tipo de incidente -->
            <div class="card mb-4">
                <div class="card-header">Tipo de Incidente</div>
                <div class="card-body">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tipo_incidente" value="AccidenteTrafico" required onclick="mostrarSeccion('trafico')">
                        <label class="form-check-label">Accidente de Tráfico</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tipo_incidente" value="AccidenteLaboral" onclick="mostrarSeccion('laboral')">
                        <label class="form-check-label">Accidente Laboral</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tipo_incidente" value="CompaniaPrivada" onclick="mostrarSeccion('compania')">
                        <label class="form-check-label">Compañías Privadas</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tipo_incidente" value="Extranjero" onclick="mostrarSeccion('extranjero')">
                        <label class="form-check-label">Extranjero</label>
                    </div>

                    <!-- Secciones condicionales -->
                    <div id="seccion-trafico" class="seccion-condicional mt-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Rol del Paciente</label>
                                    <select class="form-select" name="rol">
                                        <option value="Conductor">Conductor</option>
                                        <option value="Ocupante">Ocupante</option>
                                        <option value="Peaton">Peaton</option>
                                        <option value="Motorista">Motorista</option>
                                        <option value="Ciclista">Ciclista</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Lugar del Accidente</label>
                                    <input type="text" class="form-control" name="lugar_accidente">
                                </div>
                                <h6 class="mt-4">Vehículo Asegurado</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="nombre_asegurado" placeholder="Nombre del Asegurado">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="matricula_vehiculo" placeholder="Matrícula">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="marca_vehiculo" placeholder="Marca">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="aseguradora_vehiculo" placeholder="Aseguradora">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="numero_poliza" placeholder="Número de Póliza">
                                    </div>
                                </div>
                                <h6 class="mt-4">Vehículo Contrario</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="nombre_asegurado_contrario" placeholder="Nombre del Asegurado">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="matricula_vehiculo_contrario" placeholder="Matrícula">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="marca_vehiculo_contrario" placeholder="Marca">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="aseguradora_contrario" placeholder="Aseguradora">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="numero_poliza_contrario" placeholder="Número de Póliza">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="seccion-laboral" class="seccion-condicional mt-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Empresa</label>
                                    <input type="text" class="form-control" name="empresa">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Mutua de Accidente de Trabajo</label>
                                    <input type="text" class="form-control" name="mutua">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="seccion-compania" class="seccion-condicional mt-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Aseguradora</label>
                                    <input type="text" class="form-control" name="aseguradora_compania">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Número de Póliza</label>
                                    <input type="text" class="form-control" name="numero_poliza_compania">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="seccion-extranjero" class="seccion-condicional mt-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">País de Origen</label>
                                    <input type="text" class="form-control" name="pais_origen">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Enviar Parte</button>
        </form>
        <a href="panel_empleado.php" class="btn btn-secondary mt-3"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-return-left" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M14.5 1.5a.5.5 0 0 1 .5.5v4.8a2.5 2.5 0 0 1-2.5 2.5H2.707l3.347 3.346a.5.5 0 0 1-.708.708l-4.2-4.2a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 8.3H12.5A1.5 1.5 0 0 0 14 6.8V2a.5.5 0 0 1 .5-.5" />
            </svg>&nbsp;Volver al Panel</a>
    </div>

    <script src="../js/scripts.js"></script>
</body>
</html>