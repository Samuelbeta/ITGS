document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form'); // Selecciona tu formulario
    const modal = document.getElementById('modal');
    const modalMessage = document.getElementById('modal-message');
    const modalContent = document.querySelector('.modal-content');
    const span = document.getElementsByClassName('close')[0];

    // Validaciones del formulario
    form.addEventListener('submit', function (event) {
        let isValid = true;

        // Validación de la dirección
        const direccion = document.getElementById('direccion_linea1').value.trim();
        if (direccion === '') {
            alert('La dirección principal es obligatoria.');
            isValid = false;
        }

        // Validación de la ciudad
        const ciudad = document.getElementById('ciudad').value.trim();
        if (ciudad === '') {
            alert('La ciudad es obligatoria.');
            isValid = false;
        }

        // Validación del código postal (solo números y longitud)
        const codigoPostal = document.getElementById('codigo_postal').value.trim();
        const regexCodigoPostal = /^[0-9]{5}$/; // Ejemplo de un código postal de 5 dígitos
        if (!regexCodigoPostal.test(codigoPostal)) {
            alert('El código postal debe ser un número de 5 dígitos.');
            isValid = false;
        }

        // Validación del número de teléfono (solo números)
        const numero = document.getElementById('numero').value.trim();
        const regexNumero = /^[0-9]{10}$/; // Ejemplo para un número de 10 dígitos
        if (!regexNumero.test(numero)) {
            alert('El número de teléfono debe tener 10 dígitos.');
            isValid = false;
        }

        // Validación del correo electrónico
        const correo = document.getElementById('correo').value.trim();
        const regexCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!regexCorreo.test(correo)) {
            alert('El correo electrónico no es válido.');
            isValid = false;
        }

        // Si alguna validación falla, se previene el envío del formulario
        if (!isValid) {
            event.preventDefault();
        }
    });

    // Manejo del modal para mostrar mensajes de éxito o error
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status');

    if (status === 'success') {
        modalMessage.textContent = '¡La peticion se reaizo correctamente!';
        modalContent.classList.add('success');
        modal.style.display = 'block';
        removeStatusParam();
    } else if (status === 'error') {
        modalMessage.textContent = 'Hubo un error al agendar la cita. Por favor, inténtalo de nuevo.';
        modalContent.classList.add('error');
        modal.style.display = 'block';
        removeStatusParam();
    } else if (status === 'not_found') {
        modalMessage.textContent = 'No se encontró ninguna petición con el folio proporcionado.';
        modalContent.classList.add('error');
        modal.style.display = 'block';
        removeStatusParam();
    }

    // Cerrar el modal al hacer clic en la "X"
    span.onclick = function() {
        modal.style.display = 'none';
    }

    // Cerrar el modal al hacer clic fuera del contenido
    window.onclick = function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    }

    // Función para remover el parámetro 'status' de la URL
    function removeStatusParam() {
        const newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
        window.history.replaceState({ path: newUrl }, '', newUrl);
    }

    // Desplazamiento suave a la sección de detalles si el hash está presente
    if (window.location.hash === '#detalles-peticion') {
        const detallesPeticion = document.getElementById('detalles-peticion');
        if (detallesPeticion) {
            detallesPeticion.scrollIntoView({ behavior: 'smooth' });
        }
    }
});






