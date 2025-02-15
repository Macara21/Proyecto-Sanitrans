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
function tieneTurnoActivo($empleado_id) {
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

function registrarChecklist($datos) {
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
function tieneChecklist($turno_id) {
    $pdo = conectarDB();
    $stmt = $pdo->prepare("SELECT id FROM checklist WHERE turno_id = :turno_id");
    $stmt->execute(['turno_id' => $turno_id]);
    return $stmt->fetch() !== false;
}

// Función para obtener la matricula
function obtenerMatriculasDisponibles() {
    $pdo = conectarDB();
    $stmt = $pdo->query("SELECT matricula FROM ambulancias WHERE estado = 'Operativa'");
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}
