<?php
session_start();
include "../conexion/dbpdo.php";

if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['activa' => false]);
    exit();
}

$estudiante_id = $_SESSION['id_usuario'];

$query = "SELECT id FROM reservas WHERE estudiante_id = ? AND estado = 'Activa'";
$stmt = $conn->prepare($query);
$stmt->execute([$estudiante_id]);

$reserva = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode(['activa' => $reserva ? true : false]);
