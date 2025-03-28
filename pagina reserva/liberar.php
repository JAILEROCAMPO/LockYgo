<?php
include '../conexion/dbpdo.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    try {
        $query = "UPDATE casilleros SET estado = 'libre' WHERE numero = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo 'ok';
        } else {
            echo 'error';
        }
    } catch (PDOException $e) {
        echo 'error: ' . $e->getMessage();
    }
}
?>
