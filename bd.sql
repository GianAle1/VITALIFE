CREATE DATABASE IF NOT EXISTS vitalife;
USE vitalife;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    fecha_nacimiento DATE,
    peso DECIMAL(5,2),
    talla DECIMAL(5,2),
    genero VARCHAR(10) CHECK (genero IN ('Male', 'Female', 'Other')),
    nivel_actividad VARCHAR(20) CHECK (nivel_actividad IN ('Sedentario', 'Moderado', 'Activo', 'Muy Activo')),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table to store nutrition guides
CREATE TABLE guias_nutricion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    descripcion TEXT,
    url_archivo VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table to store exercise guides
CREATE TABLE guias_ejercicio (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    descripcion TEXT,
    url_video VARCHAR(255),
    duracion_minutos INT,
    nivel_dificultad VARCHAR(20) CHECK (nivel_dificultad IN ('Principiante', 'Intermedio', 'Avanzado')),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table to track user progress
CREATE TABLE progreso_usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    fecha_registro DATE NOT NULL,
    peso DECIMAL(5,2),
    calorias_consumidas INT,
    calorias_quemadas INT,
    pasos INT,
    UNIQUE(usuario_id, fecha_registro),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Table to log chatbot interactions
CREATE TABLE registros_chatbot (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    fecha_interaccion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    entrada_usuario TEXT,
    respuesta_bot TEXT,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Table for user preferences
CREATE TABLE preferencias_usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    preferencia_dietetica VARCHAR(50),
    objetivo_fitness VARCHAR(50) CHECK (objetivo_fitness IN ('Pérdida de Peso', 'Ganancia de Músculo', 'Resistencia', 'Fitness General')),
    frecuencia_notificaciones VARCHAR(20) CHECK (frecuencia_notificaciones IN ('Diario', 'Semanal', 'Nunca')),
    UNIQUE(usuario_id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Table to store user favorites (bookmarks)
CREATE TABLE favoritos_usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    tipo_guia VARCHAR(20) CHECK (tipo_guia IN ('Nutricion', 'Ejercicio')),
    guia_id INT,
    fecha_favorito TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (guia_id) REFERENCES guias_nutricion(id) ON DELETE CASCADE,
    FOREIGN KEY (guia_id) REFERENCES guias_ejercicio(id) ON DELETE CASCADE
);

-- Table for admin users
CREATE TABLE administradores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    rol VARCHAR(50) CHECK (rol IN ('SuperAdmin', 'GestorContenido', 'Soporte')),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table for notifications
CREATE TABLE notificaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    tipo_notificacion VARCHAR(50) CHECK (tipo_notificacion IN ('Recordatorio', 'Actualización del Sistema')),
    mensaje TEXT NOT NULL,
    enviado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    leido BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Table for user activity logs
CREATE TABLE registros_actividad_usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    accion TEXT NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

SELECT * FROM usuarios;

SELECT nombres, apellidos, email, fecha_nacimiento, peso, talla, genero, nivel_actividad, 
        TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) AS edad 
        FROM usuarios