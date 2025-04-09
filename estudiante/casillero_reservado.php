<?php
session_start();
include "../conexion/dbpdo.php"; // Asegúrate de que este archivo tenga la conexión correcta a `lockygo3`

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login/inicioSesion_usuario.html");
    exit();
}

$estudiante_id = $_SESSION['id_usuario'];

// Verificar si el estudiante existe en la base de datos
$query_check_estudiante = "SELECT id FROM estudiantes WHERE id = ?";
$stmt = $conn->prepare($query_check_estudiante);
$stmt->execute([$estudiante_id]);

if (!$stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "<p>Error: El usuario no está registrado en el sistema.</p>";
    exit();
}

// Obtener la reserva activa del estudiante
$query = "SELECT r.id AS reserva_id, c.id AS casillero_id, c.numero, c.estado 
          FROM reservas r 
          JOIN casilleros c ON r.casillero_id = c.id 
          WHERE r.estudiante_id = ? AND r.estado = 'Activa'";

$stmt = $conn->prepare($query);
$stmt->execute([$estudiante_id]);
$reserva = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$reserva) {
    echo "<p>No tienes ningún casillero reservado.</p>";
    exit();
}

// Validar estado del casillero
if (!in_array($reserva['estado'], ['ocupado', 'dañado'])) {
    echo "<p>Error: Tu reserva está activa, pero el casillero figura como '{$reserva['estado']}'.</p>";
    exit();
}

// **Liberar casillero**
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['liberar'])) {
    $conn->beginTransaction();
    try {
        // Verificar si la reserva sigue activa antes de liberarla
        $query_check_reserva = "SELECT id FROM reservas WHERE id = ? AND estado = 'Activa'";
        $stmt = $conn->prepare($query_check_reserva);
        $stmt->execute([$reserva['reserva_id']]);
        
        if (!$stmt->fetch(PDO::FETCH_ASSOC)) {
            throw new Exception("No se encontró una reserva activa para liberar.");
        }

        // Actualizar reserva y casillero
        $query = "UPDATE reservas SET estado = 'Finalizada', fecha_liberacion = NOW() WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$reserva['reserva_id']]);

        $query = "UPDATE casilleros SET estado = 'libre' WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$reserva['casillero_id']]);

        $conn->commit();
        header("Location: index_estudiante.php");
        exit();
    } catch (Exception $e) {
        $conn->rollBack();
        echo "<p>Error al liberar el casillero: " . $e->getMessage() . "</p>";
    }
}

// **Reportar daño**
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reportar'])) {
    $descripcion = trim($_POST['descripcion']);

    if (!empty($descripcion)) {
        $conn->beginTransaction();
        try {
            // Insertar el reporte de daño
            $query = "INSERT INTO reportes_danos (estudiante_id, casillero_id, descripcion) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->execute([$estudiante_id, $reserva['casillero_id'], $descripcion]);

            // Si el casillero está en estado 'ocupado', cambiarlo a 'dañado'
            $query = "UPDATE casilleros SET estado = 'dañado' WHERE id = ? AND estado = 'ocupado'";
            $stmt = $conn->prepare($query);
            $stmt->execute([$reserva['casillero_id']]);

            $conn->commit();
            echo "<p>El daño ha sido reportado. Gracias.</p>";
        } catch (Exception $e) {
            $conn->rollBack();
            echo "<p>Error al reportar el daño: " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p>Por favor, describe el daño antes de enviarlo.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Casillero Reservado</title>
</head>
<body>
    <h2>Tu Casillero Reservado</h2>
    <p>Número de casillero: <strong><?php echo htmlspecialchars($reserva['numero']); ?></strong></p>

    <form method="POST">
        <button type="submit" name="liberar">Liberar Casillero</button>
    </form>

    <h3>Reportar Daño</h3>
    <form method="POST">
        <textarea name="descripcion" placeholder="Describe el daño..." required></textarea><br>
        <button type="submit" name="reportar">Reportar</button>
    </form>
</body>
</html>
