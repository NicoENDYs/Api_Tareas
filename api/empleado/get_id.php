<?php
require_once '../../tareas.php';

// Establecer header para JSON
header('Content-Type: application/json');

// Validar que el método sea GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'error' => 'Método no permitido. Solo se acepta GET.',
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

// Validar que la conexión a la base de datos exista
if (!isset($pdo)) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Error interno: conexión a base de datos no disponible.',
        'code' => 500
    ]);
    exit();
}

try {
    $empleado = new Empleados($pdo);
    $data = $empleado->getOneEmpleado($id);
    
    if ($data && !empty($data)) {
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'data' => $data,
            'code' => 200
        ]);
    } else {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'error' => 'Empleado no encontrado.',
            'code' => 404
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