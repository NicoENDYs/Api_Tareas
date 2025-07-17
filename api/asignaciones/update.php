<?php
require_once '../../tareas.php';

header('Content-Type: application/json');

// Validate request method
if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
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

// Get and validate input data
$input = file_get_contents("php://input");
if (empty($input)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'No data provided']);
    exit();
}

$data = json_decode($input, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid JSON data']);
    exit();
}


$asignaciones = new Asignaciones($pdo);

try {
    // First check if assignment exists
    $existing = $asignaciones->getOne($id);
    if (!$existing) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Assignment not found']);
        exit();
    }

    $success = $asignaciones->update($id, $data);
    if ($success) {
        http_response_code(200);
        echo json_encode(['success' => true, 'message' => 'Assignment updated successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Failed to update assignment']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Server error: ' . $e->getMessage()]);
}
?>