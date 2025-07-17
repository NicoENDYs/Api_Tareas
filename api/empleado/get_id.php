<?php
require_once '../../tareas.php';

$id = $_GET['id'] ?? null;
$empleado = new Empleados($pdo);

if ($id) {
    $data = $empleado->getOneEmpleado($id);
    echo json_encode($data);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'ID is required']);
}
?>
