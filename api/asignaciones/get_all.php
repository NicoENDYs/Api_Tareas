<?php
require_once '../../tareas.php';

header('Content-Type: application/json');

if (!isset($pdo)) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Error interno: conexión a base de datos no disponible']);
    exit();
}
    
$asignaciones = new Asignaciones($pdo);

try {
    $asignacionesData = $asignaciones->getAll();

    if ($asignacionesData && count($asignacionesData) > 0) {
        http_response_code(200);
        echo json_encode(['success' => true, 'data' => $asignacionesData]);
    } else {
        http_response_code(204);
        echo json_encode(['success' => true, 'data' => []]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Error al obtener Asignaciones',
        'details' => $e->getMessage()
    ]);
}
?>