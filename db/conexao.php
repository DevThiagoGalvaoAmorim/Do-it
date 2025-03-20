<?php

$servidor = "localhost";
$usuario = "root";
$senha = "";
$banco = "doitdb";

try {
    $conn = new PDO("mysql:host=$servidor;dbname=$banco;charset=utf8", $usuario, $senha);

    // Configura o PDO para lançar exceções em caso de erro
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    
    // Registra erro em um arquivo de log
    error_log("Erro na conexão com o banco de dados: " . $e->getMessage(), 3, "erros.log");

    die("Erro interno. Tente novamente mais tarde.");
}
?>