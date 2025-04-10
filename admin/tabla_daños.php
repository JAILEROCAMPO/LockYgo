<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["autenticado"])) {
    header("Location: ../login/inicioSesion_usuario.html"); // Redirigir a login si no está autenticado
    exit();
}

// Obtener el nombre del usuario de la sesión
$nombreUsuario = $_SESSION["nombre"];

include "../conexion/dbpdo.php";

// Consultar reportes de daños
$sql_reportes = "
SELECT 
    r.id AS reporte_id, 
    c.numero AS numero_casillero,
    rd.fecha_reporte,
    e.nombre AS estudiante_nombre,
    e.celular AS estudiante_celular,
    rd.descripcion,
    rd.estado AS estado_reporte,
    a.nombre AS administrador_nombre
FROM reportes_danos rd
JOIN casilleros c ON rd.casillero_id = c.id
JOIN estudiantes e ON rd.estudiante_id = e.id
LEFT JOIN administradores a ON rd.administrador_id = a.id
ORDER BY rd.fecha_reporte DESC
";
$stmt_reportes = $conn->query($sql_reportes);
$reportes = $stmt_reportes->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lock&Go - Reportes de Daños</title>
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
            <li><a href="admin_reservas.php">Casilleros reservados</a></li>
        </ul>     
        <div class="user-icon"> 
            <a href="../login/logout.php" class="cerrar"><img src="../Imagenes/cerrar.png" alt="Cerrar Sesión"></a>
        </div>
    </nav>

    <h1>Reportes de Daños</h1>
    <table border="1" style="margin: 0 auto;">
        <thead>
            <tr>
                <th>Casillero</th>
                <th>Hora del Reporte</th>
                <th>Estudiante</th>
                <th>Celular</th>
                <th>Descripción</th>
                <th>Estado</th>
                <th>Administrador</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($reportes as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['numero_casillero']) ?></td>
                <td><?= htmlspecialchars($row['fecha_reporte']) ?></td>
                <td><?= htmlspecialchars($row['estudiante_nombre']) ?></td>
                <td><?= htmlspecialchars($row['estudiante_celular']) ?></td>
                <td><?= htmlspecialchars($row['descripcion']) ?></td>
                <td><?= htmlspecialchars($row['estado_reporte']) ?></td>
                <td><?= htmlspecialchars($row['administrador_nombre']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <footer>
        <p>&copy; Servicio Nacional de Aprendizaje SENA - Centro para la Industria de la Comunicación Gráfica (CENIGRAF) - Regional Distrito Capital.</p>
        <p>Dirección: Cra. 32 #15 - 80 – Teléfonos: 546 1500 o 596 0100 Ext.: 15 463</p>
        <p>Atención telefónica: Lunes a viernes 7:00 a.m. a 7:00 p.m. - Sábados 8:00 a.m. a 1:00 p.m.</p>
        <p>Línea de atención al ciudadano: Bogotá +(57) 601 7366060 - Línea gratuita: 018000 910270</p>
        <p>Contacto: <a href="mailto:servicioalciudadano@sena.edu.co">servicioalciudadano@sena.edu.co</a></p>
    </footer>
</body>
</html>
