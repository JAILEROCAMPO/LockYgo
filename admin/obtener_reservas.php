<?php
include '../conexion/db.php';

header('Content-Type: application/json');

try {
    $query = "SELECT e.nombre, e.apellidos, e.programa_formacion, e.ficha, c.numero AS casillero
              FROM reservas r
              INNER JOIN estudiantes e ON r.id_estudiante = e.id
              INNER JOIN casilleros c ON r.id_casillero = c.id
              WHERE c.estado = 'ocupado'";
    
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($reservas);
} catch (PDOException $e) {
    echo json_encode(["error" => "Error al obtener las reservas: " . $e->getMessage()]);
}
