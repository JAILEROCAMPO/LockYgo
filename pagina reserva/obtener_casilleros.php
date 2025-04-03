<?php
header('Content-Type: application/json');
include "../conexion/dbpdo.php"; // Asegúrate de que la conexión esté correcta

$response = [];

try {
    // Verificar si la conexión existe
    if (!$conn) {
        throw new Exception("No se pudo conectar a la base de datos.");
    }

    // Consulta para obtener casilleros ocupados
    $query = "SELECT id FROM casilleros WHERE estado = 'ocupado'";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $casilleros = $stmt->fetchAll(PDO::FETCH_COLUMN); // Devuelve solo los IDs ocupados

    $response['ocupados'] = $casilleros;
} catch (PDOException $e) {
    $response['error'] = "Error en la consulta: " . $e->getMessage();
} catch (Exception $e) {
    $response['error'] = $e->getMessage();
}

// Asegurar que la salida sea solo JSON limpio
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>
