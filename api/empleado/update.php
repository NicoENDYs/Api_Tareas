<?php
require_once '../../tareas.php';

// Establecer header para JSON
header('Content-Type: application/json');

// Validar que el método sea PUT
if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'error' => 'Método no permitido. Solo se acepta PUT.',
        'code' => 405
    ]);
    exit();
}

// Validar que el ID esté presente
$id = $_GET['id'] ?? null;
if (!$id) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'ID es requerido en la URL.',
        'code' => 400
    ]);
    exit();
}

// Validar formato del ID
if (!preg_match('/^\d+$/', $id)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'El ID debe ser un número válido.',
        'code' => 400
    ]);
    exit();
}

// Validar que el contenido sea JSON
$input = file_get_contents("php://input");
if (empty($input)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'No se recibieron datos para actualizar.',
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

// Validar que al menos un campo esté presente para actualizar
$allowedFields = ['nombres', 'apellidos', 'telefono'];
$hasValidField = false;

foreach ($allowedFields as $field) {
    if (isset($data[$field]) && !empty(trim($data[$field]))) {
        $hasValidField = true;
        break;
    }
}

if (!$hasValidField) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'Debe proporcionar al menos un campo válido para actualizar (nombres, apellidos, telefono).',
        'code' => 400
    ]);
    exit();
}

// Validar campos individuales si están presentes
if (isset($data['nombres']) && !empty(trim($data['nombres']))) {
    if (strlen(trim($data['nombres'])) < 2 || strlen(trim($data['nombres'])) > 50) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => 'Los nombres deben tener entre 2 y 50 caracteres.',
            'code' => 400
        ]);
        exit();
    }
    $data['nombres'] = trim($data['nombres']);
}

if (isset($data['apellidos']) && !empty(trim($data['apellidos']))) {
    if (strlen(trim($data['apellidos'])) < 2 || strlen(trim($data['apellidos'])) > 50) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => 'Los apellidos deben tener entre 2 y 50 caracteres.',
            'code' => 400
        ]);
        exit();
    }
    $data['apellidos'] = trim($data['apellidos']);
}

if (isset($data['telefono']) && !empty(trim($data['telefono']))) {
    if (!preg_match('/^\d{7,15}$/', trim($data['telefono']))) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => 'El teléfono debe contener entre 7 y 15 dígitos.',
            'code' => 400
        ]);
        exit();
    }
    $data['telefono'] = trim($data['telefono']);
}

// Eliminar campos no permitidos
$filteredData = array_intersect_key($data, array_flip($allowedFields));

try {
    $empleado = new Empleados($pdo);
    
    // Verificar si el empleado existe
    $existingEmpleado = $empleado->getOneEmpleado($id);
    if (!$existingEmpleado || empty($existingEmpleado)) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'error' => 'Empleado no encontrado.',
            'code' => 404
        ]);
        exit();
    }
    
    $success = $empleado->updateEmpleado($id, $filteredData);
    
    if ($success) {
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'message' => 'Empleado actualizado exitosamente.',
            'code' => 200
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => 'No se pudo actualizar el empleado.',
            'code' => 500
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