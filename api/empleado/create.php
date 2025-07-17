<?php
require_once '../../tareas.php';

// Establecer header para JSON
header('Content-Type: application/json');

// Validar que el método sea POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'error' => 'Método no permitido. Solo se acepta POST.',
        'code' => 405
    ]);
    exit();
}

// Validar que el contenido sea JSON
$input = file_get_contents("php://input");
if (empty($input)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'No se recibieron datos.',
        'code' => 400
    ]);
    exit();
}

$data = json_decode($input, true);

// Validar que el JSON sea válido
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'Formato JSON inválido.',
        'code' => 400
    ]);
    exit();
}

// Validar campos requeridos
$requiredFields = ['identificacion', 'nombres', 'apellidos', 'telefono'];
$missingFields = [];

foreach ($requiredFields as $field) {
    if (!isset($data[$field]) || empty(trim($data[$field]))) {
        $missingFields[] = $field;
    }
}

if (!empty($missingFields)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'Campos requeridos faltantes: ' . implode(', ', $missingFields),
        'code' => 400
    ]);
    exit();
}

// Validar formato de identificación (solo números)
if (!preg_match('/^\d+$/', trim($data['identificacion']))) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'La identificación debe contener solo números.',
        'code' => 400
    ]);
    exit();
}

// Validar longitud de campos
if (strlen(trim($data['identificacion'])) < 6 || strlen(trim($data['identificacion'])) > 15) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'La identificación debe tener entre 6 y 15 dígitos.',
        'code' => 400
    ]);
    exit();
}

if (strlen(trim($data['nombres'])) < 2 || strlen(trim($data['nombres'])) > 50) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'Los nombres deben tener entre 2 y 50 caracteres.',
        'code' => 400
    ]);
    exit();
}

if (strlen(trim($data['apellidos'])) < 2 || strlen(trim($data['apellidos'])) > 50) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'Los apellidos deben tener entre 2 y 50 caracteres.',
        'code' => 400
    ]);
    exit();
}

// Validar formato de teléfono
if (!preg_match('/^\d{5,15}$/', trim($data['telefono']))) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'El teléfono debe contener entre 5 y 15 dígitos.',
        'code' => 400
    ]);
    exit();
}

// Limpiar datos
$data['identificacion'] = trim($data['identificacion']);
$data['nombres'] = trim($data['nombres']);
$data['apellidos'] = trim($data['apellidos']);
$data['telefono'] = trim($data['telefono']);

try {
    $empleado = new Empleados($pdo);
    $success = $empleado->createEmpleado($data);
    
    if ($success) {
        http_response_code(201);
        echo json_encode([
            'success' => true,
            'message' => 'Empleado creado exitosamente.',
            'code' => 201
        ]);
    } else {
        http_response_code(409);
        echo json_encode([
            'success' => false,
            'error' => 'No se pudo crear el empleado. Posible identificación duplicada.',
            'code' => 409
        ]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Error interno del servidor.',
        'code' => 500,
        'details' => $e->getMessage()
    ]);
}
?>