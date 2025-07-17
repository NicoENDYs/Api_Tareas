<?php
require_once '../../tareas.php';

// Establecer header para JSON
header('Content-Type: application/json');

// Validar que el método sea DELETE
if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'error' => 'Método no permitido. Solo se acepta DELETE.',
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

try {
    $empleado = new Empleados($pdo);
    
    // Verificar si el empleado existe antes de eliminarlo
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
    
    // Verificar si el empleado tiene asignaciones activas
    // (Esta validación requiere que implementes una función en tu clase Empleados)
    // $hasActiveAssignments = $empleado->hasActiveAssignments($id);
    // if ($hasActiveAssignments) {
    //     http_response_code(409);
    //     echo json_encode([
    //         'success' => false,
    //         'error' => 'No se puede eliminar el empleado porque tiene asignaciones activas.',
    //         'code' => 409
    //     ]);
    //     exit();
    // }
    
    $success = $empleado->deleteEmpleado($id);
    
    if ($success) {
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'message' => 'Empleado eliminado exitosamente.',
            'code' => 200
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => 'No se pudo eliminar el empleado.',
            'code' => 500
        ]);
    }
} catch (Exception $e) {
    // Verificar si es un error de restricción de clave foránea
    if (strpos($e->getMessage(), 'foreign key constraint') !== false || 
        strpos($e->getMessage(), 'FOREIGN KEY constraint') !== false) {
        http_response_code(409);
        echo json_encode([
            'success' => false,
            'error' => 'No se puede eliminar el empleado porque tiene asignaciones relacionadas.',
            'code' => 409
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => 'Error interno del servidor.',
            'code' => 500,
            'details' => $e->getMessage()
        ]);
    }
}
?>