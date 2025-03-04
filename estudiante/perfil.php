<?php
session_start();
include('../config/conexion.php'); // Conexión a la base de datos

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login/inicioSesion_usuario.html");
    exit();
}

// Obtener datos del usuario desde la base de datos
$id_usuario = $_SESSION['usuario_id'];
$query = "SELECT nombre, apellido, email, documento, telefono FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();
$usuario = $resultado->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil - Lock&Go</title>
    <link rel="stylesheet" href="../styles.css">
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
            <a href="perfil.php"><img src="../Imagenes/perfil.png" alt="Perfil"></a>
        </div>
    </nav>

    <section class="perfil">
        <div class="perfil-container">
            <img src="../Imagenes/perfil.png" alt="Foto de perfil" class="perfil-img">
            <h1><?php echo $usuario['nombre'] . " " . $usuario['apellido']; ?></h1>
            <p><strong>Correo:</strong> <?php echo $usuario['email']; ?></p>
            <p><strong>Documento:</strong> <?php echo $usuario['documento']; ?></p>
            <p><strong>Teléfono:</strong> <?php echo $usuario['telefono']; ?></p>
            <a href="editar_perfil.php" class="btn">Editar Perfil</a>
        </div>
    </section>

    <footer>
        <p>&copy; Lock&Go - CENIGRAF</p>
    </footer>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
