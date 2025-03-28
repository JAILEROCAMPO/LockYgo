<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["autenticado"])) {
    header("Location: ../login/inicioSesion_usuario.html"); // Redirigir a login si no está autenticado
    exit();
}

// Obtener el nombre del usuario de la sesión
$nombreUsuario = $_SESSION["nombre"];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lock&Go - Inicio</title>
    <link rel="stylesheet" href="../styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="../Imagenes/image-removebg-preview.png">
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <img src="../Imagenes/image-removebg-preview.png" alt="Lock&Go">
            <span class="logo-text">Lock&Go</span>
        </div>
        <ul class="menu">
            <li><a href="../index.html">Inicio</a></li>
            <li><a href="tablas.php">Tablas</a></li>
            <li><a href="RegistrarA.php">Registrar</a></li>
        </ul>     
        <div class="user-icon"> 
            <a href="../login/logout.php" class="cerrar"><img src="../Imagenes/cerrar.png" alt="Cerrar Sesión"></a>
        </div>
    </nav>

    <h1>Bienvenido, <?php echo htmlspecialchars(ucfirst($nombreUsuario)); ?>!</h1>

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

        <p><a href="../Contactenos/privacidad.html">Aviso de privacidad</a></p>
    </footer>

</body>
</html>
