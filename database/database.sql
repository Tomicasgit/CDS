CREATE DATABASE IF NOT EXISTS clinica_paula_saucedo
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE clinica_paula_saucedo;

-- =========================================
-- PACIENTES
-- =========================================

CREATE TABLE pacientes (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    dni VARCHAR(20) NOT NULL UNIQUE,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    fecha_nacimiento DATE,
    telefono VARCHAR(30),
    email VARCHAR(150),
    direccion VARCHAR(255),
    activo BOOLEAN NOT NULL DEFAULT TRUE,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================================
-- PROFESIONALES
-- =========================================

CREATE TABLE profesionales (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    matricula VARCHAR(50) UNIQUE,
    especialidad VARCHAR(100) NOT NULL,
    telefono VARCHAR(30),
    email VARCHAR(150),
    activo BOOLEAN NOT NULL DEFAULT TRUE,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================================
-- PASANTES
-- =========================================

CREATE TABLE pasantes (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    dni VARCHAR(20) UNIQUE,
    telefono VARCHAR(30),
    email VARCHAR(150),
    institucion VARCHAR(150),
    fecha_inicio DATE,
    fecha_fin DATE,
    activo BOOLEAN NOT NULL DEFAULT TRUE,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================================
-- TURNOS
-- =========================================

CREATE TABLE turnos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    paciente_id INT UNSIGNED NOT NULL,
    profesional_id INT UNSIGNED NOT NULL,

    fecha DATE NOT NULL,
    hora TIME NOT NULL,

    motivo TEXT,

    estado ENUM(
        'pendiente',
        'confirmado',
        'atendido',
        'cancelado'
    ) NOT NULL DEFAULT 'pendiente',

    observaciones TEXT,

    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_turno_paciente
        FOREIGN KEY (paciente_id)
        REFERENCES pacientes(id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,

    CONSTRAINT fk_turno_profesional
        FOREIGN KEY (profesional_id)
        REFERENCES profesionales(id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
);

-- =========================================
-- ÍNDICES
-- =========================================

CREATE INDEX idx_pacientes_apellido
ON pacientes(apellido);

CREATE INDEX idx_profesionales_apellido
ON profesionales(apellido);

CREATE INDEX idx_turnos_fecha
ON turnos(fecha);

CREATE INDEX idx_turnos_paciente
ON turnos(paciente_id);

CREATE INDEX idx_turnos_profesional
ON turnos(profesional_id);
