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

try {
    $areas = new Areas($pdo);
    $result = $areas->getAreas();
    
    if ($result && count($result) > 0) {
        http_response_code(200); // OK
        echo json_encode([
            'success' => true,
            'data' => $result,
            'count' => count($result)
        ]);
    } else {
        http_response_code(200); // OK (empty list is still successful)
        echo json_encode([
            'success' => true,
            'data' => [],
            'count' => 0,
            'message' => 'No areas found'
        ]);
    }
} catch (Exception $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode([
        'success' => false,
        'error' => 'Failed to retrieve areas',
        'details' => $e->getMessage()
    ]);
}
?>