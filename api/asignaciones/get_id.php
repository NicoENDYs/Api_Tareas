<?php
require_once '../../tareas.php';

$id = $_GET['id'] ?? null;
$asignaciones = new Asignaciones($pdo);

if ($id) {
    try {
        $data = $asignaciones->getOne($id);
        if ($data) {
            echo json_encode(['success' => true, 'data' => $data]);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'error' => 'Asignación no encontrada']);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'ID is required']);
}
?>