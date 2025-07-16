<?php
require_once '../../tareas.php';

$id = $_GET['id'] ?? null;
$data = json_decode(file_get_contents("php://input"), true);

$tareas = new Tareas($pdo);
$success = $tareas->update($id, $data);

echo json_encode(['success' => $success]);
?>
