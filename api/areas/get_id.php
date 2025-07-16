<?php
require_once '../../tareas.php';

$id = $_GET['id'] ?? null;
$areas = new Areas($pdo);

if ($id) {
    $data = $areas->getOneArea($id);
    echo json_encode($data);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'ID is required']);
}
?>
