<?php
// Iniciar la sesión y verificar si el formulario está siendo enviado
session_start();
header('Content-Type: application/json');

// Simulando la reserva exitosa (esto debería interactuar con la base de datos)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Simulando datos
    $casillero_id = $_POST['casillero_id']; // El ID del casillero
    $estudiante_id = $_POST['estudiante_id']; // El ID del estudiante

    // Aquí deberías incluir la lógica para reservar el casillero en la base de datos.
    // Ejemplo de lógica:
    // 1. Verificar si el casillero está disponible.
    // 2. Reservarlo para el estudiante.
    
    // Suponiendo que la reserva fue exitosa:
    $response = [
        'success' => 'El casillero ha sido reservado correctamente.',
        'casillero_id' => $casillero_id // ID del casillero reservado
    ];
    echo json_encode($response);
} else {
    // Si no es un POST, devolvemos un error
    echo json_encode(['error' => 'Método no permitido']);
}
?>
