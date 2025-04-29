<?php
$host = 'doitdb.c92as0m2y6b4.sa-east-1.rds.amazonaws.com';
$port = 3306;
$db   = 'doit';
$user = 'admin';
$pass = 'bANvaAav76W5pB3cfGtN';

//$host = 'mysql';
//$port = 3306;
//$db   = 'doitdb';
//$user = 'user';
//$pass = 'password';

try {
    //$pdo = new PDO($dsn, $user, $password);
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erro na conexão: " . $e->getMessage();
}