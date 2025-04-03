<?php
session_start();
header('Content-Type: application/json');

// Incluir archivo de conexión
include "../conexion/dbpdo.php";

try {
    // Verificar método de solicitud
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['error' => 'Método no permitido']);
        exit();
    }

    // Verificar que los parámetros requeridos existan
    if (!isset($_POST['casillero_id']) || !isset($_SESSION['id_usuario'])) {
        echo json_encode(['error' => 'Faltan parámetros']);
        exit();
    }

    $casillero_id = intval($_POST['casillero_id']);
    $estudiante_id = intval($_SESSION['id_usuario']);

    // Iniciar transacción
    $conn->beginTransaction();

    // 1️⃣ Verificar si el estudiante ya tiene una reserva activa
    $stmt = $conn->prepare("SELECT id FROM reservas WHERE estudiante_id = ? AND estado = 'Activa'");
    $stmt->execute([$estudiante_id]);
    if ($stmt->fetch()) {
        $conn->rollBack();
        echo json_encode(['error' => 'Ya tienes una reserva activa']);
        exit();
    }

    // 2️⃣ Verificar si el casillero está disponible
    $stmt = $conn->prepare("SELECT id FROM casilleros WHERE id = ? AND estado = 'libre'");
    $stmt->execute([$casillero_id]);
    if (!$stmt->fetch()) {
        $conn->rollBack();
        echo json_encode(['error' => 'El casillero no está disponible']);
        exit();
    }

    // 3️⃣ Insertar la nueva reserva
    $stmt = $conn->prepare("INSERT INTO reservas (estudiante_id, casillero_id, estado) VALUES (?, ?, 'Activa')");
    $stmt->execute([$estudiante_id, $casillero_id]);
    $reserva_id = $conn->lastInsertId();

    // 4️⃣ Actualizar el estado del casillero a 'ocupado' y asignar la reserva
    $stmt = $conn->prepare("UPDATE casilleros SET estado = 'ocupado', ultima_reserva_id = ? WHERE id = ?");
    $stmt->execute([$reserva_id, $casillero_id]);

    // Confirmar transacción
    $conn->commit();

    echo json_encode(['success' => 'Casillero reservado con éxito']);

} catch (Exception $e) {
    $conn->rollBack();
    echo json_encode(['error' => 'Error en la reserva: ' . $e->getMessage()]);
}
?>
