<?php
require_once 'conexao_db/conexao.php';

try {
    $stmt = $pdo->prepare("SELECT id, nome, email, tipo FROM usuarios WHERE email = 'admin@teste.com'");
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($usuario) {
        echo "Usuário encontrado:<br>";
        echo "ID: " . $usuario['id'] . "<br>";
        echo "Nome: " . $usuario['nome'] . "<br>";
        echo "Email: " . $usuario['email'] . "<br>";
        echo "Tipo: " . ($usuario['tipo'] ?? 'não definido') . "<br>";
    } else {
        echo "Usuário não encontrado!";
    }
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?>