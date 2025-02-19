document.getElementById('registroForm').addEventListener('submit', async function (event) {
    event.preventDefault();

    const formData = {
        nombre: document.querySelector('input[placeholder="Nombre"]').value,
        apellido: document.querySelector('input[placeholder="Apellido"]').value,
        telefono: document.querySelector('input[placeholder="Número de Teléfono"]').value,
        identificacion: document.querySelector('input[placeholder="Número de Identificación"]').value,
        ficha: document.querySelector('input[placeholder="Número de Ficha"]').value,
        correo: document.querySelector('input[placeholder="Correo Electrónico (soy.sena)"]').value,
        jornada: document.querySelector('select').value,
        contrasena: document.querySelector('input[placeholder="Contraseña"]').value
    };

    try {
        let response = await fetch('http://127.0.0.1:5000/registrar', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(formData)
        });

        let result = await response.json();
        alert(result.mensaje || result.error);
    } catch (error) {
        alert("Error en la conexión con el servidor");
    }
});
