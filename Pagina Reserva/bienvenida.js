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

// Función para mostrar la ventana emergente
function mostrarVentanaEmergente(numCasillero) {
    const ventana = document.getElementById('ventanaEmergente');
    const numeroCasillero = document.getElementById('numeroCasillero');
    numeroCasillero.textContent = numCasillero;

    // Mostrar la ventana emergente
    ventana.style.display = 'flex';

    // Acciones para el botón de "Reservar Casillero"
    document.getElementById('reservarButton').addEventListener('click', function() {
        alert(`Casillero ${numCasillero} reservado con éxito!`);
        cerrarVentanaEmergente();
    });

    // Acciones para el botón de "Cerrar"
    document.getElementById('cerrarButton').addEventListener('click', cerrarVentanaEmergente);
}

// Función para cerrar la ventana emergente
function cerrarVentanaEmergente() {
    const ventana = document.getElementById('ventanaEmergente');
    ventana.style.display = 'none';
}
