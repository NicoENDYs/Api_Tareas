<?php
require_once '../../tareas.php';

// Set JSON response header
header('Content-Type: application/json');

// Only allow PUT/PATCH requests
if (!in_array($_SERVER['REQUEST_METHOD'], ['PUT', 'PATCH'])) {
    http_response_code(405); // Method Not Allowed
    echo json_encode([
        'success' => false,
        'error' => 'Method not allowed. Only PUT/PATCH requests are accepted.'
    ]);
    exit();
}

// Validate database connection
if (!isset($pdo)) {
    http_response_code(500); // Internal Server Error
    echo json_encode([
        'success' => false,
        'error' => 'Database connection unavailable'
    ]);
    exit();
}

// Get and validate ID parameter
$id = $_GET['id'] ?? null;
if (!$id) {
    http_response_code(400); // Bad Request
    echo json_encode([
        'success' => false,
        'error' => 'ID parameter is required'
    ]);
    exit();
}

// Validate ID format
if (!is_numeric($id) || $id <= 0) {
    http_response_code(400); // Bad Request
    echo json_encode([
        'success' => false,
        'error' => 'Invalid ID format. ID must be a positive integer.'
    ]);
    exit();
}

// Get and validate JSON input
$input = file_get_contents("php://input");
if (empty($input)) {
    http_response_code(400); // Bad Request
    echo json_encode([
        'success' => false,
        'error' => 'Request body is required'
    ]);
    exit();
}

$data = json_decode($input, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400); // Bad Request
    echo json_encode([
        'success' => false,
        'error' => 'Invalid JSON format',
        'details' => json_last_error_msg()
    ]);
    exit();
}

// Validate that there's at least one field to update
if (empty($data)) {
    http_response_code(400); // Bad Request
    echo json_encode([
        'success' => false,
        'error' => 'At least one field is required for update'
    ]);
    exit();
}

try {
    $area = new Areas($pdo);
    
    // First check if the area exists
    $existingArea = $area->getOneArea($id);
    if (!$existingArea) {
        http_response_code(404); // Not Found
        echo json_encode([
            'success' => false,
            'error' => 'Area not found'
        ]);
        exit();
    }
    
    $success = $area->updateArea($id, $data);
    
    if ($success) {
        http_response_code(200); // OK
        echo json_encode([
            'success' => true,
            'message' => 'Area updated successfully'
        ]);
    } else {
        http_response_code(400); // Bad Request
        echo json_encode([
            'success' => false,
            'error' => 'Failed to update area'
        ]);
    }
} catch (Exception $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode([
        'success' => false,
        'error' => 'Internal server error',
        'details' => $e->getMessage()
    ]);
}
?>
    