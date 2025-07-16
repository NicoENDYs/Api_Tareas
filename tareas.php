<?php
require_once 'db.php';

// Configuración CORS básica
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

class Tareas
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Obtener todas las tareas
    public function getAll()
    {
        $stmt = $this->pdo->query("SELECT * FROM Tareas");
        return $stmt->fetchAll();
    }

    // Obtener una tarea por ID
    public function getOne($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM Tareas WHERE id = ?");
        if (!is_numeric($id) ) {
            throw new InvalidArgumentException("Los valores de ID deben ser numéricos");
        }
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Crear una nueva tarea
    public function create($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO Tareas (descripcion, prioridad, area_id) VALUES (?, ?, ?)");
        
        $descripcion = filter_var($data['descripcion'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $prioridad = filter_var($data['prioridad'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $area_id = filter_var($data['area_id'], FILTER_SANITIZE_NUMBER_INT);

        if (!is_numeric($area_id) ) {
            throw new InvalidArgumentException("Los valores de área deben ser numéricos");
        }

        return $stmt->execute([$descripcion, $prioridad, $area_id]);
    }

    // Actualizar una tarea existente
    public function update($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE Tareas SET descripcion = ?, prioridad = ?, area_id = ? WHERE id = ?");
        // Sanitización de los valores antes de la ejecución
        $descripcion = filter_var($data['descripcion'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $prioridad = filter_var($data['prioridad'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $area_id = filter_var($data['area_id'], FILTER_SANITIZE_NUMBER_INT);

        // Validación adicional (opcional pero recomendado)
        if (!is_numeric($area_id) || !is_numeric($id)) {
            throw new InvalidArgumentException("Los valores de área e ID deben ser numéricos");
        }

        // Ejecución con valores sanitizados
        return $stmt->execute([$descripcion, $prioridad, $area_id, $id]);
    }

    // Eliminar una tarea
    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM Tareas WHERE id = ?");
        return $stmt->execute([$id]);
    }
}

// clsse area


class Areas
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Obtener todas las áreas
    public function getAreas()
    {
        $stmt = $this->pdo->query("SELECT * FROM Areas");
        return $stmt->fetchAll();
    }

    // Obtener una área por ID
    public function getOneArea($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM Areas WHERE id = ?");
        if (!is_numeric($id) ) {
            throw new InvalidArgumentException("Los valores de ID deben ser numéricos");
        }
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    // Crear una nueva área
    public function createArea($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO Areas (nombre, descripcion) VALUES (?, ?)");
        return $stmt->execute([$data['nombre'], $data['descripcion']]);
    }

    // Actualizar una area existente
    public function updateArea($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE Areas SET nombre = ?, descripcion = ? WHERE id = ?");
        // Sanitización de los valores antes de la ejecución
        $nombre = filter_var($data['nombre'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $descripcion = filter_var($data['descripcion'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        // Validación adicional (opcional pero recomendado)
        if ( !is_numeric($id)) {
            throw new InvalidArgumentException("Los valores de ID deben ser numéricos");
        }

        // Ejecución con valores sanitizados
        return $stmt->execute([$nombre, $descripcion, $id]);
    }

    
    // Eliminar una area
    public function deleteArea($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM Areas WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
