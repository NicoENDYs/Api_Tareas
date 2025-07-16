<?php
require_once '../../tareas.php';

$id = $_GET['id'] ?? null;
$tareas = new Tareas($pdo);

if ($id) {
    $data = $tareas->getOne($id);
    echo json_encode($data);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'ID is required']);
}
?>
