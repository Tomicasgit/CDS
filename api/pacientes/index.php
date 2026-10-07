<?php

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../config/database.php';

$method = $_SERVER['REQUEST_METHOD'];

try {

    // =========================================
    // GET /api/pacientes
    // GET /api/pacientes/?id=1
    // =========================================

    if ($method === 'GET') {

        $id = $_GET['id'] ?? null;

        // -----------------------------------------
        // Obtener paciente por ID
        // -----------------------------------------

        if ($id !== null) {

            if (!ctype_digit($id) || (int) $id <= 0) {

                http_response_code(400);

                echo json_encode([
                    'success' => false,
                    'error' => 'El ID debe ser un número entero positivo'
                ], JSON_UNESCAPED_UNICODE);

                exit;
            }

            $stmt = $pdo->prepare("
                SELECT
                    id,
                    dni,
                    nombre,
                    apellido,
                    fecha_nacimiento,
                    telefono,
                    email,
                    direccion,
                    activo,
                    fecha_registro
                FROM pacientes
                WHERE id = :id
            ");

            $stmt->execute([
                ':id' => (int) $id
            ]);

            $paciente = $stmt->fetch();

            if (!$paciente) {

                http_response_code(404);

                echo json_encode([
                    'success' => false,
                    'error' => 'Paciente no encontrado'
                ], JSON_UNESCAPED_UNICODE);

                exit;
            }

            echo json_encode([
                'success' => true,
                'data' => $paciente
            ], JSON_UNESCAPED_UNICODE);

            exit;
        }

        // -----------------------------------------
        // Obtener todos los pacientes
        // -----------------------------------------

        $stmt = $pdo->query("
            SELECT
                id,
                dni,
                nombre,
                apellido,
                fecha_nacimiento,
                telefono,
                email,
                direccion,
                activo,
                fecha_registro
            FROM pacientes
            ORDER BY apellido, nombre
        ");

        $pacientes = $stmt->fetchAll();

        echo json_encode([
            'success' => true,
            'data' => $pacientes
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }


    // =========================================
    // POST /api/pacientes
    // =========================================

    if ($method === 'POST') {

        $input = json_decode(
            file_get_contents('php://input'),
            true
        );

        if (!is_array($input)) {

            http_response_code(400);

            echo json_encode([
                'success' => false,
                'error' => 'El cuerpo de la solicitud debe ser JSON válido'
            ], JSON_UNESCAPED_UNICODE);

            exit;
        }

        // -----------------------------------------
        // Datos obligatorios
        // -----------------------------------------

        $dni = trim($input['dni'] ?? '');
        $nombre = trim($input['nombre'] ?? '');
        $apellido = trim($input['apellido'] ?? '');

        if ($dni === '' || $nombre === '' || $apellido === '') {

            http_response_code(400);

            echo json_encode([
                'success' => false,
                'error' => 'DNI, nombre y apellido son obligatorios'
            ], JSON_UNESCAPED_UNICODE);

            exit;
        }

        // -----------------------------------------
        // Datos opcionales
        // -----------------------------------------

        $fechaNacimiento = $input['fecha_nacimiento'] ?? null;
        $telefono = $input['telefono'] ?? null;
        $email = $input['email'] ?? null;
        $direccion = $input['direccion'] ?? null;

        // -----------------------------------------
        // Insertar paciente
        // -----------------------------------------

        $stmt = $pdo->prepare("
            INSERT INTO pacientes (
                dni,
                nombre,
                apellido,
                fecha_nacimiento,
                telefono,
                email,
                direccion
            )
            VALUES (
                :dni,
                :nombre,
                :apellido,
                :fecha_nacimiento,
                :telefono,
                :email,
                :direccion
            )
        ");

        $stmt->execute([
            ':dni' => $dni,
            ':nombre' => $nombre,
            ':apellido' => $apellido,
            ':fecha_nacimiento' => $fechaNacimiento,
            ':telefono' => $telefono,
            ':email' => $email,
            ':direccion' => $direccion
        ]);

        $id = $pdo->lastInsertId();

        http_response_code(201);

        echo json_encode([
            'success' => true,
            'message' => 'Paciente creado correctamente',
            'data' => [
                'id' => (int) $id
            ]
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }

// =========================================
// PUT /api/pacientes/?id=1
// =========================================

if ($method === 'PUT') {

    $id = $_GET['id'] ?? null;

    if ($id === null || !ctype_digit($id) || (int) $id <= 0) {

        http_response_code(400);

        echo json_encode([
            'success' => false,
            'error' => 'Debe proporcionar un ID válido'
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }

    $input = json_decode(
        file_get_contents('php://input'),
        true
    );

    if (!is_array($input)) {

        http_response_code(400);

        echo json_encode([
            'success' => false,
            'error' => 'El cuerpo de la solicitud debe ser JSON válido'
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }

    // -----------------------------------------
    // Verificar que exista el paciente
    // -----------------------------------------

    $stmt = $pdo->prepare("
        SELECT id
        FROM pacientes
        WHERE id = :id
    ");

    $stmt->execute([
        ':id' => (int) $id
    ]);

    if (!$stmt->fetch()) {

        http_response_code(404);

        echo json_encode([
            'success' => false,
            'error' => 'Paciente no encontrado'
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }

    // -----------------------------------------
    // Datos recibidos
    // -----------------------------------------

    $dni = trim($input['dni'] ?? '');
    $nombre = trim($input['nombre'] ?? '');
    $apellido = trim($input['apellido'] ?? '');

    if ($dni === '' || $nombre === '' || $apellido === '') {

        http_response_code(400);

        echo json_encode([
            'success' => false,
            'error' => 'DNI, nombre y apellido son obligatorios'
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }

    $fechaNacimiento = $input['fecha_nacimiento'] ?? null;
    $telefono = $input['telefono'] ?? null;
    $email = $input['email'] ?? null;
    $direccion = $input['direccion'] ?? null;

    // -----------------------------------------
    // Actualizar paciente
    // -----------------------------------------

    $stmt = $pdo->prepare("
        UPDATE pacientes
        SET
            dni = :dni,
            nombre = :nombre,
            apellido = :apellido,
            fecha_nacimiento = :fecha_nacimiento,
            telefono = :telefono,
            email = :email,
            direccion = :direccion
        WHERE id = :id
    ");

    $stmt->execute([
        ':id' => (int) $id,
        ':dni' => $dni,
        ':nombre' => $nombre,
        ':apellido' => $apellido,
        ':fecha_nacimiento' => $fechaNacimiento,
        ':telefono' => $telefono,
        ':email' => $email,
        ':direccion' => $direccion
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Paciente actualizado correctamente',
        'data' => [
            'id' => (int) $id
        ]
    ], JSON_UNESCAPED_UNICODE);

    exit;
}
// =========================================
// DELETE /api/pacientes/?id=1
// =========================================

if ($method === 'DELETE') {

    $id = $_GET['id'] ?? null;

    if ($id === null || !ctype_digit($id) || (int) $id <= 0) {

        http_response_code(400);

        echo json_encode([
            'success' => false,
            'error' => 'Debe proporcionar un ID válido'
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }

    // -----------------------------------------
    // Verificar que exista el paciente
    // -----------------------------------------

    $stmt = $pdo->prepare("
        SELECT id
        FROM pacientes
        WHERE id = :id
    ");

    $stmt->execute([
        ':id' => (int) $id
    ]);

    if (!$stmt->fetch()) {

        http_response_code(404);

        echo json_encode([
            'success' => false,
            'error' => 'Paciente no encontrado'
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }

    // -----------------------------------------
    // Eliminar paciente
    // -----------------------------------------

    $stmt = $pdo->prepare("
        DELETE FROM pacientes
        WHERE id = :id
    ");

    $stmt->execute([
        ':id' => (int) $id
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Paciente eliminado correctamente',
        'data' => [
            'id' => (int) $id
        ]
    ], JSON_UNESCAPED_UNICODE);

    exit;
}
    // =========================================
    // Método no permitido
    // =========================================

    http_response_code(405);

    echo json_encode([
        'success' => false,
        'error' => 'Método no permitido'
    ], JSON_UNESCAPED_UNICODE);


} catch (PDOException $e) {

    // -----------------------------------------
    // DNI duplicado
    // -----------------------------------------

    if (($e->errorInfo[1] ?? null) === 1062) {

        http_response_code(409);

        echo json_encode([
            'success' => false,
            'error' => 'Ya existe un paciente con ese DNI'
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }

    // -----------------------------------------
    // Error general de base de datos
    // -----------------------------------------

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'error' => 'Error interno de la base de datos'
    ], JSON_UNESCAPED_UNICODE);
}
