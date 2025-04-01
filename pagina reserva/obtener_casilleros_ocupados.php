<?php
include "../conexion/dbpdo.php";

// Consulta para obtener los casilleros ocupados
$sql = "SELECT id, numero FROM casilleros WHERE estado = 'ocupado'";
$stmt = $conn->prepare($sql);

header('Content-Type: application/json'); // Establecer el tipo de contenido como JSON

try {
    $stmt->execute();

    // Obtener los resultados
    $casillerosOcupados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Devolver los resultados en formato JSON
    echo json_encode($casillerosOcupados);
} catch (Exception $e) {
    // Si ocurre un error, lo capturamos y lo devolvemos en formato JSON
    echo json_encode([
        'error' => true,
        'message' => $e->getMessage()
    ]);
}
?>
