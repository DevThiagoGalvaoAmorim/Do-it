<?php
$host = '127.0.0.1'; // Endereço IP local
$port = 3307; // Porta mapeada no docker-compose
$db   = 'doitdb';
$user = 'user';
$pass = 'password';
global $pdo; // Declaração global explícita

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET NAMES utf8"); // Garante suporte a caracteres UTF-8
} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}