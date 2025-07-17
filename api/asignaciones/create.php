<?php
// create.php - Improved version
require_once '../../tareas.php';

header('Content-Type: application/json');

// Validate request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
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

// Validate required fields
$required_fields = ['empleado_identificacion', 'tarea_id', 'estado_id', 'fecha_asignacion', 'fecha_entrega'];
foreach ($required_fields as $field) {
    if (!isset($data[$field]) || empty($data[$field])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => "Field '$field' is required"]);
        exit();
    }
}


$asignaciones = new Asignaciones($pdo);

try {
    $success = $asignaciones->create($data);
    if ($success) {
        http_response_code(201); // Created
        echo json_encode(['success' => true, 'message' => 'Assignment created successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Failed to create assignment']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Server error: ' . $e->getMessage()]);
}
?>