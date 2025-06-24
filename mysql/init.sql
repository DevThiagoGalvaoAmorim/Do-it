DROP DATABASE IF EXISTS doitdb;
CREATE DATABASE IF NOT EXISTS doitdb;
USE doitdb;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    tipo VARCHAR(100),
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Inserindo um usuário administrador padrão
-- A senha deve ser armazenada de forma segura, aqui é apenas um exemplo.   

INSERT INTO usuarios (nome, email, senha, tipo)
VALUES ('Admin', 'admin', 'admin', 'admin');

CREATE TABLE notas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    descricao TEXT,
    data_hora DATETIME DEFAULT CURRENT_TIMESTAMP,
    pasta VARCHAR(100),
    id_usuario INT NOT NULL,
    tipo ENUM('Checklist', 'Anotação') NOT NULL,
    imagem_url VARCHAR(500) NULL,
    video_url VARCHAR(500) NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE
);

CREATE TABLE lixeira (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    descricao TEXT,
    data_hora DATETIME DEFAULT CURRENT_TIMESTAMP,
    pasta VARCHAR(100),
    id_usuario INT NOT NULL,
    tipo ENUM('Checklist', 'Anotação') NOT NULL,
    imagem_url VARCHAR(500) NULL,
    video_url VARCHAR(500) NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS lembrete (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    descricao TEXT,
    data_hora DATETIME DEFAULT CURRENT_TIMESTAMP,
    id_usuario INT NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuarios (id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS password_resets (
    email VARCHAR(255) NOT NULL,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (token)
);

CREATE TABLE IF NOT EXISTS log_atividades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    data_hora TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    acao TEXT NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE
);

DELIMITER //
CREATE TRIGGER after_nota_insert
AFTER INSERT ON notas
FOR EACH ROW
BEGIN
    INSERT INTO log_atividades (id_usuario, acao)
    VALUES (NEW.id_usuario, CONCAT('Criou a nota "', NEW.titulo, '" (ID: ', NEW.id, ')'));
END//
DELIMITER ;

DELIMITER //
CREATE TRIGGER after_nota_update
AFTER UPDATE ON notas
FOR EACH ROW
BEGIN
    INSERT INTO log_atividades (id_usuario, acao)
    VALUES (NEW.id_usuario, CONCAT('Atualizou a nota "', NEW.titulo, '" (ID: ', NEW.id, ')'));
END//
DELIMITER ;

DELIMITER //
CREATE TRIGGER after_nota_delete
AFTER DELETE ON notas
FOR EACH ROW
BEGIN
    INSERT INTO log_atividades (id_usuario, acao)
    VALUES (OLD.id_usuario, CONCAT('Moveu a nota "', OLD.titulo, '" (ID: ', OLD.id, ') para a lixeira'));
END//
DELIMITER ;

DELIMITER //
CREATE TRIGGER after_lembrete_insert
AFTER INSERT ON lembrete
FOR EACH ROW
BEGIN
    INSERT INTO log_atividades (id_usuario, acao)
    VALUES (NEW.id_usuario, CONCAT('Criou o lembrete "', NEW.titulo, '" (ID: ', NEW.id, ')'));
END//
DELIMITER ;

DELIMITER //
CREATE TRIGGER after_lembrete_update
AFTER UPDATE ON lembrete
FOR EACH ROW
BEGIN
    INSERT INTO log_atividades (id_usuario, acao)
    VALUES (NEW.id_usuario, CONCAT('Atualizou o lembrete "', NEW.titulo, '" (ID: ', NEW.id, ')'));
END//
DELIMITER ;

DELIMITER //
CREATE TRIGGER after_lembrete_delete
AFTER DELETE ON lembrete
FOR EACH ROW
BEGIN
    INSERT INTO log_atividades (id_usuario, acao)
    VALUES (OLD.id_usuario, CONCAT('Excluiu o lembrete "', OLD.titulo, '" (ID: ', OLD.id, ')'));
END//
DELIMITER ;

DELIMITER //
CREATE TRIGGER after_lixeira_delete
AFTER DELETE ON lixeira
FOR EACH ROW
BEGIN
    INSERT INTO log_atividades (id_usuario, acao)
    VALUES (OLD.id_usuario, CONCAT('Restaurou o item "', OLD.titulo, '" (ID: ', OLD.id, ') da lixeira'));
END//
DELIMITER ;

DELIMITER //
CREATE PROCEDURE esvaziar_lixeira(IN p_id_usuario INT)
BEGIN
    -- Log the emptying action before deleting
    INSERT INTO log_atividades (id_usuario, acao)
    VALUES (p_id_usuario, 'Esvaziou a lixeira permanentemente');
    
    -- Actually delete the items
    DELETE FROM lixeira WHERE id_usuario = p_id_usuario;
END//
DELIMITER ;