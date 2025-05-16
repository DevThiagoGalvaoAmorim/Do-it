<?php
require_once 'conexao_db/conexao.php';

<<<<<<< HEAD
$email = "admin@teste.com";
=======
$email = "ruam@mail.com";
>>>>>>> ace72197039c8ba0b59a3c63a1ec82d09ba39637

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
<<<<<<< HEAD
    $senha_teste = "senha123";
=======
    $senha_teste = "123";
>>>>>>> ace72197039c8ba0b59a3c63a1ec82d09ba39637
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = :email AND senha = :senha");
    $stmt->execute([
        ':email' => $email,
        ':senha' => $senha_teste
    ]);
    
    if ($stmt->rowCount() > 0) {
<<<<<<< HEAD
        echo "<br>Login com senha 'senha123' funcionaria!";
=======
        echo "<br>Login com senha '123' funcionaria!";
>>>>>>> ace72197039c8ba0b59a3c63a1ec82d09ba39637
    } else {
        echo "<br>Login com senha '123' NÃO funcionaria!";
    }
    
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?>