<?php
// Habilitar la visualización de errores (solo para desarrollo)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Función para conectar a la base de datos
function conectarDB()
{
    $host = "localhost";
    $usuario = "root";  // Cambiar esto si tenemos un usuario distinto
    $password = "";     // Si tenemos contraseña la ponemos
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
function iniciarTurno($empleado_id, $matricula, $kilometros)
{
    $pdo = conectarDB();

    // Verificar si la ambulancia existe
    $stmt = $pdo->prepare("SELECT id, kilometros FROM ambulancias WHERE matricula = :matricula");
    $stmt->execute(['matricula' => $matricula]);
    $ambulancia = $stmt->fetch();

    if (!$ambulancia) {
        return false; // La matrícula no existe
    }

    // Verificar que los kilómetros no sean inferiores a los registrados anteriormente
    if ($kilometros < $ambulancia['kilometros']) {
        return false; // Los kilómetros no pueden ser inferiores a los registrados
    }

    // Actualizar los kilómetros de la ambulancia
    $stmt = $pdo->prepare("UPDATE ambulancias SET kilometros = :kilometros WHERE id = :id");
    $stmt->execute([
        'kilometros' => $kilometros,
        'id' => $ambulancia['id']
    ]);

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




// Función para obtener todos los empleados
 function obtenerEmpleados(): array
 {
     $pdo = conectarDB();
     $stmt = $pdo->query("SELECT * FROM empleados");
     return $stmt->fetchAll(PDO::FETCH_ASSOC);
 }

// Función para crear un empleado
function crearEmpleado($nombre, $apellidos, $dni, $email, $pswd, $rol) {
    $pdo = conectarDB();
    $pswdHash = password_hash($pswd, PASSWORD_BCRYPT);

    try {
        $stmt = $pdo->prepare("INSERT INTO empleados (nombre, apellidos, dni, email, pswd, rol) VALUES (:nombre, :apellidos, :dni, :email, :pswd, :rol)");
        $stmt->execute([
            'nombre' => $nombre,
            'apellidos' => $apellidos,
            'dni' => $dni,
            'email' => $email,
            'pswd' => $pswdHash,
            'rol' => $rol
        ]);
        return true; // Empleado creado correctamente
    } catch (PDOException $e) {
        // Capturar errores de duplicados
        if ($e->getCode() == 23000) { // Código de error para duplicados
            return "El DNI o el email ya están registrados.";
        } else {
            return "Error al crear el empleado: " . $e->getMessage();
        }
    }
}

// Función para editar un empleado
function editarEmpleado($id, $nombre, $apellidos, $dni, $email, $pswd, $rol): bool
{
    $pdo = conectarDB();
    $sql = "UPDATE empleados SET nombre = :nombre, apellidos = :apellidos, dni = :dni, email = :email, rol = :rol";
    $params = [
        'id' => $id,
        'nombre' => $nombre,
        'apellidos' => $apellidos,
        'dni' => $dni,
        'email' => $email,
        'rol' => $rol
    ];

    if (!empty($pswd)) {
        $pswdHash = password_hash($pswd, PASSWORD_BCRYPT);
        $sql .= ", pswd = :pswd";
        $params['pswd'] = $pswdHash;
    }

    $sql .= " WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute($params);
}


// Función para obtener un empleado por ID
function obtenerEmpleadoPorId($id): array
{
    $pdo = conectarDB();
    $stmt = $pdo->prepare("SELECT * FROM empleados WHERE id = :id");
    $stmt->execute(['id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Función para eliminar un empleado
function eliminarEmpleado($id): bool
{
    $pdo = conectarDB();
    $stmt = $pdo->prepare("DELETE FROM empleados WHERE id = :id");
    return $stmt->execute(['id' => $id]);
}



function registrarParteAsistencia($datos)
{
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

        // Insertar en la tabla correspondiente según el tipo de incidente
        $detalles = $datos['detalles_incidente'];
        $detalles['parte_id'] = $parte_id;

        switch ($datos['tipo_incidente']) {
            case 'AccidenteTrafico':
                $detalles = [
                    'parte_id' => $parte_id,
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

                $stmtDetalle = $pdo->prepare("
                    INSERT INTO accidente_trafico (
                        parte_id, rol, lugar_accidente, nombre_asegurado, matricula_vehiculo,
                        marca_vehiculo, aseguradora_vehiculo, numero_poliza, nombre_asegurado_contrario,
                        matricula_vehiculo_contrario, marca_vehiculo_contrario, aseguradora_contrario, numero_poliza_contrario
                    ) VALUES (
                        :parte_id, :rol, :lugar_accidente, :nombre_asegurado, :matricula_vehiculo,
                        :marca_vehiculo, :aseguradora_vehiculo, :numero_poliza, :nombre_asegurado_contrario,
                        :matricula_vehiculo_contrario, :marca_vehiculo_contrario, :aseguradora_contrario, :numero_poliza_contrario
                    )
                ");
                break;

            case 'AccidenteLaboral':
                $detalles = [
                    'parte_id' => $parte_id,
                    'empresa' => $_POST['empresa'],
                    'mutua_accidente_trabajo' => $_POST['mutua']
                ];

                $stmtDetalle = $pdo->prepare("
                    INSERT INTO accidente_laboral (
                        parte_id, empresa, mutua_accidente_trabajo
                    ) VALUES (
                        :parte_id, :empresa, :mutua_accidente_trabajo
                    )
                ");
                break;

            case 'CompaniaPrivada':
                $detalles = [
                    'parte_id' => $parte_id,
                    'aseguradora_compania' => $_POST['aseguradora_compania'],
                    'numero_poliza_compania' => $_POST['numero_poliza_compania']
                ];

                $stmtDetalle = $pdo->prepare("
                    INSERT INTO compania_privada (
                        parte_id, aseguradora_compania, numero_poliza_compania
                    ) VALUES (
                        :parte_id, :aseguradora_compania, :numero_poliza_compania
                    )
                ");
                break;

            case 'Extranjero':
                $detalles = [
                    'parte_id' => $parte_id,
                    'pais_origen' => $_POST['pais_origen']
                ];

                $stmtDetalle = $pdo->prepare("
                    INSERT INTO extranjero (
                        parte_id, pais_origen
                    ) VALUES (
                        :parte_id, :pais_origen
                    )
                ");
                break;

            default:
                throw new Exception("Tipo de incidente no válido.");
        }

        // Depura el array $detalles
        error_log(print_r($detalles, true));

        // Ejecutar la inserción de los detalles del incidente
        $stmtDetalle->execute($detalles);

        $pdo->commit();
        return ['success' => true, 'parte_id' => $parte_id];
    } catch (Exception $e) {
        $pdo->rollBack();
        error_log("Error al registrar parte: " . $e->getMessage());
        return ['success' => false, 'error' => $e->getMessage()];
    }
}

function tieneParteAsistencia($turno_id)
{
    $pdo = conectarDB();
    $stmt = $pdo->prepare("
        SELECT pa.id 
        FROM partes_asistencia pa
        LEFT JOIN accidente_trafico at ON pa.id = at.parte_id
        LEFT JOIN accidente_laboral al ON pa.id = al.parte_id
        LEFT JOIN compania_privada cp ON pa.id = cp.parte_id
        LEFT JOIN extranjero e ON pa.id = e.parte_id
        WHERE pa.turno_id = :turno_id
    ");
    $stmt->execute(['turno_id' => $turno_id]);
    return $stmt->fetch() !== false;
}





// Obtener turnos de UN SOLO empleado
function obtenerTurnosProgramados($empleado_id)
{
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




function obtenerTodosTurnosProgramados()
{
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

function crearTurnoProgramado($empleado_id, $fecha, $hora_inicio, $hora_fin, $descripcion = null)
{
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

function editarTurnoProgramado($id, $empleado_id, $fecha, $hora_inicio, $hora_fin, $descripcion = null)
{
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

function eliminarTurnoProgramado($id)
{
    $pdo = conectarDB();
    $stmt = $pdo->prepare("DELETE FROM turnos_programados WHERE id = :id");
    return $stmt->execute(['id' => $id]);
}



// Función para obtener todos los turnos con detalles del empleado
function obtenerTodosTurnosConDetalles() {
    $pdo = conectarDB();
    $stmt = $pdo->query("
        SELECT t.id, t.inicio_turno, t.fin_turno, e.nombre, e.apellidos, e.dni
        FROM turnos t
        JOIN empleados e ON t.empleado_id = e.id
        ORDER BY t.inicio_turno DESC
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Función para calcular las horas trabajadas
function calcularHorasTrabajadas($inicio, $fin) {
    if ($inicio && $fin) {
        $inicio = new DateTime($inicio);
        $fin = new DateTime($fin);
        $diferencia = $inicio->diff($fin);
        return $diferencia->format('%h horas %i minutos');
    }
    return 'No finalizado';
}

// Función para obtener los detalles de la ambulancia asignada a un turno
function obtenerDetallesAmbulanciaPorTurno($turno_id) {
    $pdo = conectarDB();
    $stmt = $pdo->prepare("
        SELECT a.matricula, a.tipo, a.kilometros, 
               (SELECT COUNT(*) FROM checklist WHERE turno_id = :turno_id) as checklist,
               (SELECT SUM(litros) FROM combustible WHERE turno_id = :turno_id) as litros_combustible,
               (SELECT SUM(coste) FROM combustible WHERE turno_id = :turno_id) as coste_combustible,
               (SELECT descripcion FROM incidencias WHERE turno_id = :turno_id LIMIT 1) as incidencia
        FROM ambulancias a
        JOIN turnos t ON a.id = t.ambulancia_id
        WHERE t.id = :turno_id
    ");
    $stmt->execute(['turno_id' => $turno_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// FUNCIONES PARA LA GESTIÓN DE AMBULANCIAS

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

function crearAmbulancia($matricula, $tipo, $estado, $kilometros)
{
    $pdo = conectarDB();
    $stmt = $pdo->prepare("INSERT INTO ambulancias (matricula, tipo, estado, kilometros) VALUES (:matricula, :tipo, :estado, :kilometros)");
    return $stmt->execute([
        'matricula' => $matricula,
        'tipo' => $tipo,
        'estado' => $estado,
        'kilometros' => $kilometros
    ]);
}

// Función para actualizar una ambulancia
function actualizarAmbulancia($id, $matricula, $tipo, $estado, $kilometros)
{
    $pdo = conectarDB();
    $stmt = $pdo->prepare("UPDATE ambulancias SET matricula = :matricula, tipo = :tipo, estado = :estado, kilometros = :kilometros WHERE id = :id");
    return $stmt->execute([
        'id' => $id,
        'matricula' => $matricula,
        'tipo' => $tipo,
        'estado' => $estado,
        'kilometros' => $kilometros
    ]);
}

// Función para eliminar una ambulancia
function eliminarAmbulancia($id)
{
    $pdo = conectarDB();
    $stmt = $pdo->prepare("DELETE FROM ambulancias WHERE id = :id");
    return $stmt->execute(['id' => $id]);
}


function obtenerParteAsistenciaPorId($id) {
    $pdo = conectarDB();

    $sql = "SELECT pa.*, 
                   at.rol, at.lugar_accidente, at.nombre_asegurado, at.matricula_vehiculo, 
                   at.marca_vehiculo, at.aseguradora_vehiculo, at.numero_poliza, 
                   at.nombre_asegurado_contrario, at.matricula_vehiculo_contrario, 
                   at.marca_vehiculo_contrario, at.aseguradora_contrario, at.numero_poliza_contrario,
                   al.empresa, al.mutua_accidente_trabajo,
                   cp.aseguradora_compania, cp.numero_poliza_compania,
                   e.pais_origen
            FROM partes_asistencia pa
            LEFT JOIN accidente_trafico at ON pa.id = at.parte_id
            LEFT JOIN accidente_laboral al ON pa.id = al.parte_id
            LEFT JOIN compania_privada cp ON pa.id = cp.parte_id
            LEFT JOIN extranjero e ON pa.id = e.parte_id
            WHERE pa.id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


function actualizarParteAsistencia($datos) {
    $pdo = conectarDB();

    try {
        $pdo->beginTransaction();

        // Actualizar la tabla partes_asistencia
        $sql = "UPDATE partes_asistencia SET
                fecha_servicio = :fecha_servicio,
                hora_servicio = :hora_servicio,
                nombre_paciente = :nombre_paciente,
                apellidos_paciente = :apellidos_paciente,
                telefono_paciente = :telefono_paciente,
                dni_paciente = :dni_paciente,
                domicilio_paciente = :domicilio_paciente,
                poblacion_paciente = :poblacion_paciente,
                provincia_paciente = :provincia_paciente,
                codigo_postal = :codigo_postal,
                tipo_asistencia = :tipo_asistencia,
                diagnostico = :diagnostico,
                tipo_incidente = :tipo_incidente
                WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'id' => $datos['id'],
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

        // Actualizar la tabla correspondiente según el tipo de incidente
        switch ($datos['tipo_incidente']) {
            case 'AccidenteTrafico':
                $sql = "UPDATE accidente_trafico SET
                        rol = :rol,
                        lugar_accidente = :lugar_accidente,
                        nombre_asegurado = :nombre_asegurado,
                        matricula_vehiculo = :matricula_vehiculo,
                        marca_vehiculo = :marca_vehiculo,
                        aseguradora_vehiculo = :aseguradora_vehiculo,
                        numero_poliza = :numero_poliza,
                        nombre_asegurado_contrario = :nombre_asegurado_contrario,
                        matricula_vehiculo_contrario = :matricula_vehiculo_contrario,
                        marca_vehiculo_contrario = :marca_vehiculo_contrario,
                        aseguradora_contrario = :aseguradora_contrario,
                        numero_poliza_contrario = :numero_poliza_contrario
                        WHERE parte_id = :parte_id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'parte_id' => $datos['id'],
                    'rol' => $datos['detalles_incidente']['rol'],
                    'lugar_accidente' => $datos['detalles_incidente']['lugar_accidente'],
                    'nombre_asegurado' => $datos['detalles_incidente']['nombre_asegurado'],
                    'matricula_vehiculo' => $datos['detalles_incidente']['matricula_vehiculo'],
                    'marca_vehiculo' => $datos['detalles_incidente']['marca_vehiculo'],
                    'aseguradora_vehiculo' => $datos['detalles_incidente']['aseguradora_vehiculo'],
                    'numero_poliza' => $datos['detalles_incidente']['numero_poliza'],
                    'nombre_asegurado_contrario' => $datos['detalles_incidente']['nombre_asegurado_contrario'],
                    'matricula_vehiculo_contrario' => $datos['detalles_incidente']['matricula_vehiculo_contrario'],
                    'marca_vehiculo_contrario' => $datos['detalles_incidente']['marca_vehiculo_contrario'],
                    'aseguradora_contrario' => $datos['detalles_incidente']['aseguradora_contrario'],
                    'numero_poliza_contrario' => $datos['detalles_incidente']['numero_poliza_contrario']
                ]);
                break;

            case 'AccidenteLaboral':
                $sql = "UPDATE accidente_laboral SET
                        empresa = :empresa,
                        mutua_accidente_trabajo = :mutua_accidente_trabajo
                        WHERE parte_id = :parte_id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'parte_id' => $datos['id'],
                    'empresa' => $datos['detalles_incidente']['empresa'],
                    'mutua_accidente_trabajo' => $datos['detalles_incidente']['mutua_accidente_trabajo']
                ]);
                break;

            case 'CompaniaPrivada':
                $sql = "UPDATE compania_privada SET
                        aseguradora_compania = :aseguradora_compania,
                        numero_poliza_compania = :numero_poliza_compania
                        WHERE parte_id = :parte_id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'parte_id' => $datos['id'],
                    'aseguradora_compania' => $datos['detalles_incidente']['aseguradora_compania'],
                    'numero_poliza_compania' => $datos['detalles_incidente']['numero_poliza_compania']
                ]);
                break;

            case 'Extranjero':
                $sql = "UPDATE extranjero SET
                        pais_origen = :pais_origen
                        WHERE parte_id = :parte_id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'parte_id' => $datos['id'],
                    'pais_origen' => $datos['detalles_incidente']['pais_origen']
                ]);
                break;
        }

        $pdo->commit();
        return ['success' => true];
    } catch (Exception $e) {
        $pdo->rollBack();
        return ['success' => false, 'error' => $e->getMessage()];
    }
}

// Función para verificar si un turno tiene una incidencia
function tieneIncidencia($turno_id) {
    $pdo = conectarDB();
    $stmt = $pdo->prepare("SELECT id FROM incidencias WHERE turno_id = :turno_id");
    $stmt->execute(['turno_id' => $turno_id]);
    return $stmt->fetch() !== false;
}


// Función para marcar una incidencia como resuelta
function marcarIncidenciaComoResuelta($turno_id) {
    $pdo = conectarDB();
    $stmt = $pdo->prepare("UPDATE incidencias SET estado = 'Resuelto' WHERE turno_id = :turno_id");
    $stmt->execute(['turno_id' => $turno_id]);
}

// Función para verificar si un turno tiene una incidencia no resuelta
function tieneIncidenciaNoResuelta($turno_id) {
    $pdo = conectarDB();
    $stmt = $pdo->prepare("SELECT id FROM incidencias WHERE turno_id = :turno_id AND estado = 'Pendiente'");
    $stmt->execute(['turno_id' => $turno_id]);
    return $stmt->fetch() !== false;
}