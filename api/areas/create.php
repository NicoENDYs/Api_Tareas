<?php
require_once '../../tareas.php';

$data = json_decode(file_get_contents("php://input"), true);

$tarea = new Areas($pdo);
$success = $tarea->createArea($data);

echo json_encode(['success' => $success]);
?>
