<?php
require_once '../../tareas.php';

$id = $_GET['id'] ?? null;
$data = json_decode(file_get_contents("php://input"), true);

$asignaciones = new Asignaciones($pdo);

if ($id) {
    try {
        $success = $asignaciones->update($id, $data);
        echo json_encode(['success' => $success]);
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'ID is required']);
}
?>