<?php
require_once '../../tareas.php';

header('Content-Type: application/json');

// Validate request method
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit();
}

// Validate database connection
if (!isset($pdo)) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database connection not available']);
    exit();
}

$id = $_GET['id'] ?? null;

if (!$id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'ID is required']);
    exit();
}

// Validate ID is numeric
if (!is_numeric($id)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'ID must be a valid number']);
    exit();
}

$asignaciones = new Asignaciones($pdo);

try {
    $data = $asignaciones->getOne($id);
    if ($data) {
        http_response_code(200);
        echo json_encode(['success' => true, 'data' => $data]);
    } else {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Assignment not found']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Server error: ' . $e->getMessage()]);
}

?>