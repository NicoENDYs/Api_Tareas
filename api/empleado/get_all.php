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
    // Instanciar la clase con la conexión
    $empleadoInstance = new Empleados($pdo);
    
    // Obtener todos los empleados
    $empleados = $empleadoInstance->getEmpleados();
    
    // Verificar si hay empleados
    if ($empleados && is_array($empleados) && count($empleados) > 0) {
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'data' => $empleados,
            'count' => count($empleados),
            'code' => 200
        ]);
    } else {
        // Si no hay empleados, devolver lista vacía con código 200
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'data' => [],
            'count' => 0,
            'message' => 'No hay empleados registrados.',
            'code' => 200
        ]);
    }
} catch (Exception $e) {
    // Error inesperado al obtener los datos
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Error interno del servidor.',
        'code' => 500,
        'details' => $e->getMessage()
    ]);
}
?>