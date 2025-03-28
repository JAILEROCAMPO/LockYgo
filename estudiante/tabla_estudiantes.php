<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["autenticado"])) {
    header("Location: ../login/inicioSesion_usuario.html"); // Redirigir a login si no está autenticado
    exit();
}
// Obtener el nombre del usuario de la sesión
$nombreUsuario = $_SESSION["nombre"];
include "../conexion/dbpdo.php"; // Conexión a la base de datos


// Eliminar estudiante si se solicita
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $sql = "DELETE FROM estudiantes WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(1, $id, PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        echo "<script>alert('Estudiante eliminado correctamente'); window.location.href = 'tabla_estudiantes.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error al eliminar el estudiante.');</script>";
    }
}

// Editar estudiante si se envía el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['editar'])) {
    $id = $_POST["id"];
    $nombre = $_POST["nombre"];
    $apellidos = $_POST["apellidos"];
    $celular = $_POST["celular"];
    $identificacion = $_POST["identificacion"];
    $ficha = $_POST["ficha"];
    $email = $_POST["email"];
    $jornada = $_POST["jornada"];
    $programa_formacion = $_POST["programa_formacion"];

    $sql = "UPDATE estudiantes SET nombre=?, apellidos=?, celular=?, identificacion=?, ficha=?, email=?, jornada=?, programa_formacion=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(1, $nombre, PDO::PARAM_STR);
    $stmt->bindParam(2, $apellidos, PDO::PARAM_STR);
    $stmt->bindParam(3, $celular, PDO::PARAM_STR);
    $stmt->bindParam(4, $identificacion, PDO::PARAM_STR);
    $stmt->bindParam(5, $ficha, PDO::PARAM_STR);
    $stmt->bindParam(6, $email, PDO::PARAM_STR);
    $stmt->bindParam(7, $jornada, PDO::PARAM_STR);
    $stmt->bindParam(8, $programa_formacion, PDO::PARAM_STR);
    $stmt->bindParam(9, $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "<script>alert('Datos actualizados correctamente'); window.location.href = 'tabla_estudiantes.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error al actualizar los datos.');</script>";
    }
}

// Obtener la lista de estudiantes
$sql = "SELECT * FROM estudiantes";
$stmt = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lock&Go - Gestión de Estudiantes</title>
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

    <h2>Lista de Estudiantes</h2>
    <table border="1" align="center">
        <tr>
            <th>Nombre</th>
            <th>Apellidos</th>
            <th>Celular</th>
            <th>Identificación</th>
            <th>Ficha</th>
            <th>Email</th>
            <th>Jornada</th>
            <th>Programa</th>
            <th>Acciones</th>
        </tr>
        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
        <tr>
            <td><?php echo $row["nombre"]; ?></td>
            <td><?php echo $row["apellidos"]; ?></td>
            <td><?php echo $row["celular"]; ?></td>
            <td><?php echo $row["identificacion"]; ?></td>
            <td><?php echo $row["ficha"]; ?></td>
            <td><?php echo $row["email"]; ?></td>
            <td><?php echo $row["jornada"]; ?></td>
            <td><?php echo $row["programa_formacion"]; ?></td>
            <td>
                <a href="?editar=<?php echo $row['id']; ?>">✏ Editar</a>
                <a href="?eliminar=<?php echo $row['id']; ?>" onclick="return confirm('¿Seguro que deseas eliminar este estudiante?')">❌ Eliminar</a>
            </td>
        </tr>
        <?php } ?>
    </table>

    <?php if (isset($_GET['editar'])) {
        $id = $_GET['editar'];
        $sql = "SELECT * FROM estudiantes WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->execute();
        $estudiante = $stmt->fetch(PDO::FETCH_ASSOC);
    ?>
    <h2>Editar Estudiante</h2>
    <form method="POST">
        <input type="hidden" name="id" value="<?php echo $estudiante['id']; ?>">
        <label>Nombre:</label>
        <input type="text" name="nombre" value="<?php echo $estudiante['nombre']; ?>" required><br>
        <label>Apellidos:</label>
        <input type="text" name="apellidos" value="<?php echo $estudiante['apellidos']; ?>" required><br>
        <label>Celular:</label>
        <input type="text" name="celular" value="<?php echo $estudiante['celular']; ?>" required><br>
        <label>Identificación:</label>
        <input type="text" name="identificacion" value="<?php echo $estudiante['identificacion']; ?>" required><br>
        <label>Ficha:</label>
        <input type="text" name="ficha" value="<?php echo $estudiante['ficha']; ?>" required><br>
        <label>Email:</label>
        <input type="email" name="email" value="<?php echo $estudiante['email']; ?>" required><br>
        <label>Jornada:</label>
        <select name="jornada" required>
            <option value="Mañana" <?php echo ($estudiante['jornada'] == 'Mañana') ? 'selected' : ''; ?>>Mañana</option>
            <option value="Tarde" <?php echo ($estudiante['jornada'] == 'Tarde') ? 'selected' : ''; ?>>Tarde</option>
            <option value="Noche" <?php echo ($estudiante['jornada'] == 'Noche') ? 'selected' : ''; ?>>Noche</option>
        </select><br>
        <label>Programa de Formación:</label>
        <input type="text" name="programa_formacion" value="<?php echo $estudiante['programa_formacion']; ?>" required><br>
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
    <script src="../autentificacion/validar_sesion.js"></script>
</body>
</html>
<?php $conn = null; ?> <!-- Cerrar la conexión PDO -->
