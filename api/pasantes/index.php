<?php

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../config/database.php';

$method = $_SERVER['REQUEST_METHOD'];

try {

    // =========================================
    // GET
    // =========================================

    if ($method === 'GET') {

        $id = $_GET['id'] ?? null;

        // Obtener pasante por ID
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
                    dni,
                    telefono,
                    email,
                    institucion,
                    fecha_inicio,
                    fecha_fin,
                    activo,
                    fecha_registro
                FROM pasantes
                WHERE id = :id
            ");

            $stmt->execute([
                ':id' => (int) $id
            ]);

            $pasante = $stmt->fetch();

            if (!$pasante) {

                http_response_code(404);

                echo json_encode([
                    'success' => false,
                    'error' => 'Pasante no encontrado'
                ], JSON_UNESCAPED_UNICODE);

                exit;
            }

            echo json_encode([
                'success' => true,
                'data' => $pasante
            ], JSON_UNESCAPED_UNICODE);

            exit;
        }

        // Obtener todos
        $stmt = $pdo->query("
            SELECT
                id,
                nombre,
                apellido,
                dni,
                telefono,
                email,
                institucion,
                fecha_inicio,
                fecha_fin,
                activo,
                fecha_registro
            FROM pasantes
            ORDER BY apellido, nombre
        ");

        $pasantes = $stmt->fetchAll();

        echo json_encode([
            'success' => true,
            'data' => $pasantes
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }


    // =========================================
    // POST
    // =========================================

    if ($method === 'POST') {

        $input = json_decode(file_get_contents('php://input'), true);

        if (!is_array($input)) {

            http_response_code(400);

            echo json_encode([
                'success' => false,
                'error' => 'JSON inválido'
            ], JSON_UNESCAPED_UNICODE);

            exit;
        }

        $nombre = trim($input['nombre'] ?? '');
        $apellido = trim($input['apellido'] ?? '');

        if ($nombre === '' || $apellido === '') {

            http_response_code(400);

            echo json_encode([
                'success' => false,
                'error' => 'Nombre y apellido son obligatorios'
            ], JSON_UNESCAPED_UNICODE);

            exit;
        }

        $dni = trim($input['dni'] ?? '') ?: null;
        $telefono = trim($input['telefono'] ?? '') ?: null;
        $email = trim($input['email'] ?? '') ?: null;
        $institucion = trim($input['institucion'] ?? '') ?: null;
        $fecha_inicio = $input['fecha_inicio'] ?? null;
        $fecha_fin = $input['fecha_fin'] ?? null;

        $stmt = $pdo->prepare("
            INSERT INTO pasantes (
                nombre,
                apellido,
                dni,
                telefono,
                email,
                institucion,
                fecha_inicio,
                fecha_fin
            )
            VALUES (
                :nombre,
                :apellido,
                :dni,
                :telefono,
                :email,
                :institucion,
                :fecha_inicio,
                :fecha_fin
            )
        ");

        try {

            $stmt->execute([
                ':nombre' => $nombre,
                ':apellido' => $apellido,
                ':dni' => $dni,
                ':telefono' => $telefono,
                ':email' => $email,
                ':institucion' => $institucion,
                ':fecha_inicio' => $fecha_inicio,
                ':fecha_fin' => $fecha_fin
            ]);

        } catch (PDOException $e) {

            if (($e->errorInfo[1] ?? null) === 1062) {

                http_response_code(409);

                echo json_encode([
                    'success' => false,
                    'error' => 'El DNI ya está registrado'
                ], JSON_UNESCAPED_UNICODE);

                exit;
            }

            throw $e;
        }

        $id = $pdo->lastInsertId();

        http_response_code(201);

        echo json_encode([
            'success' => true,
            'message' => 'Pasante creado correctamente',
            'id' => (int) $id
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }


    // =========================================
    // PUT
    // =========================================

    if ($method === 'PUT') {

        $id = $_GET['id'] ?? null;

        if ($id === null || !ctype_digit($id) || (int) $id <= 0) {

            http_response_code(400);

            echo json_encode([
                'success' => false,
                'error' => 'El ID debe ser un número entero positivo'
            ], JSON_UNESCAPED_UNICODE);

            exit;
        }

        $input = json_decode(file_get_contents('php://input'), true);

        if (!is_array($input)) {

            http_response_code(400);

            echo json_encode([
                'success' => false,
                'error' => 'JSON inválido'
            ], JSON_UNESCAPED_UNICODE);

            exit;
        }

        $stmt = $pdo->prepare("
            SELECT id
            FROM pasantes
            WHERE id = :id
        ");

        $stmt->execute([
            ':id' => (int) $id
        ]);

        if (!$stmt->fetch()) {

            http_response_code(404);

            echo json_encode([
                'success' => false,
                'error' => 'Pasante no encontrado'
            ], JSON_UNESCAPED_UNICODE);

            exit;
        }

        $nombre = trim($input['nombre'] ?? '');
        $apellido = trim($input['apellido'] ?? '');

        if ($nombre === '' || $apellido === '') {

            http_response_code(400);

            echo json_encode([
                'success' => false,
                'error' => 'Nombre y apellido son obligatorios'
            ], JSON_UNESCAPED_UNICODE);

            exit;
        }

        $dni = trim($input['dni'] ?? '') ?: null;
        $telefono = trim($input['telefono'] ?? '') ?: null;
        $email = trim($input['email'] ?? '') ?: null;
        $institucion = trim($input['institucion'] ?? '') ?: null;
        $fecha_inicio = $input['fecha_inicio'] ?? null;
        $fecha_fin = $input['fecha_fin'] ?? null;

        $stmt = $pdo->prepare("
            UPDATE pasantes
            SET
                nombre = :nombre,
                apellido = :apellido,
                dni = :dni,
                telefono = :telefono,
                email = :email,
                institucion = :institucion,
                fecha_inicio = :fecha_inicio,
                fecha_fin = :fecha_fin
            WHERE id = :id
        ");

        try {

            $stmt->execute([
                ':nombre' => $nombre,
                ':apellido' => $apellido,
                ':dni' => $dni,
                ':telefono' => $telefono,
                ':email' => $email,
                ':institucion' => $institucion,
                ':fecha_inicio' => $fecha_inicio,
                ':fecha_fin' => $fecha_fin,
                ':id' => (int) $id
            ]);

        } catch (PDOException $e) {

            if (($e->errorInfo[1] ?? null) === 1062) {

                http_response_code(409);

                echo json_encode([
                    'success' => false,
                    'error' => 'El DNI ya está registrado'
                ], JSON_UNESCAPED_UNICODE);

                exit;
            }

            throw $e;
        }

        echo json_encode([
            'success' => true,
            'message' => 'Pasante actualizado correctamente'
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }
    // =========================================
    // DELETE
    // =========================================

    if ($method === 'DELETE') {

        $id = $_GET['id'] ?? null;

        if ($id === null || !ctype_digit($id) || (int) $id <= 0) {

            http_response_code(400);

            echo json_encode([
                'success' => false,
                'error' => 'El ID debe ser un número entero positivo'
            ], JSON_UNESCAPED_UNICODE);

            exit;
        }

        $stmt = $pdo->prepare("
            SELECT id
            FROM pasantes
            WHERE id = :id
        ");

        $stmt->execute([
            ':id' => (int) $id
        ]);

        if (!$stmt->fetch()) {

            http_response_code(404);

            echo json_encode([
                'success' => false,
                'error' => 'Pasante no encontrado'
            ], JSON_UNESCAPED_UNICODE);

            exit;
        }

        $stmt = $pdo->prepare("
            DELETE FROM pasantes
            WHERE id = :id
        ");

        $stmt->execute([
            ':id' => (int) $id
        ]);

        echo json_encode([
            'success' => true,
            'message' => 'Pasante eliminado correctamente'
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
