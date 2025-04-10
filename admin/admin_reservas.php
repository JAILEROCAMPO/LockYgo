<?php
require_once '../conexion/dbpdo.php';

// Liberar casillero si se pasó un ID por GET
if (isset($_GET['liberar'])) {
    $id_reserva = intval($_GET['liberar']);

    // Cambiar estado de reserva a "Finalizada"
    $sql_liberar = "UPDATE reservas SET estado = 'Finalizada', fecha_liberacion = NOW() WHERE id = ?";
    $stmt_liberar = $conn->prepare($sql_liberar);
    $stmt_liberar->execute([$id_reserva]);

    // Cambiar estado del casillero a "libre"
    $sql_casillero = "
        UPDATE casilleros 
        SET estado = 'libre'
        WHERE id = (
            SELECT casillero_id FROM reservas WHERE id = ?
        )";
    $stmt_casillero = $conn->prepare($sql_casillero);
    $stmt_casillero->execute([$id_reserva]);

    // Redireccionar para evitar reenvío de formulario
    header("Location: admin_reservas.php");
    exit();
}

// Consultar reservas activas
$sql = "
SELECT 
    r.id, 
    e.nombre, 
    e.celular, 
    e.ficha, 
    e.programa_formacion, 
    e.jornada, 
    c.numero AS numero_casillero
FROM reservas r
JOIN estudiantes e ON r.estudiante_id = e.id
JOIN casilleros c ON r.casillero_id = c.id
WHERE r.estado = 'Activa'
ORDER BY r.fecha_reserva DESC
";
$stmt = $conn->query($sql);
$reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            <li><a href="admin_reservas.php">Casilleros reservados</a></li>

        </ul>     
        <div class="user-icon"> 
            <a href="../login/logout.php" class="cerrar"><img src="../Imagenes/cerrar.png" alt="Cerrar Sesión"></a>
        </div>
    </nav>
    <h1>Reservas Activas</h1>
    <table border="1" style="margin: 0 auto;">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Celular</th>
                <th>Ficha</th>
                <th>Programa</th>
                <th>Jornada</th>
                <th>Casillero</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($reservas as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['nombre']) ?></td>
                <td><?= htmlspecialchars($row['celular']) ?></td>
                <td><?= htmlspecialchars($row['ficha']) ?></td>
                <td><?= htmlspecialchars($row['programa_formacion']) ?></td>
                <td><?= htmlspecialchars($row['jornada']) ?></td>
                <td><?= htmlspecialchars($row['numero_casillero']) ?></td>
                <td>
                    <a href="?liberar=<?= $row['id'] ?>" onclick="return confirm('¿Estás seguro de liberar este casillero?')">🔓 Liberar</a>
                </td>
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
