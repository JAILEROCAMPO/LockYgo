<?php
include '../conexion/dbpdo.php';

$query = "SELECT e.nombre, e.programa_formacion, e.ficha, c.numero, c.id AS casillero_id
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
                    <td><button onclick='liberarCasillero({$row['casillero_id']})'>Liberar</button></td>
                </tr>";
}

echo $salida ?: "<tr><td colspan='5'>No hay reservas activas</td></tr>";
?>
