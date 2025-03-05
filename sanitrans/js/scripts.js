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

//Función para mostrar secciones según tipo de formulario
function mostrarSeccion(tipo) {
    // 1. Oculta todas las secciones con la clase 'seccion-condicional'
    document.querySelectorAll('.seccion-condicional').forEach(seccion => {
        seccion.style.display = 'none';
    });
    // 2. Muestra la sección específica que coincide con el 'tipo' proporcionado en el Id
    const seccion = document.getElementById(`seccion-${tipo}`);
    if (seccion) seccion.style.display = 'block';
}


// Función para validar el DNI
function validarDNI(dni) {
    const regex = /^[0-9]{8}[A-Za-z]$/;
    if (!regex.test(dni)) {
        return false; // El formato no es válido
    }

    const letras = "TRWAGMYFPDXBNJZSQVHLCKE";
    const numero = dni.substr(0, 8);
    const letra = dni.charAt(8).toUpperCase();
    const letraCalculada = letras[numero % 23];

    return letra === letraCalculada; // La letra debe coincidir
}

// Función para validar el formulario de empleado
function validarFormulario() {
    const dni = document.getElementById('dni').value;
    const pswd = document.getElementById('pswd').value;
    const confirmar_pswd = document.getElementById('confirmar_pswd').value;
    const errorContrasena = document.getElementById('errorContrasena');
    const errorDNI = document.getElementById('errorDNI');

    // Validar DNI
    if (!validarDNI(dni)) {
        errorDNI.textContent = 'DNI no válido. Debe tener 8 números y una letra válida.';
        return false; // Evita que el formulario se envíe
    } else {
        errorDNI.textContent = '';
    }

    // Validar contraseñas
    if (pswd !== confirmar_pswd) {
        errorContrasena.textContent = 'Las contraseñas no coinciden.';
        return false; // Evita que el formulario se envíe
    } else {
        errorContrasena.textContent = '';
    }

    return true; // Permite que el formulario se envíe
}

// Función para alternar la visibilidad de la contraseña
function togglePassword(fieldId, iconId) {
    var passwordField = document.getElementById(fieldId);
    var eyeIcon = document.getElementById(iconId);

    if (passwordField.type === "password") {
        passwordField.type = "text";
        eyeIcon.classList.remove("bi-eye");
        eyeIcon.classList.add("bi-eye-slash"); // Cambia el ícono a "ojo tachado"
    } else {
        passwordField.type = "password";
        eyeIcon.classList.remove("bi-eye-slash");
        eyeIcon.classList.add("bi-eye"); // Cambia el ícono a "ojo"
    }
}


//Ejecutar funciones cuando el DOM esté listo
document.addEventListener("DOMContentLoaded", function () {
    mostrarAlertaTurnoIniciado();
    
});


