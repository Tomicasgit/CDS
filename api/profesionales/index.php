<?php

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../config/database.php';

$method = $_SERVER['REQUEST_METHOD'];

try {

    // =========================================
    // GET /api/profesionales
    // GET /api/profesionales/?id=1
    // =========================================

    if ($method === 'GET') {

        $id = $_GET['id'] ?? null;

        // -----------------------------------------
        // Obtener profesional por ID
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
                    nombre,
                    apellido,
                    matricula,
                    especialidad,
                    telefono,
                    email,
                    activo,
                    fecha_registro
                FROM profesionales
                WHERE id = :id
            ");

            $stmt->execute([
                ':id' => (int) $id
            ]);

            $profesional = $stmt->fetch();

            if (!$profesional) {

                http_response_code(404);

                echo json_encode([
                    'success' => false,
                    'error' => 'Profesional no encontrado'
                ], JSON_UNESCAPED_UNICODE);

                exit;
            }

            echo json_encode([
                'success' => true,
                'data' => $profesional
            ], JSON_UNESCAPED_UNICODE);

            exit;
        }

        // -----------------------------------------
        // Obtener todos los profesionales
        // -----------------------------------------

        $stmt = $pdo->query("
            SELECT
                id,
                nombre,
                apellido,
                matricula,
                especialidad,
                telefono,
                email,
                activo,
                fecha_registro
            FROM profesionales
            ORDER BY apellido, nombre
        ");

        $profesionales = $stmt->fetchAll();

        echo json_encode([
            'success' => true,
            'data' => $profesionales
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }


// =========================================
// POST /api/profesionales
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

    $nombre = trim($input['nombre'] ?? '');
    $apellido = trim($input['apellido'] ?? '');
    $especialidad = trim($input['especialidad'] ?? '');

    if ($nombre === '' || $apellido === '' || $especialidad === '') {

        http_response_code(400);

        echo json_encode([
            'success' => false,
            'error' => 'Nombre, apellido y especialidad son obligatorios'
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }

    // -----------------------------------------
    // Datos opcionales
    // -----------------------------------------

    $matricula = trim($input['matricula'] ?? '') ?: null;
    $telefono = trim($input['telefono'] ?? '') ?: null;
    $email = trim($input['email'] ?? '') ?: null;

    // -----------------------------------------
    // Insertar profesional
    // -----------------------------------------

    $stmt = $pdo->prepare("
        INSERT INTO profesionales (
            nombre,
            apellido,
            matricula,
            especialidad,
            telefono,
            email
        )
        VALUES (
            :nombre,
            :apellido,
            :matricula,
            :especialidad,
            :telefono,
            :email
        )
    ");

    $stmt->execute([
        ':nombre' => $nombre,
        ':apellido' => $apellido,
        ':matricula' => $matricula,
        ':especialidad' => $especialidad,
        ':telefono' => $telefono,
        ':email' => $email
    ]);

    $id = $pdo->lastInsertId();

    http_response_code(201);

    echo json_encode([
        'success' => true,
        'message' => 'Profesional creado correctamente',
        'data' => [
            'id' => (int) $id
        ]
    ], JSON_UNESCAPED_UNICODE);

    exit;
}
// =========================================
// PUT /api/profesionales/?id=1
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
    // Verificar que exista
    // -----------------------------------------

    $stmt = $pdo->prepare("
        SELECT id
        FROM profesionales
        WHERE id = :id
    ");

    $stmt->execute([
        ':id' => (int) $id
    ]);

    if (!$stmt->fetch()) {

        http_response_code(404);

        echo json_encode([
            'success' => false,
            'error' => 'Profesional no encontrado'
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }

    // -----------------------------------------
    // Datos obligatorios
    // -----------------------------------------

    $nombre = trim($input['nombre'] ?? '');
    $apellido = trim($input['apellido'] ?? '');
    $especialidad = trim($input['especialidad'] ?? '');

    if ($nombre === '' || $apellido === '' || $especialidad === '') {

        http_response_code(400);

        echo json_encode([
            'success' => false,
            'error' => 'Nombre, apellido y especialidad son obligatorios'
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }

    // -----------------------------------------
    // Datos opcionales
    // -----------------------------------------

    $matricula = trim($input['matricula'] ?? '') ?: null;
    $telefono = trim($input['telefono'] ?? '') ?: null;
    $email = trim($input['email'] ?? '') ?: null;

    // -----------------------------------------
    // Actualizar
    // -----------------------------------------

    $stmt = $pdo->prepare("
        UPDATE profesionales
        SET
            nombre = :nombre,
            apellido = :apellido,
            matricula = :matricula,
            especialidad = :especialidad,
            telefono = :telefono,
            email = :email
        WHERE id = :id
    ");

    $stmt->execute([
        ':id' => (int) $id,
        ':nombre' => $nombre,
        ':apellido' => $apellido,
        ':matricula' => $matricula,
        ':especialidad' => $especialidad,
        ':telefono' => $telefono,
        ':email' => $email
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Profesional actualizado correctamente',
        'data' => [
            'id' => (int) $id
        ]
    ], JSON_UNESCAPED_UNICODE);

    exit;
}
// =========================================
// DELETE /api/profesionales/?id=1
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
    // Verificar que exista
    // -----------------------------------------

    $stmt = $pdo->prepare("
        SELECT id
        FROM profesionales
        WHERE id = :id
    ");

    $stmt->execute([
        ':id' => (int) $id
    ]);

    if (!$stmt->fetch()) {

        http_response_code(404);

        echo json_encode([
            'success' => false,
            'error' => 'Profesional no encontrado'
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }

    // -----------------------------------------
    // Eliminar
    // -----------------------------------------

    $stmt = $pdo->prepare("
        DELETE FROM profesionales
        WHERE id = :id
    ");

    $stmt->execute([
        ':id' => (int) $id
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Profesional eliminado correctamente',
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

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'error' => 'Error interno de la base de datos'
    ], JSON_UNESCAPED_UNICODE);
}
