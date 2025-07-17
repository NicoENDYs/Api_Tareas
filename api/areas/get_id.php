<?php
require_once '../../tareas.php';

// Set JSON response header
header('Content-Type: application/json');

// Only allow GET requests
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405); // Method Not Allowed
    echo json_encode([
        'success' => false,
        'error' => 'Method not allowed. Only GET requests are accepted.'
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

try {
    $areas = new Areas($pdo);
    $data = $areas->getOneArea($id);
    
    if ($data) {
        http_response_code(200); // OK
        echo json_encode([
            'success' => true,
            'data' => $data
        ]);
    } else {
        http_response_code(404); // Not Found
        echo json_encode([
            'success' => false,
            'error' => 'Area not found'
        ]);
    }
} catch (Exception $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode([
        'success' => false,
        'error' => 'Failed to retrieve area',
        'details' => $e->getMessage()
    ]);
}
?>