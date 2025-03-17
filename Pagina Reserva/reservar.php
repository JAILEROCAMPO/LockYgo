<?php
include '../conexion/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id'])) {
    $id = $_POST['id'];
    $query = "UPDATE casilleros SET estado='ocupado' WHERE id=$id AND estado='libre'";
    
    if (mysqli_query($conn, $query) && mysqli_affected_rows($conn) > 0) {
        echo "ok";
    } else {
        echo "error";
    }
}
?>
