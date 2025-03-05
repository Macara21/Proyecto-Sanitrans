<?php
session_start();
require 'controller.php';

// Verificar si el usuario está logueado y es administrador
if (!isset($_SESSION["usuario_id"]) || $_SESSION["rol"] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Verificar si se ha proporcionado un ID válido
if (!isset($_GET['id'])) {
    header("Location: asistencias_terceros.php");
    exit();
}

$id = $_GET['id'];

// Obtener los datos del parte de asistencia usando la función del controlador
$asistencia = obtenerParteAsistenciaPorId($id);

// Si no se encuentra el parte, redirigir
if (!$asistencia) {
    header("Location: asistencias_terceros.php");
    exit();
}

// Si se envía el formulario de edición
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $datos_principales = [
        'id' => $id,
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

    // Detalles según el tipo de incidente
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
                'nombre_asegurado_contrario' => $_POST['nombre_asegurado_contrario'] ?? null,
                'matricula_vehiculo_contrario' => $_POST['matricula_vehiculo_contrario'] ?? null,
                'marca_vehiculo_contrario' => $_POST['marca_vehiculo_contrario'] ?? null,
                'aseguradora_contrario' => $_POST['aseguradora_contrario'] ?? null,
                'numero_poliza_contrario' => $_POST['numero_poliza_contrario'] ?? null
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

    // Actualizar el parte de asistencia
    $resultado = actualizarParteAsistencia($datos_principales);
    if ($resultado['success']) {
        header("Location: asistencias_terceros.php?parte_actualizado=1");
        exit();
    } else {
        $error = "Error al actualizar el parte: " . $resultado['error'];
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Parte de Asistencia - Sanitrans</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="\sanitrans\css\Estilo.css">
</head>

<body>
    <header class="container h-25 mw-100 p-3">
        <h1 class="m-2">Plataforma web transporte sanitario</h1>
    </header>

    <div class="container mt-5">
        <h1 class="mb-4">Editar Parte de Asistencia</h1>

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
                            <input type="date" class="form-control" name="fecha_servicio" value="<?php echo htmlspecialchars($asistencia['fecha_servicio']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Hora del Servicio</label>
                            <input type="time" class="form-control" name="hora_servicio" value="<?php echo htmlspecialchars($asistencia['hora_servicio']); ?>" required>
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
                            <input type="text" class="form-control" name="nombre_paciente" value="<?php echo htmlspecialchars($asistencia['nombre_paciente']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Apellidos</label>
                            <input type="text" class="form-control" name="apellidos_paciente" value="<?php echo htmlspecialchars($asistencia['apellidos_paciente']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Teléfono</label>
                            <input type="tel" class="form-control" name="telefono_paciente" value="<?php echo htmlspecialchars($asistencia['telefono_paciente']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">DNI/NIE</label>
                            <input type="text" class="form-control" name="dni_paciente" value="<?php echo htmlspecialchars($asistencia['dni_paciente']); ?>" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Domicilio</label>
                            <input type="text" class="form-control" name="domicilio_paciente" value="<?php echo htmlspecialchars($asistencia['domicilio_paciente']); ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Población</label>
                            <input type="text" class="form-control" name="poblacion_paciente" value="<?php echo htmlspecialchars($asistencia['poblacion_paciente']); ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Provincia</label>
                            <input type="text" class="form-control" name="provincia_paciente" value="<?php echo htmlspecialchars($asistencia['provincia_paciente']); ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Código Postal</label>
                            <input type="text" class="form-control" name="codigo_postal" value="<?php echo htmlspecialchars($asistencia['codigo_postal']); ?>" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tipo de asistencia -->
            <div class="card mb-4">
                <div class="card-header">Tipo de Asistencia</div>
                <div class="card-body">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tipo_asistencia" value="SVA" <?php echo ($asistencia['tipo_asistencia'] === 'SVA') ? 'checked' : ''; ?> required>
                        <label class="form-check-label">Asistencia por SVA</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tipo_asistencia" value="SVB" <?php echo ($asistencia['tipo_asistencia'] === 'SVB') ? 'checked' : ''; ?>>
                        <label class="form-check-label">Asistencia y traslado por SVB</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tipo_asistencia" value="Convencional" <?php echo ($asistencia['tipo_asistencia'] === 'Convencional') ? 'checked' : ''; ?>>
                        <label class="form-check-label">Asistencia y traslado por Convencional</label>
                    </div>
                </div>
            </div>

            <!-- Diagnóstico -->
            <div class="mb-4">
                <label class="form-label">Diagnóstico</label>
                <textarea class="form-control" name="diagnostico" rows="3" required><?php echo htmlspecialchars($asistencia['diagnostico']); ?></textarea>
            </div>

            <!-- Tipo de incidente -->
            <div class="card mb-4">
                <div class="card-header">Tipo de Incidente: <?php echo htmlspecialchars($asistencia['tipo_incidente']); ?></div>
                <div class="card-body">
                    <div class="form-check"hidden>
                        <input class="form-check-input" type="radio" name="tipo_incidente" value="AccidenteTrafico" <?php echo ($asistencia['tipo_incidente'] === 'AccidenteTrafico') ? 'checked' : ''; ?> onclick="mostrarSeccion('trafico')">
                        <label class="form-check-label">Accidente de Tráfico</label>
                    </div>
                    <div class="form-check"hidden>
                        <input class="form-check-input" type="radio" name="tipo_incidente" value="AccidenteLaboral" <?php echo ($asistencia['tipo_incidente'] === 'AccidenteLaboral') ? 'checked' : ''; ?> onclick="mostrarSeccion('laboral')">
                        <label class="form-check-label">Accidente Laboral</label>
                    </div>
                    <div class="form-check"hidden>
                        <input class="form-check-input" type="radio" name="tipo_incidente" value="CompaniaPrivada" <?php echo ($asistencia['tipo_incidente'] === 'CompaniaPrivada') ? 'checked' : ''; ?> onclick="mostrarSeccion('compania')">
                        <label class="form-check-label">Compañías Privadas</label>
                    </div>
                    <div class="form-check"hidden>
                        <input class="form-check-input" type="radio" name="tipo_incidente" value="Extranjero" <?php echo ($asistencia['tipo_incidente'] === 'Extranjero') ? 'checked' : ''; ?> onclick="mostrarSeccion('extranjero')">
                        <label class="form-check-label">Extranjero</label>
                    </div>

                    <!-- Secciones condicionales -->
                    <div id="seccion-trafico" class="seccion-condicional mt-3" style="<?php echo ($asistencia['tipo_incidente'] === 'AccidenteTrafico') ? 'display: block;' : 'display: none;'; ?>">
                        <div class="card">
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Rol del Paciente</label>
                                    <select class="form-select" name="rol">
                                        <option value="Conductor" <?php echo ($asistencia['rol'] ?? '' === 'Conductor') ? 'selected' : ''; ?>>Conductor</option>
                                        <option value="Ocupante" <?php echo ($asistencia['rol'] ?? '' === 'Ocupante') ? 'selected' : ''; ?>>Ocupante</option>
                                        <option value="Peaton" <?php echo ($asistencia['rol'] ?? '' === 'Peaton') ? 'selected' : ''; ?>>Peaton</option>
                                        <option value="Motorista" <?php echo ($asistencia['rol'] ?? '' === 'Motorista') ? 'selected' : ''; ?>>Motorista</option>
                                        <option value="Ciclista" <?php echo ($asistencia['rol'] ?? '' === 'Ciclista') ? 'selected' : ''; ?>>Ciclista</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Lugar del Accidente</label>
                                    <input type="text" class="form-control" name="lugar_accidente" value="<?php echo htmlspecialchars($asistencia['lugar_accidente'] ?? ''); ?>">
                                </div>
                                <h6 class="mt-4">Vehículo Asegurado</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="nombre_asegurado" placeholder="Nombre del Asegurado" value="<?php echo htmlspecialchars($asistencia['nombre_asegurado'] ?? ''); ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="matricula_vehiculo" placeholder="Matrícula" value="<?php echo htmlspecialchars($asistencia['matricula_vehiculo'] ?? ''); ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="marca_vehiculo" placeholder="Marca" value="<?php echo htmlspecialchars($asistencia['marca_vehiculo'] ?? ''); ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="aseguradora_vehiculo" placeholder="Aseguradora" value="<?php echo htmlspecialchars($asistencia['aseguradora_vehiculo'] ?? ''); ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="numero_poliza" placeholder="Número de Póliza" value="<?php echo htmlspecialchars($asistencia['numero_poliza'] ?? ''); ?>">
                                    </div>
                                </div>
                                <h6 class="mt-4">Vehículo Contrario</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="nombre_asegurado_contrario" placeholder="Nombre del Asegurado" value="<?php echo htmlspecialchars($asistencia['nombre_asegurado_contrario'] ?? ''); ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="matricula_vehiculo_contrario" placeholder="Matrícula" value="<?php echo htmlspecialchars($asistencia['matricula_vehiculo_contrario'] ?? ''); ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="marca_vehiculo_contrario" placeholder="Marca" value="<?php echo htmlspecialchars($asistencia['marca_vehiculo_contrario'] ?? ''); ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="aseguradora_contrario" placeholder="Aseguradora" value="<?php echo htmlspecialchars($asistencia['aseguradora_contrario'] ?? ''); ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="numero_poliza_contrario" placeholder="Número de Póliza" value="<?php echo htmlspecialchars($asistencia['numero_poliza_contrario'] ?? ''); ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="seccion-laboral" class="seccion-condicional mt-3" style="<?php echo ($asistencia['tipo_incidente'] === 'AccidenteLaboral') ? 'display: block;' : 'display: none;'; ?>">
                        <div class="card">
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Empresa</label>
                                    <input type="text" class="form-control" name="empresa" value="<?php echo htmlspecialchars($asistencia['empresa']); ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Mutua de Accidente de Trabajo</label>
                                    <input type="text" class="form-control" name="mutua" value="<?php echo htmlspecialchars($asistencia['mutua_accidente_trabajo']); ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="seccion-compania" class="seccion-condicional mt-3" style="<?php echo ($asistencia['tipo_incidente'] === 'CompaniaPrivada') ? 'display: block;' : 'display: none;'; ?>">
                        <div class="card">
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Aseguradora</label>
                                    <input type="text" class="form-control" name="aseguradora_compania" value="<?php echo htmlspecialchars($asistencia['aseguradora_compania']); ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Número de Póliza</label>
                                    <input type="text" class="form-control" name="numero_poliza_compania" value="<?php echo htmlspecialchars($asistencia['numero_poliza_compania']); ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="seccion-extranjero" class="seccion-condicional mt-3" style="<?php echo ($asistencia['tipo_incidente'] === 'Extranjero') ? 'display: block;' : 'display: none;'; ?>">
                        <div class="card">
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">País de Origen</label>
                                    <input type="text" class="form-control" name="pais_origen" value="<?php echo htmlspecialchars($asistencia['pais_origen']); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Guardar Cambios</button>
            <a href="asistencias_terceros.php" class="btn btn-secondary mt-3">Volver</a>
        </form>
    </div>

    <script src="../js/scripts.js"></script>
</body>

</html>