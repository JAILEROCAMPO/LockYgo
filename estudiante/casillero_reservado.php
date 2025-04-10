<?php
session_start();
include "../conexion/dbpdo.php"; // Asegúrate de que este archivo tenga la conexión correcta a `lockygo3`

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login/inicioSesion_usuario.html");
    exit();
}

$estudiante_id = $_SESSION['id_usuario'];

// Verificar si el estudiante existe en la base de datos
$query_check_estudiante = "SELECT id FROM estudiantes WHERE id = ?";
$stmt = $conn->prepare($query_check_estudiante);
$stmt->execute([$estudiante_id]);

if (!$stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "<p>Error: El usuario no está registrado en el sistema.</p>";
    exit();
}

// Obtener la reserva activa del estudiante
$query = "SELECT r.id AS reserva_id, c.id AS casillero_id, c.numero, c.estado 
          FROM reservas r 
          JOIN casilleros c ON r.casillero_id = c.id 
          WHERE r.estudiante_id = ? AND r.estado = 'Activa'";

$stmt = $conn->prepare($query);
$stmt->execute([$estudiante_id]);
$reserva = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$reserva) {
    echo "<p>No tienes ningún casillero reservado.</p>";
    exit();
}

// Validar estado del casillero
if (!in_array($reserva['estado'], ['ocupado', 'dañado'])) {
    echo "<p>Error: Tu reserva está activa, pero el casillero figura como '{$reserva['estado']}'.</p>";
    exit();
}

// **Liberar casillero**
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['liberar'])) {
    $conn->beginTransaction();
    try {
        // Verificar si la reserva sigue activa antes de liberarla
        $query_check_reserva = "SELECT id FROM reservas WHERE id = ? AND estado = 'Activa'";
        $stmt = $conn->prepare($query_check_reserva);
        $stmt->execute([$reserva['reserva_id']]);
        
        if (!$stmt->fetch(PDO::FETCH_ASSOC)) {
            throw new Exception("No se encontró una reserva activa para liberar.");
        }

        // Actualizar reserva y casillero
        $query = "UPDATE reservas SET estado = 'Finalizada', fecha_liberacion = NOW() WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$reserva['reserva_id']]);

        $query = "UPDATE casilleros SET estado = 'libre' WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$reserva['casillero_id']]);

        $conn->commit();
        header("Location: index_estudiante.php");
        exit();
    } catch (Exception $e) {
        $conn->rollBack();
        echo "<p>Error al liberar el casillero: " . $e->getMessage() . "</p>";
    }
}

// **Reportar daño**
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reportar'])) {
    $descripcion = trim($_POST['descripcion']);

    if (!empty($descripcion)) {
        $conn->beginTransaction();
        try {
            // Insertar el reporte de daño
            $query = "INSERT INTO reportes_danos (estudiante_id, casillero_id, descripcion) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->execute([$estudiante_id, $reserva['casillero_id'], $descripcion]);

            // Si el casillero está en estado 'ocupado', cambiarlo a 'dañado'
            $query = "UPDATE casilleros SET estado = 'dañado' WHERE id = ? AND estado = 'ocupado'";
            $stmt = $conn->prepare($query);
            $stmt->execute([$reserva['casillero_id']]);

            $conn->commit();
        } catch (Exception $e) {
            $conn->rollBack();
            echo "<p>Error al reportar el daño: " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p>Por favor, describe el daño antes de enviarlo.</p>";
    }
}
?>
<?php



?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Lock&Go - Inicio</title>
        <link rel="stylesheet" href="../styles.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
        <link rel="icon" type="image/png" href="../../Imagenes/image-removebg-preview.png">
    
    </head>
<body>
    <nav class="navbar">
        <div class="logo">
            <img src="../Imagenes/image-removebg-preview.png" alt="Lock&Go">
            <span class="logo-text">Lock&Go</span>
        </div>
        <ul class="menu">
            <li><a href="index_estudiante.php">Inicio</a></li>
            <li><a href="../Pagina Reserva/bienvenida.html">Casilleros</a></li>
            <li><a href="../contactenos/contacto.html">Contacto</a></li>
        </ul>
        <div class="user-icon"> 
            <a href="editar_perfil.php"><img src="../Imagenes/perfil.png" alt="Perfil"></a>
            <a href="../login/logout.php" class="cerrar"><img src="../Imagenes/cerrar.png" alt="Cerrar Sesion"></a>
        </div>

    </nav>
    <h2>Tu Casillero Reservado</h2>
    <div class="casillero-box">
        <span class="numero-casillero"><?php echo htmlspecialchars($reserva['numero']); ?></span>
       
    </div>
    <form method="POST">
        <button type="submit" name="liberar">Liberar Casillero</button>
    </form>
    <!-- Botón que abre el modal -->
    <button onclick="abrirModal()">Reportar Daño</button>

    <!-- Modal con FORMULARIO que sí envía los datos -->
    <div id="modalReporte" class="modal">
    <div class="modal-contenido">
        <h3>Reportar Daño</h3>
        <form method="POST">
            <textarea name="descripcion" placeholder="Describe el daño..." required></textarea>
            <input type="hidden" name="reportar" value="1"> <!-- Esto activa la lógica del PHP -->
            <div class="botones-modal">
                <button type="submit">Reportar</button>
                <button type="button" onclick="cerrarModal()">Cerrar</button>
            </div>
        </form>
    </div>
    </div>


</div>

    <!-- Pie de página -->
    <footer>
        <p>&copy; Servicio Nacional de Aprendizaje SENA - Centro para la Industria de la Comunicación Gráfica (CENIGRAF) - Regional Distrito Capital.</p>
        <p>Dirección: Cra. 32 #15 - 80 – Teléfonos: 546 1500 o 596 0100 Ext.: 15 463</p>
        <p>Atención telefónica: Lunes a viernes 7:00 a.m. a 7:00 p.m. - Sábados 8:00 a.m. a 1:00 p.m.</p>
        <p>Línea de atención al ciudadano: Bogotá +(57) 601 7366060 - Línea gratuita: 018000 910270</p>
        <p>Contacto: <a href="mailto:servicioalciudadano@sena.edu.co">servicioalciudadano@sena.edu.co</a></p>

        <div class="social-icons">
            <a href="https://www.facebook.com/SENADistritoCapital/" target="_blank">
                <img src="../Imagenes/face_logo.png" alt="Facebook">
            </a>
            <a href="https://x.com/SENAComunica" target="_blank">
                <img src="../Imagenes/tw_logo.png" alt="Twitter">
            </a>
            <a href="https://www.instagram.com/senacomunica/" target="_blank">
                <img src="../Imagenes/insta_logo.png" alt="Instagram">
            </a>
        </div>

        <p><a href="Contactenos/privacidad.html">Aviso de privacidad</a></p>
    </footer>
    <script>
        function abrirModal() {
            document.getElementById("modalReporte").style.display = "block";
        }

        function cerrarModal() {
            document.getElementById("modalReporte").style.display = "none";
        }
        function enviarReporte() {
            const descripcion = document.getElementById("descripcion").value.trim();
             if (!descripcion) {
                alert("Por favor describe el daño antes de enviar.");
                return;
            }
            alert("Reporte enviado correctamente.");
            cerrarModal();
        }



    </script>

</body>
