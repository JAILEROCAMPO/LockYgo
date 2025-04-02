<?php
include '../conexion/dbpdo.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservas - Panel de Administrador</title>
    <link rel="stylesheet" href="../styles.css">
    <script>
        function cargarReservas() {
            fetch('obtener_reservas.php')
                .then(response => response.text())
                .then(data => {
                    document.getElementById('tabla-reservas').innerHTML = data;
                });
        }

        function liberarCasillero(casilleroId) {
            fetch('liberar_casillero.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'id=' + casilleroId
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                cargarReservas(); // Recargar la tabla después de liberar
            });
        }

        setInterval(cargarReservas, 5000); // Actualiza cada 5 segundos
    </script>
</head>
<body>
    <h2>Reservas de Casilleros</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Programa de Formación</th>
                <th>Número de Ficha</th>
                <th>Casillero</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody id="tabla-reservas">
            <!-- Se llenará con obtener_reservas.php -->
        </tbody>
    </table>
</body>
</html>
