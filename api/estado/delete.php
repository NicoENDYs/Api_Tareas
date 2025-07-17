<?php
require_once '../../tareas.php';

// Establecer encabezado JSON
header('Content-Type: application/json');

// Validar que la conexión a la base de datos exista
if (!isset($pdo)) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Error interno: conexión a base de datos no disponible']);
    exit();
}

// Validar método HTTP
if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Método no permitido. Use DELETE']);
    exit();
}

// Obtener y validar ID
$id = $_GET['id'] ?? null;

if (!$id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'ID es requerido']);
    exit();
}

// Validar que el ID sea numérico
if (!is_numeric($id) || $id <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'ID debe ser un número válido mayor que 0']);
    exit();
}

try {
    $estado = new Estados($pdo);
    
    // Verificar si el estado existe antes de eliminarlo
    $existingEstado = $estado->getOneEstado($id);
    if (!$existingEstado) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Estado no encontrado']);
        exit();
    }
    
    // Verificar si el estado está siendo usado en tareas (opcional)
    // $isUsed = $estado->isEstadoUsed($id);
    // if ($isUsed) {
    //     http_response_code(409);
    //     echo json_encode(['success' => false, 'error' => 'No se puede eliminar el estado porque está siendo usado']);
    //     exit();
    // }
    
    $success = $estado->deleteEstado($id);

    if ($success) {
        http_response_code(200);
        echo json_encode(['success' => true, 'message' => 'Estado eliminado exitosamente']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Error al eliminar el estado']);
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