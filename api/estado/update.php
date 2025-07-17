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
if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Método no permitido. Use PUT']);
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

// Obtener y validar datos JSON
$json_input = file_get_contents("php://input");
if (empty($json_input)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'No se recibieron datos']);
    exit();
}

$data = json_decode($json_input, true);

// Validar que el JSON sea válido
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'JSON inválido: ' . json_last_error_msg()]);
    exit();
}

// Validar que los datos no estén vacíos
if (empty($data)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Los datos no pueden estar vacíos']);
    exit();
}

// Validar campos requeridos (ajustar según tu estructura)
$required_fields = ['nombre']; // Ajusta según los campos requeridos para estados
foreach ($required_fields as $field) {
    if (!isset($data[$field]) || empty(trim($data[$field]))) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => "El campo '$field' es requerido"]);
        exit();
    }
}

try {
    $estado = new Estados($pdo);
    
    // Verificar si el estado existe antes de actualizarlo
    $existingEstado = $estado->getOneEstado($id);
    if (!$existingEstado) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Estado no encontrado']);
        exit();
    }
    
    $success = $estado->updateEstado($id, $data);

    if ($success) {
        http_response_code(200);
        echo json_encode(['success' => true, 'message' => 'Estado actualizado exitosamente']);
    } else {
        http_response_code(409);
        echo json_encode(['success' => false, 'error' => 'No se pudo actualizar el estado']);
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