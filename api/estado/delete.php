<?php
require_once '../../tareas.php';

$id = $_GET['id'] ?? null;
$estado = new Estados($pdo);

if ($id) {
    $success = $estado->deleteEstado($id);
    echo json_encode(['success' => $success]);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'ID is required']);
}
?>
