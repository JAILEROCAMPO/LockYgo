<?php
include "../conexion/dbpdo.php";
include "../autentificacion/validar_token.php";

if (!isset($_COOKIE["token"])) {
    header("Location: ../login/inicioSesion_usuario.html");
    exit();
}

// Eliminar administrador si se solicita
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $sql = "DELETE FROM administradores WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(1, $id, PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        echo "<script>alert('Administrador eliminado correctamente'); window.location.href = 'tabla_administradores.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error al eliminar el administrador.');</script>";
    }
}

// Editar administrador si se envía el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['editar'])) {
    $id = $_POST["id"];
    $nombre = $_POST["nombre"];
    $apellidos = $_POST["apellidos"];
    $celular = $_POST["celular"];
    $identificacion = $_POST["identificacion"];
    $email = $_POST["email"];

    $sql = "UPDATE administradores SET nombre=?, apellidos=?, celular=?, identificacion=?, email=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(1, $nombre, PDO::PARAM_STR);
    $stmt->bindParam(2, $apellidos, PDO::PARAM_STR);
    $stmt->bindParam(3, $celular, PDO::PARAM_STR);
    $stmt->bindParam(4, $identificacion, PDO::PARAM_STR);
    $stmt->bindParam(5, $email, PDO::PARAM_STR);
    $stmt->bindParam(6, $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "<script>alert('Datos actualizados correctamente'); window.location.href = 'tabla_admin.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error al actualizar los datos.');</script>";
    }
}

// Obtener la lista de administradores
$sql = "SELECT * FROM administradores";
$stmt = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lock&Go - Gestión de Administradores</title>
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
    </nav>

    <h2>Lista de Administradores</h2>
    <table border="1" align="center">
        <tr>
            <th>Nombre</th>
            <th>Apellidos</th>
            <th>Celular</th>
            <th>Identificación</th>
            <th>Email</th>
            <th>Acciones</th>
        </tr>
        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
        <tr>
            <td><?php echo $row["nombre"]; ?></td>
            <td><?php echo $row["apellidos"]; ?></td>
            <td><?php echo $row["celular"]; ?></td>
            <td><?php echo $row["identificacion"]; ?></td>
            <td><?php echo $row["email"]; ?></td>
            <td>
                <a href="?editar=<?php echo $row['id']; ?>">✏ Editar</a>
                <a href="?eliminar=<?php echo $row['id']; ?>" onclick="return confirm('¿Seguro que deseas eliminar este administrador?')">❌ Eliminar</a>
            </td>
        </tr>
        <?php } ?>
    </table>

    <?php if (isset($_GET['editar'])) {
        $id = $_GET['editar'];
        $sql = "SELECT * FROM administradores WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->execute();
        $administrador = $stmt->fetch(PDO::FETCH_ASSOC);
    ?>
    <h2>Editar Administrador</h2>
    <form method="POST">
        <input type="hidden" name="id" value="<?php echo $administrador['id']; ?>">
        <label>Nombre:</label>
        <input type="text" name="nombre" value="<?php echo $administrador['nombre']; ?>" required><br>
        <label>Apellidos:</label>
        <input type="text" name="apellidos" value="<?php echo $administrador['apellidos']; ?>" required><br>
        <label>Celular:</label>
        <input type="text" name="celular" value="<?php echo $administrador['celular']; ?>" required><br>
        <label>Identificación:</label>
        <input type="text" name="identificacion" value="<?php echo $administrador['identificacion']; ?>" required><br>
        <label>Email:</label>
        <input type="email" name="email" value="<?php echo $administrador['email']; ?>" required><br>
        <button type="submit" name="editar">Actualizar</button>
    </form>
    <?php } ?>

    <footer>
        <p>&copy; Servicio Nacional de Aprendizaje SENA - Centro para la Industria de la Comunicación Gráfica (CENIGRAF) - Regional Distrito Capital.</p>
        <p>Dirección: Cra. 32 #15 - 80 – Teléfonos: 546 1500 o 596 0100 Ext.: 15 463</p>
        <p>Atención telefónica: Lunes a viernes 7:00 a.m. a 7:00 p.m. - Sábados 8:00 a.m. a 1:00 p.m.</p>
        <p>Línea de atención al ciudadano: Bogotá +(57) 601 7366060 - Línea gratuita: 018000 910270</p>
        <p>Contacto: <a href="mailto:servicioalciudadano@sena.edu.co">servicioalciudadano@sena.edu.co</a></p>
    </footer>
</body>
</html>
<?php $conn = null; ?> <!-- Cerrar la conexión PDO -->
