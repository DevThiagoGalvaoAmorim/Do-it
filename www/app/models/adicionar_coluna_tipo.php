<?php
require_once 'conexao_db/conexao.php';

try {
    $pdo->exec("ALTER TABLE usuarios ADD COLUMN tipo VARCHAR(20) DEFAULT 'user'");
    echo "Coluna 'tipo' adicionada com sucesso à tabela 'usuarios'!";
} catch (PDOException $e) {
    echo "Erro ao adicionar coluna: " . $e->getMessage();
}
?>