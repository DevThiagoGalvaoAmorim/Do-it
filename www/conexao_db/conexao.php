<?php
$dsn = 'mysql:host=mysql;dbname=doitdb;charset=utf8';
$user = 'user';
$password = 'password';

try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erro na conexão: " . $e->getMessage();
}