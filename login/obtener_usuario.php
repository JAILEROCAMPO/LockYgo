<?php
session_start();
header('Content-Type: application/json');

echo json_encode(['id_usuario' => $_SESSION['id_usuario'] ?? null]);
?>
