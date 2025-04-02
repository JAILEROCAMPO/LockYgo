<?php
include '../conexion/dbpdo.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $casillero_id = $_POST['id'];

    // Liberar el casillero en la base de datos
    $query = "UPDATE casilleros SET estado = 'libre' WHERE id = ?";
    $stmt = $conn->prepare($query);
    
    if ($stmt->execute([$casillero_id])) {
        echo "Casillero liberado exitosamente.";
    } else {
        echo "Error al liberar el casillero.";
    }
}
?>
