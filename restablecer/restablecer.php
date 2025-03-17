<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../conexion/db.php';

if (isset($_GET["token"])) {
    $token = trim($_GET["token"]);

    // Buscar el token en ambas tablas con UNION
    $sql = "SELECT 'estudiante' AS tipo_usuario, id FROM estudiantes WHERE token = ? AND expira_token > NOW()
            UNION
            SELECT 'admin' AS tipo_usuario, id FROM administradores WHERE token = ? AND expira_token > NOW()";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $token, $token);
    $stmt->execute();
    $stmt->store_result();

    // Verificar si hay resultados
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($tipo_usuario, $id);
        $stmt->fetch();
    } else {
        echo "<script>alert('Token inválido o expirado.'); window.location.href = '../login/inicioSesion_usuario.html';</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña - Lock&Go</title>
    <link rel="stylesheet" href="../styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="../Imagenes/image-removebg-preview.png">
</head>
<body>

    <!-- Barra de navegación -->
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

    <!-- Contenedor de recuperación de contraseña -->
    <div class="login-page login-container">
        <h2>Recupera tu cuenta</h2>
        <form action="actualizar_contrasena.php" method="POST">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
            <input type="hidden" name="tipo_usuario" value="<?php echo htmlspecialchars($tipo_usuario); ?>">
            <label for="password">Nueva Contraseña:</label>
            <input type="password" name="password" required>
            <button type="submit">Actualizar Contraseña</button>
        </form>
        <p>¿Tienes cuenta? <a href="../login/inicioSesion_usuario.html">Iniciar sesión</a></p>
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
        <p><a href="../Contactenos/privacidad.html">Aviso de privacidad</a></p>
    </footer>

</body>
</html>
