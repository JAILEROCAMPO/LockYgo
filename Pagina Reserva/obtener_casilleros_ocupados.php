<?php
include "../conexion/db.php"; // Conexión a la base de datos

error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');


$sql = "SELECT numero FROM casilleros WHERE estado = 'Ocupado'";
$result = $conn->query($sql);

$casillerosOcupados = [];
while ($row = $result->fetch_assoc()) {
    $casillerosOcupados[] = $row['numero'];
}

echo json_encode($casillerosOcupados);
$conn->close();
?>
