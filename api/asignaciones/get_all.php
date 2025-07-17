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

$asignaciones = new Asignaciones($pdo);

try {
    $asignacionesData = $asignaciones->getAll();

    if ($asignacionesData && count($asignacionesData) > 0) {
        http_response_code(200);
        echo json_encode([
            'success' => true, 
            'data' => $asignacionesData,
            'count' => count($asignacionesData)
        ]);
    } else {
        http_response_code(200); // Changed from 204 to 200 with empty array
        echo json_encode([
            'success' => true, 
            'data' => [],
            'count' => 0,
            'message' => 'No assignments found'
        ]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Error retrieving assignments',
        'details' => $e->getMessage()
    ]);
}
?>