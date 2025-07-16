<?php
// ✅ Incluir la clase Patient (que internamente necesita PDO)
require_once '../../tareas.php';

// Encabezado para indicar que la respuesta es JSON
header('Content-Type: application/json');

// Validar que la conexión a la base de datos ($pdo) exista
if (!isset($pdo)) {
    //Error 500 si la conexión no está disponible
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Error interno: conexión a base de datos no disponible']);
    exit();
}
    
// Instanciar la clase con la conexión
$clases = new Areas($pdo);

try {
    // Obtener todos los áreas
    $areas = $clases->getAreas();

    // Si hay áreas, devolver con estado 200 OK
    if ($areas && count($areas) > 0) {
        http_response_code(200);
        echo json_encode(['success' => true, 'data' => $areas]);
    } else {
        // Si no hay datos, responder con 204 No Content (opcionalmente se puede usar 200 con lista vacía)
        http_response_code(204);
        echo json_encode(['success' => true, 'data' => []]);
    }
} catch (Exception $e) {
    // Error inesperado al obtener los datos
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Error al obtener Areas',
        'details' => $e->getMessage()
    ]);
}
?>
