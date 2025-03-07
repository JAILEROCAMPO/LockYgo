document.querySelectorAll('.btn.bloque').forEach(button => {
    button.addEventListener('click', function() {
        const bloque = this.getAttribute('data-bloque');
        const cuadricula = document.getElementById('cuadricula');
        cuadricula.innerHTML = ''; // Limpiar la cuadrícula antes de llenarla

        let numeros = [];

        // Asignamos los rangos correspondientes a cada bloque
        if (bloque === 'A') {
            numeros = Array.from({ length: 120 }, (_, i) => i + 1); // De 1 a 120
        } else if (bloque === 'B') {
            numeros = Array.from({ length: 149 }, (_, i) => i + 121); // 121 a 269
        } else if (bloque === 'C') {
            numeros = [
                ...Array.from({ length: 15 }, (_, i) => i + 270), // de 270 a 284
                ...Array.from({ length: 7 }, (_, i) => i + 304), // de 304 a 310
                ...Array.from({ length: 11 }, (_, i) => i + 315), // 315 a 325
                ...Array.from({ length: 11 }, (_, i) => i + 330), // 330 a 340
            ];
        } else if (bloque === 'D') {
            numeros = [
                ...Array.from({ length: 15 }, (_, i) => i + 285), // 285 a 299
                ...Array.from({ length: 75 }, (_, i) => i + 360), // 360 a 434
            ];
        } else if (bloque === 'E') {
            numeros = Array.from({ length: 41 }, (_, i) => i + 435); // de 435 a 475
        }

        // Llamamos la cuadrícula con los números
        numeros.forEach(num => {
            const celda = document.createElement('div');
            celda.classList.add('celda');
            celda.textContent = num;

            // Agregar un evento de clic a cada celda (casillero)
            celda.addEventListener('click', function() {
                mostrarVentanaEmergente(num);
            });

            cuadricula.appendChild(celda);
        });

        // Alternar la visibilidad de la cuadrícula usando la clase 'visible'
        cuadricula.classList.toggle('visible');
    });
});

function mostrarVentanaEmergente(numCasillero) {
    const ventana = document.getElementById('ventanaEmergente');
    const numeroCasillero = document.getElementById('numeroCasillero');
    numeroCasillero.textContent = numCasillero;

    // Mostrar la ventana emergente
    ventana.style.display = 'flex';

    // Remover eventos previos para evitar acumulación
    const reservarButton = document.getElementById('reservarButton');
    const nuevoBoton = reservarButton.cloneNode(true);
    reservarButton.parentNode.replaceChild(nuevoBoton, reservarButton);

    // Agregar nuevo evento al botón de reservar
    nuevoBoton.addEventListener('click', function() {
        reservarCasillero(numCasillero);
    });

    
}
function cerrarVentanaEmergente() {
    const ventana = document.getElementById('ventanaEmergente');
    if (ventana) {
        ventana.style.display = 'none';
    }
}



// Función para enviar la solicitud de reserva al backend
function reservarCasillero(numCasillero) {
    const estudianteId = 1; // Asegúrate de obtener el ID correcto

    console.log("Reservando casillero:", numCasillero, "para estudiante:", estudianteId); // Para depuración

    fetch('reservar_casillero.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `casillero_id=${numCasillero}&estudiante_id=${estudianteId}`
    })
    .then(response => response.json())
    .then(data => {
        console.log("Respuesta del servidor:", data); // Ver si llega la respuesta
        alert(data.message);
        if (data.success) {
            actualizarCasilleroVisual(numCasillero);
            cerrarVentanaEmergente(); // Cerrar ventana después de reservar
        }
    })
    .catch(error => {
        console.error('Error en la solicitud:', error);
        alert("Hubo un error al reservar el casillero.");
    });
}

// Función para actualizar la interfaz
function actualizarCasilleroVisual(numCasillero) {
    const casillero = document.querySelector(`.celda:contains('${numCasillero}')`);
    if (casillero) {
        casillero.classList.add('ocupado'); // Asegúrate de tener una clase CSS para estilos
        casillero.onclick = null; // Deshabilitar el clic en casilleros ocupados
    }
}
document.addEventListener("DOMContentLoaded", function() {
    fetch('obtener_casilleros_ocupados.php')
        .then(response => response.json())
        .then(data => {
            data.forEach(numCasillero => {
                const casillero = document.querySelector(`.celda:contains('${numCasillero}')`);
                if (casillero) {
                    casillero.classList.add('ocupado');
                    casillero.onclick = null;
                }
            });
        })
        .catch(error => console.error('Error al cargar casilleros ocupados:', error));
});
