
<?php
include '../conexion/dbpdo.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    // Liberar casillero
    $casillero_id = $_POST['id'];
    $query = "UPDATE casilleros SET estado = 'libre' WHERE id = ?";
    $stmt = $conn->prepare($query);
    echo ($stmt->execute([$casillero_id])) ? "Casillero liberado exitosamente." : "Error al liberar el casillero.";
    exit;
}

// Obtener casilleros reservados
$query = "SELECT e.nombre, e.programa_formacion, e.ficha, c.numero, c.id AS casillero_id
          FROM reservas r
          INNER JOIN estudiantes e ON r.estudiante_id = e.id
          INNER JOIN casilleros c ON r.casillero_id = c.id
          WHERE c.estado = 'ocupado'";
$stmt = $conn->prepare($query);
$stmt->execute();
$reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservas - Panel de Administrador</title>
    <link rel="stylesheet" href="../styles.css">
    <script>
        function liberarCasillero(casilleroId) {
            fetch('admin_reservas.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'id=' + casilleroId
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                location.reload(); // Recargar la página para actualizar la tabla
            });
        }
    </script>
</head>
<body>
    <h2>Reservas de Casilleros</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Programa de Formación</th>
                <th>Número de Ficha</th>
                <th>Casillero</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($reservas): ?>
                <?php foreach ($reservas as $reserva): ?>
                    <tr>
                        <td><?= htmlspecialchars($reserva['nombre']) ?></td>
                        <td><?= htmlspecialchars($reserva['programa_formacion']) ?></td>
                        <td><?= htmlspecialchars($reserva['ficha']) ?></td>
                        <td><?= htmlspecialchars($reserva['numero']) ?></td>
                        <td><button onclick="liberarCasillero(<?= $reserva['casillero_id'] ?>)">Liberar</button></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5">No hay reservas activas</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservas - Panel de Administrador</title>
    <link rel="stylesheet" href="../styles.css">
    <script>
        function cargarReservas() {
            fetch('obtener_reservas.php')
                .then(response => response.text())
                .then(data => {
                    document.getElementById('tabla-reservas').innerHTML = data;
                });
        }

        function liberarCasillero(casilleroId) {
            fetch('liberar_casillero.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'id=' + casilleroId
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                cargarReservas(); // Recargar la tabla después de liberar
            });
        }

        setInterval(cargarReservas, 5000); // Actualiza cada 5 segundos
    </script>
</head>
<body>
    <h2>Reservas de Casilleros</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Programa de Formación</th>
                <th>Número de Ficha</th>
                <th>Casillero</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody id="tabla-reservas">
            <!-- Se llenará con obtener_reservas.php -->
        </tbody>
    </table>
</body>
</html>
