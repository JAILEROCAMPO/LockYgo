<?php
include "../conexion/db.php"; // Conexión a la base de datos

// Eliminar administrador si se solicita
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $sql = "DELETE FROM administradores WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
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
    $stmt->bind_param("sssssi", $nombre, $apellidos, $celular, $identificacion, $email, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Datos actualizados correctamente'); window.location.href = 'tabla_administradores.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error al actualizar los datos.');</script>";
    }
}

// Obtener la lista de administradores
$sql = "SELECT id, nombre, apellidos, celular, identificacion, email FROM administradores";
$result = $conn->query($sql);
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
    <h2>Lista de Administradores</h2>
    <table border="1">
        <tr>
            <th>Nombre</th>
            <th>Apellidos</th>
            <th>Celular</th>
            <th>Identificación</th>
            <th>Email</th>
            <th>Acciones</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
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
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $admin = $resultado->fetch_assoc();
    ?>
    <h2>Editar Administrador</h2>
    <form method="POST">
        <input type="hidden" name="id" value="<?php echo $admin['id']; ?>">
        <label>Nombre:</label>
        <input type="text" name="nombre" value="<?php echo $admin['nombre']; ?>" required><br>
        <label>Apellidos:</label>
        <input type="text" name="apellidos" value="<?php echo $admin['apellidos']; ?>" required><br>
        <label>Celular:</label>
        <input type="text" name="celular" value="<?php echo $admin['celular']; ?>" required><br>
        <label>Identificación:</label>
        <input type="text" name="identificacion" value="<?php echo $admin['identificacion']; ?>" required><br>
        <label>Email:</label>
        <input type="email" name="email" value="<?php echo $admin['email']; ?>" required><br>
        <button type="submit" name="editar">Actualizar</button>
    </form>
    <?php } ?>
</body>
</html>
<?php $conn->close(); ?>
