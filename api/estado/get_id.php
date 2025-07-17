<?php
require_once '../../tareas.php';

$id = $_GET['id'] ?? null;
$estado = new Estados($pdo);

if ($id) {
    $data = $estado->getOneEstado($id);
    echo json_encode($data);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'ID is required']);
}
?>
