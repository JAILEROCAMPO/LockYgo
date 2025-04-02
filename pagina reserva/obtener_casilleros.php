<?php
header('Content-Type: application/json');
include "../conexion/dbpdo.php"; // Asegúrate de incluir la conexión a la BD

$response = [];

try {
    $query = "SELECT numero FROM casilleros WHERE estado = 'ocupado'";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $casilleros = $stmt->fetchAll(PDO::FETCH_COLUMN); // Devuelve solo los números ocupados

    $response['ocupados'] = $casilleros;
} catch (Exception $e) {
    $response['error'] = "Error en la consulta: " . $e->getMessage();
}

// Asegurar que la salida sea solo JSON
echo json_encode($response);
?>
