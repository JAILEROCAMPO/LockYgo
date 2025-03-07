<?php
include "../conexion/db.php"; // Conexión a la base de datos
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $casillero_id = $_POST['casillero_id'];
    $estudiante_id = $_POST['estudiante_id'];

    // Verificar si el casillero está disponible
    $sql_verificar = "SELECT estado FROM casilleros WHERE id = ? AND estado = 'Disponible'";
    $stmt_verificar = $conn->prepare($sql_verificar);
    $stmt_verificar->bind_param("i", $casillero_id);
    $stmt_verificar->execute();
    $resultado = $stmt_verificar->get_result();

    if ($resultado->num_rows > 0) {
        // Reservar el casillero
        $sql_reservar = "CALL ReservarCasillero(?, ?)";
        $stmt_reservar = $conn->prepare($sql_reservar);
        $stmt_reservar->bind_param("ii", $estudiante_id, $casillero_id);
        if ($stmt_reservar->execute()) {
            echo json_encode(["success" => true, "message" => "Casillero reservado exitosamente."]);
        } else {
            echo json_encode(["success" => false, "message" => "Error al reservar casillero."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "El casillero ya está ocupado."]);
    }

    $stmt_verificar->close();
    $conn->close();
}
?>
