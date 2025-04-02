<?php
include '../conexion/dbpdo.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservas en Tiempo Real</title>
    <link rel="stylesheet" href="../styles.css">
    <script>
        function actualizarTabla() {
            fetch('obtener_reservas.php')
                .then(response => response.text())
                .then(data => {
                    document.getElementById('tablaReservas').innerHTML = data;
                });
        }

        setInterval(actualizarTabla, 5000);
        window.onload = actualizarTabla;
    </script>
</head>
<body>
    <nav class="navbar">
        <ul>
            <li><a href="admin_panel.php">Inicio</a></li>
            <li><a href="#">Reservas</a></li>
            <li><a href="logout.php">Cerrar Sesión</a></li>
        </ul>
    </nav>
    
    <section>
        <h2>Casilleros Reservados</h2>
        <table border="1">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Programa de Formación</th>
                    <th>Ficha</th>
                    <th>Casillero</th>
                </tr>
            </thead>
            <tbody id="tablaReservas">
                <!-- Datos cargados dinámicamente -->
            </tbody>
        </table>
    </section>
</body>
</html>
