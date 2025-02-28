<?php
// Habilitar la visualización de errores (solo para desarrollo)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Función para conectar a la base de datos
function conectarDB()
{
    $host = "localhost";
    $usuario = "root";  // Cambia esto si tienes un usuario distinto
    $password = "";     // Pon la contraseña si la has configurado en XAMPP
    $base_de_datos = "sanitrans";

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$base_de_datos;charset=utf8", $usuario, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Error en la conexión: " . $e->getMessage());
    }
}

// Función para buscar un usuario por email
function buscarUsuarioPorEmail($email)
{
    $pdo = conectarDB();
    $stmt = $pdo->prepare("SELECT id, nombre, pswd, rol FROM empleados WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Función para verificar las credenciales del usuario
function verificarCredenciales($email, $password)
{
    $usuario = buscarUsuarioPorEmail($email);
    if ($usuario && password_verify($password, $usuario["pswd"])) {
        return $usuario;
    }
    return false;
}

// Función para iniciar un turno
function iniciarTurno($empleado_id, $matricula)
{
    $pdo = conectarDB();

    // Verificar si la ambulancia existe
    $stmt = $pdo->prepare("SELECT id FROM ambulancias WHERE matricula = :matricula");
    $stmt->execute(['matricula' => $matricula]);
    $ambulancia = $stmt->fetch();

    if (!$ambulancia) {
        return false; // La matrícula no existe
    }

    // Registrar el inicio de turno
    $inicio_turno = date('Y-m-d H:i:s'); // Fecha y hora actual
    $stmt = $pdo->prepare("INSERT INTO turnos (empleado_id, ambulancia_id, inicio_turno) VALUES (:empleado_id, :ambulancia_id, :inicio_turno)");
    $stmt->execute([
        'empleado_id' => $empleado_id,
        'ambulancia_id' => $ambulancia['id'],
        'inicio_turno' => $inicio_turno
    ]);

    return true;
}

// Función para registrar combustible
function registrarCombustible($turno_id, $litros, $coste)
{
    $pdo = conectarDB();
    $fecha_repostaje = date('Y-m-d H:i:s'); // Fecha y hora actual

    $stmt = $pdo->prepare("INSERT INTO combustible (turno_id, litros, coste, fecha) VALUES (:turno_id, :litros, :coste, :fecha)");
    $stmt->execute([
        'turno_id' => $turno_id,
        'litros' => $litros,
        'coste' => $coste,
        'fecha' => $fecha_repostaje
    ]);

    return true;
}

// Función para registrar una incidencia
function registrarIncidencia($turno_id, $descripcion)
{
    $pdo = conectarDB();
    $fecha_incidencia = date('Y-m-d H:i:s'); // Fecha y hora actual

    $stmt = $pdo->prepare("INSERT INTO incidencias (turno_id, descripcion, fecha) VALUES (:turno_id, :descripcion, :fecha)");
    $stmt->execute([
        'turno_id' => $turno_id,
        'descripcion' => $descripcion,
        'fecha' => $fecha_incidencia
    ]);

    return true;
}

// Función para obtener el turno activo de un empleado
function obtenerTurnoActivo($empleado_id)
{
    $pdo = conectarDB();
    $stmt = $pdo->prepare("SELECT id FROM turnos WHERE empleado_id = :empleado_id AND fin_turno IS NULL");
    $stmt->execute(['empleado_id' => $empleado_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

//función para verificar si el empleado ya tiene un turno activo
function tieneTurnoActivo($empleado_id)
{
    $pdo = conectarDB();
    $stmt = $pdo->prepare("SELECT id FROM turnos WHERE empleado_id = :empleado_id AND fin_turno IS NULL");
    $stmt->execute(['empleado_id' => $empleado_id]);
    return $stmt->fetch() !== false;
}

// Función para cerrar sesión y registrar el fin de turno
function cerrarSesion($empleado_id)
{
    $pdo = conectarDB();
    $fin_turno = date('Y-m-d H:i:s'); // Fecha y hora actual

    $stmt = $pdo->prepare("UPDATE turnos SET fin_turno = :fin_turno WHERE empleado_id = :empleado_id AND fin_turno IS NULL");
    $stmt->execute([
        'fin_turno' => $fin_turno,
        'empleado_id' => $empleado_id
    ]);

    session_destroy(); // Destruir la sesión
    return true; //Cierre de sesión exitoso
}

function registrarChecklist($datos)
{
    $pdo = conectarDB();

    $stmt = $pdo->prepare("
        INSERT INTO checklist (
            turno_id, ruedas, aceite, anticongelante, golpes_exteriores, luces, sirena, limpieza, camilla, ferulas, ambu, desfibrilador, camilla_pala, tablero_espinal, collarines, guedels, sueros, silla_evacuacion, extintor
        ) VALUES (
            :turno_id, :ruedas, :aceite, :anticongelante, :golpes_exteriores, :luces, :sirena, :limpieza, :camilla, :ferulas, :ambu, :desfibrilador, :camilla_pala, :tablero_espinal, :collarines, :guedels, :sueros, :silla_evacuacion, :extintor
        )
    ");

    return $stmt->execute($datos);
}

//Ya se realizó un checkList
function tieneChecklist($turno_id)
{
    $pdo = conectarDB();
    $stmt = $pdo->prepare("SELECT id FROM checklist WHERE turno_id = :turno_id");
    $stmt->execute(['turno_id' => $turno_id]);
    return $stmt->fetch() !== false;
}

// Función para obtener la matricula
function obtenerMatriculasDisponibles()
{
    $pdo = conectarDB();
    $stmt = $pdo->query("SELECT matricula FROM ambulancias WHERE estado = 'Operativa'");
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

//Obtener empleados
function obtenerEmpleados() {
    $pdo = conectarDB();
    $stmt = $pdo->query("
        SELECT id, nombre, apellidos 
        FROM empleados 
        ORDER BY apellidos ASC
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function registrarParteAsistencia($datos) {
    $pdo = conectarDB();

    try {
        $pdo->beginTransaction();

        // Insertar en partes_asistencia
        $stmt = $pdo->prepare("
            INSERT INTO partes_asistencia (
                turno_id, fecha_servicio, hora_servicio, nombre_paciente, apellidos_paciente,
                telefono_paciente, dni_paciente, domicilio_paciente, poblacion_paciente,
                provincia_paciente, codigo_postal, tipo_asistencia, diagnostico, tipo_incidente
            ) VALUES (
                :turno_id, :fecha_servicio, :hora_servicio, :nombre_paciente, :apellidos_paciente,
                :telefono_paciente, :dni_paciente, :domicilio_paciente, :poblacion_paciente,
                :provincia_paciente, :codigo_postal, :tipo_asistencia, :diagnostico, :tipo_incidente
            )
        ");

        // Ejecutar la inserción de los datos principales
        $stmt->execute([
            'turno_id' => $datos['turno_id'],
            'fecha_servicio' => $datos['fecha_servicio'],
            'hora_servicio' => $datos['hora_servicio'],
            'nombre_paciente' => $datos['nombre_paciente'],
            'apellidos_paciente' => $datos['apellidos_paciente'],
            'telefono_paciente' => $datos['telefono_paciente'],
            'dni_paciente' => $datos['dni_paciente'],
            'domicilio_paciente' => $datos['domicilio_paciente'],
            'poblacion_paciente' => $datos['poblacion_paciente'],
            'provincia_paciente' => $datos['provincia_paciente'],
            'codigo_postal' => $datos['codigo_postal'],
            'tipo_asistencia' => $datos['tipo_asistencia'],
            'diagnostico' => $datos['diagnostico'],
            'tipo_incidente' => $datos['tipo_incidente']
        ]);

        // Obtener el ID del parte recién insertado
        $parte_id = $pdo->lastInsertId();

        // Insertar en detalles_incidente
        $detalles = $datos['detalles_incidente'];
        $detalles['parte_id'] = $parte_id;

        $stmtDetalle = $pdo->prepare("
            INSERT INTO detalles_incidente (
                parte_id, tipo_incidente, rol, lugar_accidente, nombre_asegurado,
                matricula_vehiculo, marca_vehiculo, aseguradora_vehiculo, numero_poliza,
                nombre_asegurado_contrario, matricula_vehiculo_contrario, marca_vehiculo_contrario,
                aseguradora_contrario, numero_poliza_contrario, empresa, mutua_accidente_trabajo,
                aseguradora_compania, numero_poliza_compania, pais_origen
            ) VALUES (
                :parte_id, :tipo_incidente, :rol, :lugar_accidente, :nombre_asegurado,
                :matricula_vehiculo, :marca_vehiculo, :aseguradora_vehiculo, :numero_poliza,
                :nombre_asegurado_contrario, :matricula_vehiculo_contrario, :marca_vehiculo_contrario,
                :aseguradora_contrario, :numero_poliza_contrario, :empresa, :mutua_accidente_trabajo,
                :aseguradora_compania, :numero_poliza_compania, :pais_origen
            )
        ");

        // Ejecutar la inserción de los detalles del incidente
        $stmtDetalle->execute([
            'parte_id' => $detalles['parte_id'],
            'tipo_incidente' => $detalles['tipo_incidente'],
            'rol' => $detalles['rol'] ?? null,
            'lugar_accidente' => $detalles['lugar_accidente'] ?? null,
            'nombre_asegurado' => $detalles['nombre_asegurado'] ?? null,
            'matricula_vehiculo' => $detalles['matricula_vehiculo'] ?? null,
            'marca_vehiculo' => $detalles['marca_vehiculo'] ?? null,
            'aseguradora_vehiculo' => $detalles['aseguradora_vehiculo'] ?? null,
            'numero_poliza' => $detalles['numero_poliza'] ?? null,
            'nombre_asegurado_contrario' => $detalles['nombre_asegurado_contrario'] ?? null,
            'matricula_vehiculo_contrario' => $detalles['matricula_vehiculo_contrario'] ?? null,
            'marca_vehiculo_contrario' => $detalles['marca_vehiculo_contrario'] ?? null,
            'aseguradora_contrario' => $detalles['aseguradora_contrario'] ?? null,
            'numero_poliza_contrario' => $detalles['numero_poliza_contrario'] ?? null,
            'empresa' => $detalles['empresa'] ?? null,
            'mutua_accidente_trabajo' => $detalles['mutua_accidente_trabajo'] ?? null,
            'aseguradora_compania' => $detalles['aseguradora_compania'] ?? null,
            'numero_poliza_compania' => $detalles['numero_poliza_compania'] ?? null,
            'pais_origen' => $detalles['pais_origen'] ?? null
        ]);

        $pdo->commit();
        return $parte_id;
    } catch (Exception $e) {
        $pdo->rollBack();
        error_log("Error al registrar parte: " . $e->getMessage());
        return false;
    }
}

function tieneParteAsistencia($turno_id) {
    $pdo = conectarDB();
    $stmt = $pdo->prepare("SELECT id FROM partes_asistencia WHERE turno_id = :turno_id");
    $stmt->execute(['turno_id' => $turno_id]);
    return $stmt->fetch() !== false;
}


// Obtener turnos de UN SOLO empleado
function obtenerTurnosProgramados($empleado_id) {
    $pdo = conectarDB();
    $stmt = $pdo->prepare("
        SELECT 
            tp.id, 
            tp.fecha, 
            tp.hora_inicio, 
            tp.hora_fin, 
            tp.descripcion,
            e.nombre,
            e.apellidos
        FROM turnos_programados tp
        JOIN empleados e ON tp.empleado_id = e.id
        WHERE tp.empleado_id = :empleado_id
        ORDER BY tp.fecha ASC
    ");
    $stmt->execute(['empleado_id' => $empleado_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



// //Crear Turno Programado
// function crearTurnoProgramado($empleado_id, $fecha, $hora_inicio, $hora_fin, $descripcion = null) {
//     $pdo = conectarDB();
//     $stmt = $pdo->prepare("
//         INSERT INTO turnos_programados (empleado_id, fecha, hora_inicio, hora_fin, descripcion)
//         VALUES (:empleado_id, :fecha, :hora_inicio, :hora_fin, :descripcion)
//     ");
//     return $stmt->execute([
//         'empleado_id' => $empleado_id,
//         'fecha' => $fecha,
//         'hora_inicio' => $hora_inicio,
//         'hora_fin' => $hora_fin,
//         'descripcion' => $descripcion
//     ]);
// }


// //Editar Turno Programado
// function editarTurnoProgramado($id, $fecha, $hora_inicio, $hora_fin, $descripcion = null) {
//     $pdo = conectarDB();
//     $stmt = $pdo->prepare("
//         UPDATE turnos_programados
//         SET fecha = :fecha, hora_inicio = :hora_inicio, hora_fin = :hora_fin, descripcion = :descripcion
//         WHERE id = :id
//     ");
//     return $stmt->execute([
//         'id' => $id,
//         'fecha' => $fecha,
//         'hora_inicio' => $hora_inicio,
//         'hora_fin' => $hora_fin,
//         'descripcion' => $descripcion
//     ]);
// }


// //Eliminar Turno Programado
// function eliminarTurnoProgramado($id) {
//     $pdo = conectarDB();
//     $stmt = $pdo->prepare("DELETE FROM turnos_programados WHERE id = :id");
//     return $stmt->execute(['id' => $id]);
// }


// // Obtener TODOS los turnos
// function obtenerTodosTurnosProgramados() {
//     $pdo = conectarDB();
//     $stmt = $pdo->query("
//         SELECT 
//             tp.id, 
//             tp.fecha, 
//             tp.hora_inicio, 
//             tp.hora_fin, 
//             tp.descripcion,
//             e.nombre,
//             e.apellidos
//         FROM turnos_programados tp
//         JOIN empleados e ON tp.empleado_id = e.id
//         ORDER BY tp.fecha ASC
//     ");
//     return $stmt->fetchAll(PDO::FETCH_ASSOC);
// }



function obtenerTodosTurnosProgramados() {
    $pdo = conectarDB();
    $stmt = $pdo->query("
        SELECT 
            tp.*,
            e.nombre,
            e.apellidos
        FROM turnos_programados tp
        JOIN empleados e ON tp.empleado_id = e.id
        ORDER BY tp.fecha ASC
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function crearTurnoProgramado($empleado_id, $fecha, $hora_inicio, $hora_fin, $descripcion = null) {
    $pdo = conectarDB();
    $stmt = $pdo->prepare("
        INSERT INTO turnos_programados (empleado_id, fecha, hora_inicio, hora_fin, descripcion)
        VALUES (:empleado_id, :fecha, :hora_inicio, :hora_fin, :descripcion)
    ");
    return $stmt->execute([
        'empleado_id' => $empleado_id,
        'fecha' => $fecha,
        'hora_inicio' => $hora_inicio,
        'hora_fin' => $hora_fin,
        'descripcion' => $descripcion
    ]);
}

function editarTurnoProgramado($id, $empleado_id, $fecha, $hora_inicio, $hora_fin, $descripcion = null) {
    $pdo = conectarDB();
    $stmt = $pdo->prepare("
        UPDATE turnos_programados
        SET empleado_id = :empleado_id, fecha = :fecha, hora_inicio = :hora_inicio, hora_fin = :hora_fin, descripcion = :descripcion
        WHERE id = :id
    ");
    return $stmt->execute([
        'id' => $id,
        'empleado_id' => $empleado_id,
        'fecha' => $fecha,
        'hora_inicio' => $hora_inicio,
        'hora_fin' => $hora_fin,
        'descripcion' => $descripcion
    ]);
}

function eliminarTurnoProgramado($id) {
    $pdo = conectarDB();
    $stmt = $pdo->prepare("DELETE FROM turnos_programados WHERE id = :id");
    return $stmt->execute(['id' => $id]);
}



// Funciones para la gestión de ambulancias

// Función para obtener todas las ambulancias
function obtenerAmbulancias()
{
    $pdo = conectarDB();
    $stmt = $pdo->query("SELECT * FROM ambulancias");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Función para obtener una ambulancia por su ID
function obtenerAmbulanciaPorId($id)
{
    $pdo = conectarDB();
    $stmt = $pdo->prepare("SELECT * FROM ambulancias WHERE id = :id");
    $stmt->execute(['id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Función para crear una nueva ambulancia
function crearAmbulancia($matricula, $tipo, $estado)
{
    $pdo = conectarDB();
    $stmt = $pdo->prepare("INSERT INTO ambulancias (matricula, tipo, estado) VALUES (:matricula, :tipo, :estado)");
    return $stmt->execute([
        'matricula' => $matricula,
        'tipo' => $tipo,
        'estado' => $estado
    ]);
}

// Función para actualizar una ambulancia
function actualizarAmbulancia($id, $matricula, $tipo, $estado)
{
    $pdo = conectarDB();
    $stmt = $pdo->prepare("UPDATE ambulancias SET matricula = :matricula, tipo = :tipo, estado = :estado WHERE id = :id");
    return $stmt->execute([
        'id' => $id,
        'matricula' => $matricula,
        'tipo' => $tipo,
        'estado' => $estado
    ]);
}

// Función para eliminar una ambulancia
function eliminarAmbulancia($id)
{
    $pdo = conectarDB();
    $stmt = $pdo->prepare("DELETE FROM ambulancias WHERE id = :id");
    return $stmt->execute(['id' => $id]);
}