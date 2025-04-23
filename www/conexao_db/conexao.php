<?php
$host = 'doitdb.c92as0m2y6b4.sa-east-1.rds.amazonaws.com';
$port = 3306;
$db   = 'doit';
$user = 'admin';
$pass = 'bANvaAav76W5pB3cfGtN';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Criação do banco de dados e tabela
    $query = "
        DROP DATABASE IF EXISTS doit;
        CREATE DATABASE IF NOT EXISTS doit;
        USE doit;

        CREATE TABLE IF NOT EXISTS usuarios (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL UNIQUE,
            senha VARCHAR(255) NOT NULL,
            criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );

        CREATE TABLE IF NOT EXISTS notas (
            id INT AUTO_INCREMENT PRIMARY KEY,
            titulo VARCHAR(255) NOT NULL,
            descricao TEXT,
            data_hora DATETIME DEFAULT CURRENT_TIMESTAMP,
            pasta VARCHAR(100),
            id_usuario INT NOT NULL,
            tipo ENUM('Checklist', 'Anotação') NOT NULL,
            FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE
        );
    ";

    $pdo->exec($query);
    //require_once 'mostrar_tabelas.php';
} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}
?>