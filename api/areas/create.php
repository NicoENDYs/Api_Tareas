<?php
require_once '../../tareas.php';

// Set JSON response header
header('Content-Type: application/json');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode([
        'success' => false,
        'error' => 'Method not allowed. Only POST requests are accepted.'
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

// Validate required fields (adjust according to your Areas model)
$requiredFields = ['nombre']; // Add your required fields here
foreach ($requiredFields as $field) {
    if (!isset($data[$field]) || empty(trim($data[$field]))) {
        http_response_code(400); // Bad Request
        echo json_encode([
            'success' => false,
            'error' => "Field '$field' is required"
        ]);
        exit();
    }
}

try {
    $area = new Areas($pdo);
    $success = $area->createArea($data);
    
    if ($success) {
        http_response_code(201); // Created
        echo json_encode([
            'success' => true,
            'message' => 'Area created successfully',
            'id' => $success // If your createArea method returns the new ID
        ]);
    } else {
        http_response_code(400); // Bad Request
        echo json_encode([
            'success' => false,
            'error' => 'Failed to create area'
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