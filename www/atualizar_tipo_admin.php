<?php
require_once 'conexao_db/conexao.php';

try {
    $stmt = $pdo->prepare("UPDATE usuarios SET tipo = 'admin' WHERE email = 'admin@teste.com'");
    $stmt->execute();
    echo "Usuário admin@teste.com atualizado para tipo 'admin' com sucesso!";
} catch (PDOException $e) {
    echo "Erro ao atualizar tipo de usuário: " . $e->getMessage();
}
?>