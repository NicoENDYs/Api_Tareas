<?php
require_once '../../tareas.php';

$id = $_GET['id'] ?? null;
$data = json_decode(file_get_contents("php://input"), true);

$estado = new Estados($pdo);
$success = $estado->updateEstado($id, $data);

echo json_encode(['success' => $success]);
?>
