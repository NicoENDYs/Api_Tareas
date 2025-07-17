# API de Tareas - Documentación

## Información General

Esta API permite gestionar un sistema de tareas con áreas, empleados, estados y asignaciones. La API está desarrollada en PHP y utiliza una base de datos para almacenar la información.

**URL Base:** `http://localhost:8000/api/`

## Endpoints Disponibles

### 1. Áreas

Las áreas representan las categorías o departamentos donde se pueden clasificar las tareas.

#### Obtener todas las áreas
- **URL:** `/areas/get_areas.php`
- **Método:** `GET`
- **Descripción:** Retorna todas las áreas disponibles

#### Obtener área por ID
- **URL:** `/areas/get_id.php`
- **Método:** `GET`
- **Parámetros:** 
  - `id` (query string): ID del área a consultar
- **Ejemplo:** `/areas/get_id.php?id=1`

#### Crear nueva área
- **URL:** `/areas/create.php`
- **Método:** `POST`
- **Content-Type:** `application/json`
- **Body:**
```json
{
  "nombre": "Matematicas",
  "descripcion": "Sumar"
}
```

#### Actualizar área
- **URL:** `/areas/create.php`
- **Método:** `PUT`
- **Parámetros:**
  - `id` (query string): ID del área a actualizar
- **Content-Type:** `application/json`
- **Body:**
```json
{
  "nombre": "Matematicas algebra",
  "descripcion": "Sumar y sumar"
}
```

#### Eliminar área
- **URL:** `/areas/delete.php`
- **Método:** `DELETE`
- **Parámetros:**
  - `id` (query string): ID del área a eliminar
- **Ejemplo:** `/areas/delete.php?id=9`

### 2. Tareas

Las tareas son las actividades que se pueden asignar a los empleados.

#### Obtener todas las tareas
- **URL:** `/tareas/get_all.php`
- **Método:** `GET`
- **Descripción:** Retorna todas las tareas disponibles

#### Obtener tarea por ID
- **URL:** `/tareas/get_id.php`
- **Método:** `GET`
- **Parámetros:**
  - `id` (query string): ID de la tarea a consultar
- **Ejemplo:** `/tareas/get_id.php?id=8`

#### Crear nueva tarea
- **URL:** `/tareas/create.php`
- **Método:** `POST`
- **Content-Type:** `application/json`
- **Body:**
```json
{
  "descripcion": "Crear Tres API",
  "prioridad": "Importante",
  "area_id": "1"
}
```

#### Actualizar tarea
- **URL:** `/tareas/update.php`
- **Método:** `PUT`
- **Parámetros:**
  - `id` (query string): ID de la tarea a actualizar
- **Content-Type:** `application/json`
- **Body:**
```json
{
  "descripcion": "monte",
  "prioridad": "ESPAÑA",
  "area_id": "2"
}
```

#### Eliminar tarea
- **URL:** `/tareas/delete.php`
- **Método:** `DELETE`
- **Parámetros:**
  - `id` (query string): ID de la tarea a eliminar
- **Ejemplo:** `/tareas/delete.php?id=6`

### 3. Empleados

Los empleados son las personas que pueden tener tareas asignadas.

#### Obtener todos los empleados
- **URL:** `/empleado/get_all.php`
- **Método:** `GET`
- **Descripción:** Retorna todos los empleados registrados

#### Obtener empleado por ID
- **URL:** `/empleado/get_id.php`
- **Método:** `GET`
- **Parámetros:**
  - `id` (query string): Identificación del empleado a consultar
- **Ejemplo:** `/empleado/get_id.php?id=17899952`

#### Crear nuevo empleado
- **URL:** `/empleado/create.php`
- **Método:** `POST`
- **Content-Type:** `application/json`
- **Body:**
```json
{
  "identificacion": "1321132",
  "nombres": "nico",
  "apellidos": "guari",
  "telefono": "310"
}
```

#### Actualizar empleado
- **URL:** `/empleado/update.php`
- **Método:** `POST`
- **Parámetros:**
  - `id` (query string): Identificación del empleado a actualizar
- **Content-Type:** `application/json`
- **Body:**
```json
{
  "nombres": "Nico",
  "apellidos": "Molina",
  "telefono": "310"
}
```

#### Eliminar empleado
- **URL:** `/empleado/delete.php`
- **Método:** `DELETE`
- **Parámetros:**
  - `id` (query string): Identificación del empleado a eliminar
- **Ejemplo:** `/empleado/delete.php?id=789`

### 4. Estados

Los estados representan las diferentes fases en las que puede estar una tarea.

#### Obtener todos los estados
- **URL:** `/estado/get_all.php`
- **Método:** `GET`
- **Descripción:** Retorna todos los estados disponibles

#### Obtener estado por ID
- **URL:** `/estado/get_id.php`
- **Método:** `GET`
- **Parámetros:**
  - `id` (query string): ID del estado a consultar
- **Ejemplo:** `/estado/get_id.php?id=1`

#### Crear nuevo estado
- **URL:** `/estado/create.php`
- **Método:** `POST`
- **Content-Type:** `application/json`
- **Body:**
```json
{
  "nombre": "Espera"
}
```

#### Actualizar estado
- **URL:** `/estado/update.php`
- **Método:** `PUT`
- **Parámetros:**
  - `id` (query string): ID del estado a actualizar
- **Content-Type:** `application/json`
- **Body:**
```json
{
  "nombre": "eeee"
}
```

#### Eliminar estado
- **URL:** `/estado/delete.php`
- **Método:** `DELETE`
- **Parámetros:**
  - `id` (query string): ID del estado a eliminar
- **Ejemplo:** `/estado/delete.php?id=5`

### 5. Asignaciones

Las asignaciones vinculan empleados con tareas específicas y su estado actual.

#### Obtener todas las asignaciones
- **URL:** `/asignaciones/get_all.php`
- **Método:** `GET`
- **Descripción:** Retorna todas las asignaciones registradas

#### Obtener asignación por ID
- **URL:** `/asignaciones/get_id.php`
- **Método:** `GET`
- **Parámetros:**
  - `id` (query string): ID de la asignación a consultar
- **Ejemplo:** `/asignaciones/get_id.php?id=2`

#### Crear nueva asignación
- **URL:** `/asignaciones/create.php`
- **Método:** `POST`
- **Content-Type:** `application/json`
- **Body:**
```json
{
  "empleado_identificacion": "17899952",
  "tarea_id": 9,
  "estado_id": 3,
  "fecha_asignacion": "2025-07-17 04:47:46",
  "fecha_entrega": "2025-07-24 23:47:13"
}
```

#### Actualizar asignación
- **URL:** `/asignaciones/update.php`
- **Método:** `PUT`
- **Parámetros:**
  - `id` (query string): ID de la asignación a actualizar
- **Content-Type:** `application/json`
- **Body:**
```json
{
  "empleado_identificacion": "17899952",
  "tarea_id": 9,
  "estado_id": 2,
  "fecha_asignacion": "2025-07-17 04:47:46",
  "fecha_entrega": "2025-07-24 23:47:13"
}
```

#### Eliminar asignación
- **URL:** `/asignaciones/delete.php`
- **Método:** `DELETE`
- **Parámetros:**
  - `id` (query string): ID de la asignación a eliminar
- **Ejemplo:** `/asignaciones/delete.php?id=3`

## Estructura de Datos

### Área
```json
{
  "id": "number",
  "nombre": "string",
  "descripcion": "string"
}
```

### Tarea
```json
{
  "id": "number",
  "descripcion": "string",
  "prioridad": "string",
  "area_id": "number"
}
```

### Empleado
```json
{
  "identificacion": "string",
  "nombres": "string",
  "apellidos": "string",
  "telefono": "string"
}
```

### Estado
```json
{
  "id": "number",
  "nombre": "string"
}
```

### Asignación
```json
{
  "id": "number",
  "empleado_identificacion": "string",
  "tarea_id": "number",
  "estado_id": "number",
  "fecha_asignacion": "datetime",
  "fecha_entrega": "datetime"
}
```

## Códigos de Estado HTTP

- `200 OK`: Operación exitosa
- `201 Created`: Recurso creado exitosamente
- `400 Bad Request`: Error en los parámetros enviados
- `404 Not Found`: Recurso no encontrado
- `500 Internal Server Error`: Error interno del servidor

## Notas Importantes

1. Todas las fechas deben enviarse en formato: `YYYY-MM-DD HH:MM:SS`
2. Los parámetros `id` se envían como query strings en la URL
3. Los datos JSON se envían en el body de la petición
4. Asegúrate de incluir el header `Content-Type: application/json` en las peticiones POST y PUT
5. La identificación del empleado se usa como clave primaria en lugar de un ID numérico

## Ejemplos de Uso

### Crear una nueva tarea
```bash
curl -X POST http://localhost:8000/api/tareas/create.php \
  -H "Content-Type: application/json" \
  -d '{
    "descripcion": "Desarrollar módulo de reportes",
    "prioridad": "Alta",
    "area_id": "1"
  }'
```

### Asignar tarea a empleado
```bash
curl -X POST http://localhost:8000/api/asignaciones/create.php \
  -H "Content-Type: application/json" \
  -d '{
    "empleado_identificacion": "17899952",
    "tarea_id": 9,
    "estado_id": 1,
    "fecha_asignacion": "2025-07-17 08:00:00",
    "fecha_entrega": "2025-07-24 18:00:00"
  }'
```