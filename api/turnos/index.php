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

        // -----------------------------------------
        // Obtener turno por ID
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
                    t.id,
                    t.paciente_id,
                    p.nombre AS paciente_nombre,
                    p.apellido AS paciente_apellido,
                    t.profesional_id,
                    pr.nombre AS profesional_nombre,
                    pr.apellido AS profesional_apellido,
                    pr.especialidad,
                    t.fecha,
                    t.hora,
                    t.motivo,
                    t.estado,
                    t.observaciones,
                    t.fecha_creacion
                FROM turnos t
                INNER JOIN pacientes p
                    ON t.paciente_id = p.id
                INNER JOIN profesionales pr
                    ON t.profesional_id = pr.id
                WHERE t.id = :id
            ");

            $stmt->execute([
                ':id' => (int) $id
            ]);

            $turno = $stmt->fetch();

            if (!$turno) {

                http_response_code(404);

                echo json_encode([
                    'success' => false,
                    'error' => 'Turno no encontrado'
                ], JSON_UNESCAPED_UNICODE);

                exit;
            }

            echo json_encode([
                'success' => true,
                'data' => $turno
            ], JSON_UNESCAPED_UNICODE);

            exit;
        }

        // -----------------------------------------
        // Obtener todos los turnos
        // -----------------------------------------

        $stmt = $pdo->query("
            SELECT
                t.id,
                t.paciente_id,
                p.nombre AS paciente_nombre,
                p.apellido AS paciente_apellido,
                t.profesional_id,
                pr.nombre AS profesional_nombre,
                pr.apellido AS profesional_apellido,
                pr.especialidad,
                t.fecha,
                t.hora,
                t.motivo,
                t.estado,
                t.observaciones,
                t.fecha_creacion
            FROM turnos t
            INNER JOIN pacientes p
                ON t.paciente_id = p.id
            INNER JOIN profesionales pr
                ON t.profesional_id = pr.id
            ORDER BY t.fecha, t.hora
        ");

        $turnos = $stmt->fetchAll();

        echo json_encode([
            'success' => true,
            'data' => $turnos
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

        $paciente_id = $input['paciente_id'] ?? null;
        $profesional_id = $input['profesional_id'] ?? null;
        $fecha = $input['fecha'] ?? null;
        $hora = $input['hora'] ?? null;
        $motivo = trim($input['motivo'] ?? '') ?: null;
        $estado = $input['estado'] ?? 'pendiente';
        $observaciones = trim($input['observaciones'] ?? '') ?: null;

        // -----------------------------------------
        // Validar paciente_id
        // -----------------------------------------

        if (
            $paciente_id === null ||
            !is_numeric($paciente_id) ||
            (int) $paciente_id <= 0
        ) {

            http_response_code(400);

            echo json_encode([
                'success' => false,
                'error' => 'paciente_id debe ser un número entero positivo'
            ], JSON_UNESCAPED_UNICODE);

            exit;
        }

        // -----------------------------------------
        // Validar profesional_id
        // -----------------------------------------

        if (
            $profesional_id === null ||
            !is_numeric($profesional_id) ||
            (int) $profesional_id <= 0
        ) {

            http_response_code(400);

            echo json_encode([
                'success' => false,
                'error' => 'profesional_id debe ser un número entero positivo'
            ], JSON_UNESCAPED_UNICODE);

            exit;
        }

        // -----------------------------------------
        // Validar fecha y hora
        // -----------------------------------------

        if (!$fecha || !$hora) {

            http_response_code(400);

            echo json_encode([
                'success' => false,
                'error' => 'Fecha y hora son obligatorias'
            ], JSON_UNESCAPED_UNICODE);

            exit;
        }

        // -----------------------------------------
        // Validar estado
        // -----------------------------------------

        $estados_validos = [
            'pendiente',
            'confirmado',
            'atendido',
            'cancelado'
        ];

        if (!in_array($estado, $estados_validos, true)) {

            http_response_code(400);

            echo json_encode([
                'success' => false,
                'error' => 'Estado inválido. Valores permitidos: pendiente, confirmado, atendido, cancelado'
            ], JSON_UNESCAPED_UNICODE);

            exit;
        }

        // -----------------------------------------
        // Comprobar paciente
        // -----------------------------------------

        $stmt = $pdo->prepare("
            SELECT id
            FROM pacientes
            WHERE id = :id
        ");

        $stmt->execute([
            ':id' => (int) $paciente_id
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
        // Comprobar profesional
        // -----------------------------------------

        $stmt = $pdo->prepare("
            SELECT id
            FROM profesionales
            WHERE id = :id
        ");

        $stmt->execute([
            ':id' => (int) $profesional_id
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
        // Crear turno
        // -----------------------------------------

        $stmt = $pdo->prepare("
            INSERT INTO turnos (
                paciente_id,
                profesional_id,
                fecha,
                hora,
                motivo,
                estado,
                observaciones
            )
            VALUES (
                :paciente_id,
                :profesional_id,
                :fecha,
                :hora,
                :motivo,
                :estado,
                :observaciones
            )
        ");

        $stmt->execute([
            ':paciente_id' => (int) $paciente_id,
            ':profesional_id' => (int) $profesional_id,
            ':fecha' => $fecha,
            ':hora' => $hora,
            ':motivo' => $motivo,
            ':estado' => $estado,
            ':observaciones' => $observaciones
        ]);

        $id = $pdo->lastInsertId();

        http_response_code(201);

        echo json_encode([
            'success' => true,
            'message' => 'Turno creado correctamente',
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

        // -----------------------------------------
        // Comprobar que el turno existe
        // -----------------------------------------

        $stmt = $pdo->prepare("
            SELECT id
            FROM turnos
            WHERE id = :id
        ");

        $stmt->execute([
            ':id' => (int) $id
        ]);

        if (!$stmt->fetch()) {

            http_response_code(404);

            echo json_encode([
                'success' => false,
                'error' => 'Turno no encontrado'
            ], JSON_UNESCAPED_UNICODE);

            exit;
        }

        // -----------------------------------------
        // Obtener datos
        // -----------------------------------------

        $paciente_id = $input['paciente_id'] ?? null;
        $profesional_id = $input['profesional_id'] ?? null;
        $fecha = $input['fecha'] ?? null;
        $hora = $input['hora'] ?? null;
        $motivo = trim($input['motivo'] ?? '') ?: null;
        $estado = $input['estado'] ?? 'pendiente';
        $observaciones = trim($input['observaciones'] ?? '') ?: null;

        // -----------------------------------------
        // Validar paciente
        // -----------------------------------------

        if (
            $paciente_id === null ||
            !is_numeric($paciente_id) ||
            (int) $paciente_id <= 0
        ) {

            http_response_code(400);

            echo json_encode([
                'success' => false,
                'error' => 'paciente_id debe ser un número entero positivo'
            ], JSON_UNESCAPED_UNICODE);

            exit;
        }

        // -----------------------------------------
        // Validar profesional
        // -----------------------------------------

        if (
            $profesional_id === null ||
            !is_numeric($profesional_id) ||
            (int) $profesional_id <= 0
        ) {

            http_response_code(400);

            echo json_encode([
                'success' => false,
                'error' => 'profesional_id debe ser un número entero positivo'
            ], JSON_UNESCAPED_UNICODE);

            exit;
        }

        // -----------------------------------------
        // Validar fecha y hora
        // -----------------------------------------

        if (!$fecha || !$hora) {

            http_response_code(400);

            echo json_encode([
                'success' => false,
                'error' => 'Fecha y hora son obligatorias'
            ], JSON_UNESCAPED_UNICODE);

            exit;
        }

        // -----------------------------------------
        // Validar estado
        // -----------------------------------------

        $estados_validos = [
            'pendiente',
            'confirmado',
            'atendido',
            'cancelado'
        ];

        if (!in_array($estado, $estados_validos, true)) {

            http_response_code(400);

            echo json_encode([
                'success' => false,
                'error' => 'Estado inválido. Valores permitidos: pendiente, confirmado, atendido, cancelado'
            ], JSON_UNESCAPED_UNICODE);

            exit;
        }

        // -----------------------------------------
        // Comprobar paciente
        // -----------------------------------------

        $stmt = $pdo->prepare("
            SELECT id
            FROM pacientes
            WHERE id = :id
        ");

        $stmt->execute([
            ':id' => (int) $paciente_id
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
        // Comprobar profesional
        // -----------------------------------------

        $stmt = $pdo->prepare("
            SELECT id
            FROM profesionales
            WHERE id = :id
        ");

        $stmt->execute([
            ':id' => (int) $profesional_id
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
        // Actualizar turno
        // -----------------------------------------

        $stmt = $pdo->prepare("
            UPDATE turnos
            SET
                paciente_id = :paciente_id,
                profesional_id = :profesional_id,
                fecha = :fecha,
                hora = :hora,
                motivo = :motivo,
                estado = :estado,
                observaciones = :observaciones
            WHERE id = :id
        ");

        $stmt->execute([
            ':paciente_id' => (int) $paciente_id,
            ':profesional_id' => (int) $profesional_id,
            ':fecha' => $fecha,
            ':hora' => $hora,
            ':motivo' => $motivo,
            ':estado' => $estado,
            ':observaciones' => $observaciones,
            ':id' => (int) $id
        ]);

        echo json_encode([
            'success' => true,
            'message' => 'Turno actualizado correctamente'
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

        // -----------------------------------------
        // Comprobar que el turno existe
        // -----------------------------------------

        $stmt = $pdo->prepare("
            SELECT id
            FROM turnos
            WHERE id = :id
        ");

        $stmt->execute([
            ':id' => (int) $id
        ]);

        if (!$stmt->fetch()) {

            http_response_code(404);

            echo json_encode([
                'success' => false,
                'error' => 'Turno no encontrado'
            ], JSON_UNESCAPED_UNICODE);

            exit;
        }

        // -----------------------------------------
        // Eliminar turno
        // -----------------------------------------

        $stmt = $pdo->prepare("
            DELETE FROM turnos
            WHERE id = :id
        ");

        $stmt->execute([
            ':id' => (int) $id
        ]);

        echo json_encode([
            'success' => true,
            'message' => 'Turno eliminado correctamente'
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
