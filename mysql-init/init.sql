CREATE DATABASE IF NOT EXISTS cv_web;
USE cv_web;

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Tabla de notas
CREATE TABLE IF NOT EXISTS notas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    nota DECIMAL(5, 2) NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Insertar un usuario por defecto solo si no existe
INSERT INTO usuarios (usuario, password)
SELECT 'admin', 'admin123'
WHERE NOT EXISTS (
    SELECT 1 FROM usuarios WHERE usuario = 'admin'
);

-- Configurar el usuario root con privilegios
GRANT ALL PRIVILEGES ON cv_web.* TO 'root'@'%' IDENTIFIED BY 'root_password';
FLUSH PRIVILEGES;
