<?php
require_once 'conexao_db/conexao.php';

$email = "ruam@mail.com";

try {
    // Verificar se o usuário existe e qual é a senha armazenada
    $stmt = $pdo->prepare("SELECT id, nome, email, senha FROM usuarios WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($usuario) {
        echo "Usuário encontrado:<br>";
        echo "ID: " . $usuario['id'] . "<br>";
        echo "Nome: " . $usuario['nome'] . "<br>";
        echo "Email: " . $usuario['email'] . "<br>";
        echo "Senha armazenada: " . $usuario['senha'] . "<br>";
    } else {
        echo "Usuário não encontrado!";
    }
    
    // Testar login com a senha fornecida
    $senha_teste = "123";
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = :email AND senha = :senha");
    $stmt->execute([
        ':email' => $email,
        ':senha' => $senha_teste
    ]);
    
    if ($stmt->rowCount() > 0) {
        echo "<br>Login com senha '123' funcionaria!";
    } else {
        echo "<br>Login com senha '123' NÃO funcionaria!";
    }
    
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?>