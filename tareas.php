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
        if (!is_numeric($id)) {
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

        if (!is_numeric($area_id)) {
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
        if (!is_numeric($id)) {
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
        if (!is_numeric($id)) {
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


//Empleado class

class Empleados
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Obtener todos los empleados
    public function getEmpleados()
    {
        $stmt = $this->pdo->query("SELECT * FROM Empleados");
        return $stmt->fetchAll();
    }

    // Obtener un empleado por ID
    public function getOneEmpleado($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM Empleados WHERE identificacion = ?");
        if (!is_numeric($id)) {
            throw new InvalidArgumentException("Los valores de identificacion deben ser numéricos");
        }
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    // Crear un nuevo empleado
    public function createEmpleado($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO Empleados (identificacion, nombres, apellidos, telefono) VALUES (?, ?, ?, ?)");
        $identificacion = filter_var($data['identificacion'], FILTER_SANITIZE_NUMBER_INT);
        $nombre = filter_var($data['nombres'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $apellido = filter_var($data['apellidos'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $telefono = filter_var($data['telefono'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if (!is_numeric($identificacion)) {
            throw new InvalidArgumentException("Los valores de identificacion deben ser numéricos");
        }

        return $stmt->execute([$identificacion, $nombre, $apellido, $telefono]);
    }

    // Actualizar un empleado existente
    public function updateEmpleado($id, $data)
    {
        $stmt = $this->pdo->prepare("   UPDATE Empleados SET nombres = ?, apellidos = ?, telefono = ? WHERE identificacion = ?");
        // Sanitización de los valores antes de la ejecución
        $nombres = filter_var($data['nombres'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $apellidos = filter_var($data['apellidos'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $telefono = filter_var($data['telefono'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if (!is_numeric($id)) {
            throw new InvalidArgumentException("Los valores de identificacion deben ser numéricos");
        }

        return $stmt->execute([$nombres, $apellidos, $telefono, $id]);
    }
    // Eliminar un empleado
    public function deleteEmpleado($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM Empleados WHERE identificacion = ?");
        if (!is_numeric($id)) {
            throw new InvalidArgumentException("Los valores de identificacion deben ser numéricos");
        }
        return $stmt->execute([$id]);
    }
}

class Estados
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Obtener todos los estados
    public function getEstados()
    {
        $stmt = $this->pdo->query("SELECT * FROM Estados");
        return $stmt->fetchAll();
    }

    // Obtener un estado por ID
    public function getOneEstado($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM Estados WHERE id = ?");
        if (!is_numeric($id)) {
            throw new InvalidArgumentException("Los valores de ID deben ser numéricos");
        }
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    // Crear un nuevo estado
    public function createEstado($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO Estados (nombre) VALUES (?)");
        $nombre = filter_var($data['nombre'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        return $stmt->execute([$nombre]);
    }
    // Actualizar un estado existente
    public function updateEstado($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE Estados SET nombre = ? WHERE id = ?");
        $nombre = filter_var($data['nombre'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (!is_numeric($id)) {
            throw new InvalidArgumentException("Los valores de ID deben ser numéricos");
        }
        return $stmt->execute([$nombre, $id]);
    }
    //Eliminar
    public function deleteEstado($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM Estados WHERE id = ?");
        if (!is_numeric($id)) {
            throw new InvalidArgumentException("Los valores de ID deben ser numéricos");
        }
        return $stmt->execute([$id]);
    }
}

class Asignaciones
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Obtener todas las asignaciones con detalles completos
    public function getAll()
    {
        $sql = "SELECT 
                    a.id,
                    a.fecha_asignacion,
                    a.fecha_entrega,
                    e.nombres AS empleado_nombres,
                    e.apellidos AS empleado_apellidos,
                    t.descripcion AS tarea_descripcion,
                    t.prioridad AS tarea_prioridad,
                    es.nombre AS estado_nombre
                FROM Asignaciones a
                JOIN Empleados e ON a.empleado_identificacion = e.identificacion
                JOIN Tareas t ON a.tarea_id = t.id
                JOIN Estados es ON a.estado_id = es.id";

        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    // Obtener una asignación por ID con detalles completos
    public function getOne($id)
    {
        $sql = "SELECT 
                    a.id,
                    a.fecha_asignacion,
                    a.fecha_entrega,
                    e.identificacion AS empleado_id,
                    e.nombres AS empleado_nombres,
                    e.apellidos AS empleado_apellidos,
                    t.id AS tarea_id,
                    t.descripcion AS tarea_descripcion,
                    t.prioridad AS tarea_prioridad,
                    es.id AS estado_id,
                    es.nombre AS estado_nombre
                FROM Asignaciones a
                JOIN Empleados e ON a.empleado_identificacion = e.identificacion
                JOIN Tareas t ON a.tarea_id = t.id
                JOIN Estados es ON a.estado_id = es.id
                WHERE a.id = ?";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Crear una nueva asignación
    public function create($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO Asignaciones 
                                    (empleado_identificacion, tarea_id, estado_id, fecha_asignacion, fecha_entrega) 
                                    VALUES (?, ?, ?, ?, ?)");

        // Sanitización y validación
        $empleado_identificacion = filter_var($data['empleado_identificacion'], FILTER_SANITIZE_NUMBER_INT);
        $tarea_id = filter_var($data['tarea_id'], FILTER_SANITIZE_NUMBER_INT);
        $estado_id = filter_var($data['estado_id'], FILTER_SANITIZE_NUMBER_INT);
        $fecha_asignacion = filter_var($data['fecha_asignacion'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $fecha_entrega = isset($data['fecha_entrega']) ? filter_var($data['fecha_entrega'], FILTER_SANITIZE_FULL_SPECIAL_CHARS) : null;

        if (!is_numeric($empleado_identificacion) || !is_numeric($tarea_id) || !is_numeric($estado_id)) {
            throw new InvalidArgumentException("Los IDs deben ser valores numéricos");
        }

        return $stmt->execute([
            $empleado_identificacion,
            $tarea_id,
            $estado_id,
            $fecha_asignacion,
            $fecha_entrega
        ]);
    }

    // Actualizar una asignación existente
    public function update($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE Asignaciones 
                                    SET empleado_identificacion = ?, 
                                        tarea_id = ?, 
                                        estado_id = ?, 
                                        fecha_asignacion = ?, 
                                        fecha_entrega = ? 
                                    WHERE id = ?");

        // Sanitización y validación
        $empleado_id = filter_var($data['empleado_identificacion'], FILTER_SANITIZE_NUMBER_INT);
        $tarea_id = filter_var($data['tarea_id'], FILTER_SANITIZE_NUMBER_INT);
        $estado_id = filter_var($data['estado_id'], FILTER_SANITIZE_NUMBER_INT);
        $fecha_asignacion = filter_var($data['fecha_asignacion'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $fecha_entrega = isset($data['fecha_entrega']) ? filter_var($data['fecha_entrega'], FILTER_SANITIZE_FULL_SPECIAL_CHARS) : null;

        if (!is_numeric($id) || !is_numeric($empleado_id) || !is_numeric($tarea_id) || !is_numeric($estado_id)) {
            throw new InvalidArgumentException("Los IDs deben ser valores numéricos");
        }

        return $stmt->execute([
            $empleado_id,
            $tarea_id,
            $estado_id,
            $fecha_asignacion,
            $fecha_entrega,
            $id
        ]);
    }

    // Eliminar una asignación
    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM Asignaciones WHERE id = ?");
        if (!is_numeric($id)) {
            throw new InvalidArgumentException("El ID debe ser un valor numérico");
        }
        return $stmt->execute([$id]);
    }
}
