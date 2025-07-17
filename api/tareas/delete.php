<?php
require_once '../../tareas.php';

header('Content-Type: application/json');

// Validar que la conexión a la base de datos exista
if (!isset($pdo)) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Error interno: conexión a base de datos no disponible']);
    exit();
}

// Validar que el método sea DELETE
if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Método no permitido. Use DELETE']);
    exit();
}

// Validar que se proporcione el ID
$id = $_GET['id'] ?? null;

if (!$id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'ID es requerido']);
    exit();
}

// Validar que el ID sea un número entero válido
if (!is_numeric($id) || $id <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'ID debe ser un número entero positivo']);
    exit();
}

try {
    $tareas = new Tareas($pdo);
    
    // Verificar que la tarea existe antes de eliminarla
    $existing_task = $tareas->getOne($id);
    if (!$existing_task) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Tarea no encontrada']);
        exit();
    }

    $success = $tareas->delete($id);

    if ($success) {
        http_response_code(200);
        echo json_encode(['success' => true, 'message' => 'Tarea eliminada exitosamente']);
    } else {
        http_response_code(409);
        echo json_encode(['success' => false, 'error' => 'No se pudo eliminar la tarea']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Error interno del servidor',
        'details' => $e->getMessage()
    ]);
}
?>