<?php
require_once '../../tareas.php';

header('Content-Type: application/json');

// Validar que la conexión a la base de datos exista
if (!isset($pdo)) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Error interno: conexión a base de datos no disponible']);
    exit();
}

// Validar que el método sea POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Método no permitido. Use POST']);
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

// Validar que los datos requeridos estén presentes
$required_fields = [ 'descripcion']; // Ajusta según tus campos requeridos
$missing_fields = [];

foreach ($required_fields as $field) {
    if (!isset($data[$field]) || empty(trim($data[$field]))) {
        $missing_fields[] = $field;
    }
}

if (!empty($missing_fields)) {
    http_response_code(400);
    echo json_encode([
        'success' => false, 
        'error' => 'Campos requeridos faltantes: ' . implode(', ', $missing_fields)
    ]);
    exit();
}

if (strlen($data['descripcion']) > 1000) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'La descripción no puede exceder 1000 caracteres']);
    exit();
}


try {
    $tarea = new Tareas($pdo);
    $success = $tarea->create($data);

    if ($success) {
        http_response_code(201);
        echo json_encode(['success' => true, 'message' => 'Tarea creada exitosamente']);
    } else {
        http_response_code(409);
        echo json_encode(['success' => false, 'error' => 'No se pudo crear la tarea. Posible conflicto']);
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