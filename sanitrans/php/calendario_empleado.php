<?php
session_start();
require 'controller.php';

if (!isset($_SESSION["usuario_id"]) || $_SESSION["rol"] !== 'empleado') {
    header("Location: index.php");
    exit();
}

$empleado_id = $_SESSION["usuario_id"];
$turnos = obtenerTurnosProgramados($empleado_id);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendario de Turnos - Sanitrans</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
    <link rel="stylesheet" href="\sanitrans\css\Estilo.css">
    <style>
        .fc-event {
            cursor: pointer;
            padding: 3px;
            border-radius: 4px;
            white-space: normal;
            /* Evita que el texto se divida en varias líneas */
            overflow: hidden;
            /* Oculta el texto que se desborda */
            text-overflow: ellipsis;
            /* Muestra "..." si el texto es demasiado largo */
        }
    </style>
</head>

<body>

    <header>
        Plataforma web de transporte sanitario
    </header>

    <div class="container mt-5">
        <h2>Calendario de Turnos</h2>
        <div>
            <a href="panel_empleado.php" class="btn btn-secondary mb-3"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-return-left" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M14.5 1.5a.5.5 0 0 1 .5.5v4.8a2.5 2.5 0 0 1-2.5 2.5H2.707l3.347 3.346a.5.5 0 0 1-.708.708l-4.2-4.2a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 8.3H12.5A1.5 1.5 0 0 0 14 6.8V2a.5.5 0 0 1 .5-.5" />
                </svg>&nbsp;Volver al Panel</a>
        </div>
        <div id="calendario"></div>

    </div>

    <!-- Scripts de FullCalendar -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/es.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const turnos = <?php echo json_encode($turnos); ?>;

            // Mapear turnos a eventos del calendario
            const eventos = turnos.map(turno => {
                const fechaInicio = new Date(`${turno.fecha}T${turno.hora_inicio}`);
                const fechaFin = new Date(`${turno.fecha}T${turno.hora_fin}`);

                // Si la hora de fin es anterior a la de inicio, sumar 1 día
                if (fechaFin < fechaInicio) {
                    fechaFin.setDate(fechaFin.getDate() + 1);
                }

                return {
                    title: `Turno: ${turno.hora_inicio} - ${turno.hora_fin}`, // Mostrar hora de inicio y fin
                    start: fechaInicio,
                    end: fechaFin,
                    description: `Hora de inicio: ${turno.hora_inicio}\nHora de fin: ${turno.hora_fin}\n ${turno.descripcion || 'Sin descripción'}`,
                    color: '#3788d8' // Color personalizado
                };
            });

            // Configurar calendario
            const calendarEl = document.getElementById('calendario');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                locale: 'es',
                firstDay: 1, // Lunes como primer día
                initialView: 'dayGridMonth',
                events: eventos,
                eventTimeFormat: { // Formato 24h
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false
                },
                eventDidMount: function(info) {
                    // Mostrar tooltip con detalles (sin etiquetas <br>)
                    info.el.setAttribute('title', info.event.extendedProps.description.replace(/\n/g, ' - '));
                },
                eventContent: function(info) {
                    // Personalizar contenido del evento
                    const descripcion = info.event.extendedProps.description.split('\n')[2]; // Obtener solo la descripción
                    return {
                        html: `
                            <div class="fw-bold">${info.event.title}</div>
                            
                            <div>${descripcion}</div>
                        `
                    };
                }
            });

            calendar.render();
        });
    </script>
    <footer>
        Proyecto desarrollo de aplicaciones web<br>
        Mario Carmona Ramos
    </footer>
</body>

</html>