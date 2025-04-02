<?php
include '../conexion/dbpdo.php';

$query = "SELECT e.nombre, e.programa_formacion, e.ficha, c.numero 
          FROM reservas r
          INNER JOIN estudiantes e ON r.estudiante_id = e.id
          INNER JOIN casilleros c ON r.casillero_id = c.id
          WHERE c.estado = 'ocupado'";

$stmt = $conn->prepare($query);
$stmt->execute();

$salida = "";
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $salida .= "<tr>
                    <td>{$row['nombre']}</td>
                    <td>{$row['programa_formacion']}</td>
                    <td>{$row['ficha']}</td>
                    <td>{$row['numero']}</td>
                </tr>";
}

echo $salida ?: "<tr><td colspan='4'>No hay reservas activas</td></tr>";
?>
