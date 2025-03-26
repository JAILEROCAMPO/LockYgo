<?php
include '../conexion/db.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $query = "UPDATE casilleros SET estado = 'libre' WHERE numero = $id";
    if (mysqli_query($conn, $query)) {
        echo 'ok';
    } else {
        echo 'error';
    }
}
?>
