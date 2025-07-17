<?php
require_once '../../tareas.php';

header('Content-Type: application/json');

// Validar que la conexión a la base de datos exista
if (!isset($pdo)) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Error interno: conexión a base de datos no disponible']);
    exit();
}

// Validar que el método sea GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Método no permitido. Use GET']);
    exit();
}

// Validar parámetros opcionales de paginación
$page = $_GET['page'] ?? 1;
$limit = $_GET['limit'] ?? 10;
$estado = $_GET['estado'] ?? null;

// Validar que page y limit sean números enteros positivos
if (!is_numeric($page) || $page <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'El parámetro page debe ser un número entero positivo']);
    exit();
}

if (!is_numeric($limit) || $limit <= 0 || $limit > 100) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'El parámetro limit debe ser un número entero entre 1 y 100']);
    exit();
}

// Validar estado si se proporciona
$valid_states = ['pendiente', 'en_progreso', 'completada'];
if ($estado && !in_array($estado, $valid_states)) {
    http_response_code(400);
    echo json_encode([
        'success' => false, 
        'error' => 'Estado inválido. Estados permitidos: ' . implode(', ', $valid_states)
    ]);
    exit();
}

try {
    $tareas = new Tareas($pdo);
    
    // Obtener todas las tareas (podrías modificar el método para incluir filtros)
    $data = $tareas->getAll();

    if ($data && count($data) > 0) {
        http_response_code(200);
        echo json_encode([
            'success' => true, 
            'data' => $data,
            'total' => count($data),
            'page' => (int)$page,
            'limit' => (int)$limit
        ]);
    } else {
        http_response_code(200);
        echo json_encode([
            'success' => true, 
            'data' => [],
            'total' => 0,
            'page' => (int)$page,
            'limit' => (int)$limit,
            'message' => 'No se encontraron tareas'
        ]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Error al obtener las tareas',
        'details' => $e->getMessage()
    ]);
}
?>