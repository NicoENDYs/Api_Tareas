<?php
require_once '../../tareas.php';

$data = json_decode(file_get_contents("php://input"), true);

$empleado = new Empleados($pdo);
$success = $empleado->createEmpleado($data);

echo json_encode(['success' => $success]);
?>
