// scripts.js

// Función para mostrar un alert cuando se inicia un turno
function mostrarAlertaTurnoIniciado() {
    const urlParams = new URLSearchParams(window.location.search);
    const turnoIniciado = urlParams.get('turno_iniciado');
    const matricula = urlParams.get('matricula');

    if (turnoIniciado && matricula) {
        alert(`Turno iniciado correctamente con la ambulancia: ${matricula}`);
    }
}
/*document.addEventListener('DOMContentLoaded', function() {
    // Mostrar/ocultar secciones condicionales
    const incidenteRadios = document.querySelectorAll('input[name="tipo_incidente"]');
    
    function toggleSecciones() {
        document.querySelectorAll('.seccion-condicional').forEach(seccion => {
            seccion.style.display = 'none';
        });
        
        const seleccionado = document.querySelector('input[name="tipo_incidente"]:checked');
        if (seleccionado) {
            const seccionId = `seccion-${seleccionado.value.toLowerCase()}`;
            const seccion = document.getElementById(seccionId);
            if (seccion) seccion.style.display = 'block';
        }
    }

    incidenteRadios.forEach(radio => {
        radio.addEventListener('change', toggleSecciones);
    });

    // Validación de formulario
    document.getElementById('form-parte').addEventListener('submit', function(e) {
        let valido = true;
        
        // Validar campos requeridos visibles
        document.querySelectorAll('.seccion-condicional:not([style*="display: none"]) [required]').forEach(campo => {
            if (!campo.value.trim()) {
                valido = false;
                campo.classList.add('is-invalid');
            }
        });

        if (!valido) {
            e.preventDefault();
            alert('Por favor complete todos los campos requeridos');
        }
    });

    /* Resetear validación al modificar campos
    document.querySelectorAll('input, select').forEach(campo => {
        campo.addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
    });
});*/

function mostrarSeccion(tipo) {
    // 1. Oculta todas las secciones con la clase 'seccion-condicional'
    document.querySelectorAll('.seccion-condicional').forEach(seccion => {
        seccion.style.display = 'none';
    });
    // 2. Muestra la sección específica que coincide con el 'tipo' proporcionado en el Id
    const seccion = document.getElementById(`seccion-${tipo}`);
    if (seccion) seccion.style.display = 'block';
}

//Ejecutar funciones cuando el DOM esté listo
document.addEventListener("DOMContentLoaded", function () {
    mostrarAlertaTurnoIniciado();
   // validarFormulario(); // Ejemplo de otra función
});


