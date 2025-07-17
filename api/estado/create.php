<?php
require_once '../../tareas.php';

$data = json_decode(file_get_contents("php://input"), true);

$estado = new Estados($pdo);
$success = $estado->createEstado($data);

echo json_encode(['success' => $success]);
?>
