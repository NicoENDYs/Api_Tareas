<?php
require_once '../../tareas.php';

header('Content-Type: application/json');

// Validar que la conexión a la base de datos exista
if (!isset($pdo)) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Error interno: conexión a base de datos no disponible']);
    exit();
}

// Validar que el método sea PUT o PATCH
if (!in_array($_SERVER['REQUEST_METHOD'], ['PUT', 'PATCH'])) {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Método no permitido. Use PUT o PATCH']);
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

// Obtener y validar los datos JSON
$input = file_get_contents("php://input");
if (empty($input)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'No se recibieron datos']);
    exit();
}

$data = json_decode($input, true);

// Validar que el JSON sea válido
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'JSON inválido: ' . json_last_error_msg()]);
    exit();
}

// Validar que al menos un campo esté presente para actualizar
if (empty($data)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'No se proporcionaron datos para actualizar']);
    exit();
}

// Validar campos si están presentes
if (isset($data['titulo']) && strlen($data['titulo']) > 255) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'El título no puede exceder 255 caracteres']);
    exit();
}

if (isset($data['descripcion']) && strlen($data['descripcion']) > 1000) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'La descripción no puede exceder 1000 caracteres']);
    exit();
}

// Validar estado si se proporciona
$valid_states = ['pendiente', 'en_progreso', 'completada'];
if (isset($data['estado']) && !in_array($data['estado'], $valid_states)) {
    http_response_code(400);
    echo json_encode([
        'success' => false, 
        'error' => 'Estado inválido. Estados permitidos: ' . implode(', ', $valid_states)
    ]);
    exit();
}

// Validar campos vacíos si están presentes
$fields_to_check = ['titulo', 'descripcion'];
foreach ($fields_to_check as $field) {
    if (isset($data[$field]) && empty(trim($data[$field]))) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => "El campo '$field' no puede estar vacío"]);
        exit();
    }
}

try {
    $tareas = new Tareas($pdo);
    
    // Verificar que la tarea existe antes de actualizarla
    $existing_task = $tareas->getOne($id);
    if (!$existing_task) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Tarea no encontrada']);
        exit();
    }

    $success = $tareas->update($id, $data);

    if ($success) {
        http_response_code(200);
        echo json_encode(['success' => true, 'message' => 'Tarea actualizada exitosamente']);
    } else {
        http_response_code(409);
        echo json_encode(['success' => false, 'error' => 'No se pudo actualizar la tarea']);
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