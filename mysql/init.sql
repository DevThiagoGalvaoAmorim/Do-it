DROP DATABASE IF EXISTS doitdb;
CREATE DATABASE IF NOT EXISTS doitdb;
USE doitdb;

CREATE TABLE notas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    descricao TEXT,
    data_hora DATETIME DEFAULT CURRENT_TIMESTAMP,
    pasta VARCHAR(100),
    id_usuario INT NOT NULL,
    tipo ENUM('Checklist', 'Anotação') NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE
);