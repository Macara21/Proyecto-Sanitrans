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
/*
// Función para validar un formulario (ejemplo)
function validarFormulario() {
    const formulario = document.querySelector('form');
    formulario.addEventListener('submit', function (event) {
        const matricula = document.querySelector('#matricula').value;
        if (!matricula) {
            alert('Por favor, selecciona una matrícula.');
            event.preventDefault(); // Evita que el formulario se envíe
        }
    });
}*/

//Ejecutar funciones cuando el DOM esté listo
document.addEventListener("DOMContentLoaded", function () {
    mostrarAlertaTurnoIniciado();
   // validarFormulario(); // Ejemplo de otra función
});