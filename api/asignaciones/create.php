<?php
require_once '../../tareas.php';

$data = json_decode(file_get_contents("php://input"), true);

$asignaciones = new Asignaciones($pdo);

try {
    $success = $asignaciones->create($data);
    echo json_encode(['success' => $success]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>