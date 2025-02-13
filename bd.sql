CREATE DATABASE IF NOT EXISTS vitalife;
USE vitalife;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    fecha_nacimiento DATE NULL,
    peso DECIMAL(5,2) NULL,
    talla DECIMAL(5,2) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
SELECT nombres, apellidos, email, fecha_nacimiento, peso, talla, genero, nivel_actividad, 
        TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) AS edad 
        FROM usuarios WHERE id = 6

SELECT * FROM usuarios