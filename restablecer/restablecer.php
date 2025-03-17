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

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($tipo_usuario, $id);
        $stmt->fetch();
    } else {
        echo "<script>alert('Token inválido o expirado.'); window.location.href = 'inicioSesion_usuario.html';</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Contraseña</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>

    <div class="login-page login-container">
        <h2>Recuperar tu cuenta</h2>
        <form action="actualizar_contrasena.php" method="POST">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
            <input type="hidden" name="tipo_usuario" value="<?php echo htmlspecialchars($tipo_usuario); ?>">
            <label for="password">Nueva Contraseña:</label>
            <input type="password" name="password" required>
            <button type="submit">Actualizar Contraseña</button>
        </form>
    </div>

</body>
</html>
