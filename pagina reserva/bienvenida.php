<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["autenticado"])) {
    header("Location: ../login/inicioSesion_usuario.html"); // Redirigir a login si no está autenticado
    exit();
}
// Obtener el nombre del usuario de la sesión
$nombreUsuario = $_SESSION["nombre"];
include '../conexion/dbpdo.php';

// Obtener casilleros
try {
    $query = "SELECT * FROM casilleros";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $casilleros = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $casilleros[$row['numero']] = $row['estado'];
    }
} catch (PDOException $e) {
    die("Error al obtener casilleros: " . $e->getMessage());
}

// Definir bloques de casilleros
$bloques = [
    'A' => range(1, 120),
    'B' => range(121, 269),
    'C' => array_merge(range(270, 284), range(304, 310), range(315, 325), range(330, 340)),
    'D' => array_merge(range(285, 299), range(360, 434)),
    'E' => range(435, 475)
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lock&Go - Reserva de Casilleros</title>
    <link rel="stylesheet" href="../styles.css">
    <style>
        .contenedor-botones { text-align: center; margin-bottom: 20px; }
        .btn { margin: 5px; padding: 10px 15px; cursor: pointer; }
        .contenedor-cuadricula {
            display: grid; grid-template-columns: repeat(10, 50px); gap: 10px; justify-content: center; margin-top: 20px;
        }
        .casillero {
            width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;
            border: 2px solid black; cursor: pointer;
        }
        .libre { background-color: green; }
        .ocupado { background-color: red; cursor: not-allowed; }
        
        /* Botón de liberar en un costado */
        .contenedor-liberar {
            position: fixed;
            top: 50%;
            right: 20px;
            transform: translateY(-50%);
            padding: 20px;
            background: #f8f9fa;
            border: 2px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            display: none;
        }
        .btn-liberar {
            background-color: orange;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            width: 100%;
        }
    </style>
</head>
<body>

    <nav class="navbar">
            <div class="logo">
                <img src="../Imagenes/image-removebg-preview.png" alt="Lock&Go">
                <span class="logo-text">Lock&Go</span>
            </div>
            <ul class="menu">
                <li><a href="../estudiante/index_estudiante.php">Inicio</a></li>
                <li><a href="../Pagina Reserva/bienvenida.php">Casilleros</a></li>
                <li><a href="../contactenos/contacto.html">Contacto</a></li>
            </ul>
            <div class="user-icon"> 
                <a href="editar_perfil.php"><img src="../Imagenes/perfil.png" alt="Perfil"></a>
                <a href="../login/logout.php" class="cerrar"><img src="../Imagenes/cerrar.png" alt="Cerrar Sesion"></a>
            </div>

    </nav>


    <section class="tittle-welcome">
        <h1></h1>
        <h2>!<?php echo htmlspecialchars(ucfirst($nombreUsuario)); ?>! <br>Por favor selecciona el casillero que mejor se te facilite</h2>
    </section>

    <div class="contenedor-botones">
        <?php foreach ($bloques as $bloque => $numeros) : ?>
            <button class="btn bloque" onclick="mostrarCasilleros('<?php echo $bloque; ?>')">Bloque <?php echo $bloque; ?></button>
        <?php endforeach; ?>
    </div>

    <div class="contenedor-cuadricula" id="cuadricula">
        <?php foreach ($bloques as $bloque => $numeros) : ?>
            <div id="bloque-<?php echo $bloque; ?>" style="display: none;">
                <?php foreach ($numeros as $num) :
                    $estado = isset($casilleros[$num]) ? $casilleros[$num] : 'libre';
                    $clase = ($estado == 'libre') ? 'libre' : 'ocupado';
                ?>
                    <div class="casillero <?php echo $clase; ?>" data-id="<?php echo $num; ?>" onclick="mostrarVentana(this)">
                        <?php echo $num; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Botón liberar en un costado -->
    <div class="contenedor-liberar" id="contenedor-liberar">
        <h4>Casillero reservado:</h4>
        <p id="casilleroReservado">Ninguno</p>
        <button class="btn-liberar" id="btnLiberar" style="display: none;">Liberar</button>
    </div>

    <div id="ventanaEmergente" class="ventana" style="display: none;">
        <div class="contenido-ventana">
            <h3>Reservar Casillero</h3>
            <p>¿Deseas reservar el casillero <span id="numeroCasillero"></span>?</p>
            <button id="reservarButton">Reservar</button>
            <button onclick="cerrarVentana()">Cerrar</button>
        </div>
    </div>

    <script>
        function mostrarCasilleros(bloque) {
            document.querySelectorAll('.contenedor-cuadricula > div').forEach(div => div.style.display = 'none');
            document.getElementById('bloque-' + bloque).style.display = 'grid';
        }

        function mostrarVentana(casillero) {
            if (casillero.classList.contains('ocupado')) return;
            let numero = casillero.textContent;
            document.getElementById('numeroCasillero').textContent = numero;
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
                    let casillero = document.querySelector(`[data-id='${id}']`);
                    casillero.classList.remove('libre');
                    casillero.classList.add('ocupado');
                    cerrarVentana();

                    // Mostrar botón "Liberar"
                    document.getElementById('casilleroReservado').textContent = id;
                    document.getElementById('btnLiberar').setAttribute('data-id', id);
                    document.getElementById('contenedor-liberar').style.display = 'block';
                    document.getElementById('btnLiberar').style.display = 'block';
                }
            });
        });

        document.getElementById('btnLiberar').addEventListener('click', function() {
            let id = this.getAttribute('data-id');
            fetch('liberar.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'id=' + id
            }).then(() => {
                document.querySelector(`[data-id='${id}']`).classList.remove('ocupado');
                document.querySelector(`[data-id='${id}']`).classList.add('libre');
                document.getElementById('contenedor-liberar').style.display = 'none';
            });
        });
    </script>

</body>
</html>
