<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["autenticado"])) {
    header("Location: ../login/inicioSesion_usuario.html");
    exit();
}

// Obtener el ID del usuario de la sesión
$idUsuario = $_SESSION["id_usuario"];
include "../conexion/dbpdo.php"; // Conexión a la base de datos

// Obtener los datos actuales del usuario
$sql = "SELECT nombre, apellidos, celular, identificacion, email FROM estudiantes WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bindParam(1, $idUsuario, PDO::PARAM_INT);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Si se envía el formulario, actualizar los datos
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $apellidos = $_POST["apellidos"];
    $celular = $_POST["celular"];
    $identificacion = $_POST["identificacion"];
    $email = $_POST["email"];
    
    $sql = "UPDATE estudiantes SET nombre=?, apellidos=?, celular=?, identificacion=?, email=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(1, $nombre, PDO::PARAM_STR);
    $stmt->bindParam(2, $apellidos, PDO::PARAM_STR);
    $stmt->bindParam(3, $celular, PDO::PARAM_STR);
    $stmt->bindParam(4, $identificacion, PDO::PARAM_STR);
    $stmt->bindParam(5, $email, PDO::PARAM_STR);
    $stmt->bindParam(6, $idUsuario, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "<script>alert('Perfil actualizado correctamente'); window.location.href = 'editar_perfil.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error al actualizar el perfil.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<html lang="en">
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
            <li><a href="index_estudiante.php">Inicio</a></li>
            <li><a href="../Pagina Reserva/bienvenida.php">Casilleros</a></li>
            <li><a href="../contactenos/contacto.html">Contacto</a></li>
        </ul>
        <div class="user-icon"> 
            <a href="editar_perfil.php"><img src="../Imagenes/perfil.png" alt="Perfil"></a>
            <a href="../login/logout.php" class="cerrar"><img src="../Imagenes/cerrar.png" alt="Cerrar Sesion"></a>
        </div>

    </nav>
    <div class="register-page">
        <div class="register-container">
            <h2>editar Perfil</h2>
            <form method="POST" class="register-form">
                    <div class="register-input-group">
                        <label>Nombre:</label>
                        <input type="text" name="nombre" value="<?php echo $usuario['nombre']; ?>" required><br>
                    </div> 

                    <div class="register-input-group"> 
                        <label>Apellidos:</label>
                        <input type="text" name="apellidos" value="<?php echo $usuario['apellidos']; ?>" required><br>
                    </div>   

                    <div class="register-input-group">
                        <label>Celular:</label>
                        <input type="text" name="celular" value="<?php echo $usuario['celular']; ?>" required><br>
                    </div>  

                    <div class="register-input-group">
                        <label>Identificación:</label>
                        <input type="text" name="identificacion" value="<?php echo $usuario['identificacion']; ?>" required><br>
                    </div>         
                    
                    <div class="register-input-group">
                        <label>Email:</label>
                        <input type="email" name="email" value="<?php echo $usuario['email']; ?>" required><br>
                    </div>   
                        <button type="submit">Actualizar Perfil</button>
                </form>
            
            
            
        
            

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

        <p><a href="../Contactenos/privacidad.html">Aviso de privacidad</a></p>
    </footer>
</body>
</html>

<?php $conn = null; ?>
