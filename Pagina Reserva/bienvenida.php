<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lock&Go - Reserva de Casilleros</title>
    <link rel="stylesheet" href="../styles.css">
    <style>
        .contenedor-cuadricula {
            display: grid;
            grid-template-columns: repeat(10, 50px);
            gap: 10px;
            justify-content: center;
            margin-top: 20px;
        }
        .casillero {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid black;
            cursor: pointer;
        }
        .libre { background-color: green; }
        .ocupado { background-color: red; cursor: not-allowed; }
    </style>
</head>

<body>
    <nav class="navbar">
        <div class="logo">
            <img src="../Imagenes/image-removebg-preview.png" alt="Lock&Go">
            <span class="logo-text">Lock&Go</span>
        </div>
        <ul class="menu">
            <li><a href="../Pagina index/index.html">Inicio</a></li>
            <li><a href="../Pagina Reserva/bienvenida.html">Casilleros</a></li>
            <li><a href="../contactenos/contacto.html">Contacto</a></li>
        </ul>
        <div class="user-icon">
            <a href="../login/inicioSesion_usuario.html"><img src="../Imagenes/perfil.png" alt="Perfil"></a>
        </div>
    </nav>

    <section class="tittle-welcome">
        <h2>Por favor selecciona el casillero que mejor se te facilite</h2>
    </section>

    <div class="contenedor-cuadricula">
        <?php
        include '../conexion/db.php';
        $query = "SELECT * FROM casilleros";
        $result = mysqli_query($conn, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            $clase = $row['estado'] == 'libre' ? 'libre' : 'ocupado';
            echo "<div class='casillero $clase' data-id='{$row['id']}' onclick='mostrarVentana(this)'>";
            echo $row['numero'];
            echo "</div>";
        }
        ?>
    </div>

    <div id="ventanaEmergente" class="ventana" style="display: none;">
        <div class="contenido-ventana">
            <h3>Reservar Casillero</h3>
            <p>¿Deseas reservar el casillero <span id="numeroCasillero"></span>?</p>
            <button id="reservarButton">Reservar Casillero</button>
            <button id="cerrarButton" onclick="cerrarVentana()">Cerrar</button>
        </div>
    </div>

    <script>
        function mostrarVentana(casillero) {
            if (casillero.classList.contains('ocupado')) return;
            document.getElementById('numeroCasillero').textContent = casillero.textContent;
            document.getElementById('reservarButton').setAttribute('data-id', casillero.dataset.id);
            document.getElementById('ventanaEmergente').style.display = 'block';
        }
        function cerrarVentana() {
            document.getElementById('ventanaEmergente').style.display = 'none';
        }
        document.getElementById('reservarButton').addEventListener('click', function() {
            let id = this.getAttribute('data-id');
            fetch('reservar.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'id=' + id
            }).then(response => response.text())
              .then(data => {
                if (data === 'ok') {
                    document.querySelector(`[data-id='${id}']`).classList.remove('libre');
                    document.querySelector(`[data-id='${id}']`).classList.add('ocupado');
                    cerrarVentana();
                }
            });
        });
    </script>
</body>

</html>
