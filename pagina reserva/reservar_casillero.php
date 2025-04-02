<?php
session_start();
header('Content-Type: application/json');

include "../conexion/dbpdo.php";

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['error' => 'Método no permitido']);
        exit();
    }

    if (!isset($_POST['casillero_id']) || !isset($_SESSION['id_usuario'])) {
        echo json_encode(['error' => 'Faltan parámetros']);
        exit();
    }

    $casillero_id = $_POST['casillero_id'];
    $estudiante_id = $_SESSION['id_usuario'];

    // Verificar si el estudiante ya tiene una reserva activa
    $query_check_reserva = "SELECT id FROM reservas WHERE estudiante_id = ? AND estado = 'Activa'";
    $stmt = $conn->prepare($query_check_reserva);
    $stmt->execute([$estudiante_id]);
    $reserva_existente = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($reserva_existente) {
        echo json_encode(['error' => 'Ya tienes una reserva activa']);
        exit();
    }

    // Verificar si el casillero está disponible
    $query_check_casillero = "SELECT estado FROM casilleros WHERE id = ? AND estado = 'libre'";
    $stmt = $conn->prepare($query_check_casillero);
    $stmt->execute([$casillero_id]);
    $casillero = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$casillero) {
        echo json_encode(['error' => 'El casillero no está disponible']);
        exit();
    }

    // Insertar la nueva reserva
    $query_reserva = "INSERT INTO reservas (estudiante_id, casillero_id, estado) VALUES (?, ?, 'Activa')";
    $stmt = $conn->prepare($query_reserva);
    $stmt->execute([$estudiante_id, $casillero_id]);

    echo json_encode(['success' => 'Casillero reservado con éxito']);

} catch (Exception $e) {
    echo json_encode(['error' => 'Error en la reserva: ' . $e->getMessage()]);
}