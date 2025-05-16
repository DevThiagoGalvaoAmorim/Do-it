<?php
require_once __DIR__ . '/www/conexao_db/conexao.php';
require_once __DIR__ . '/www/conexao_db/usuarios_crud.php';

$nome = "Administrador";
$email = "admin2@teste.com";
$senha = "senha123";
$tipo = "admin";

// Criptografar a senha
$senhaHash = password_hash($senha, PASSWORD_DEFAULT);

// Criar o usuário
$id = criarUsuario($nome, $email, $senhaHash, $tipo);

if ($id) {
    echo "✅ Usuário administrador criado com sucesso! ID: $id\n";
    echo "📧 Email: $email\n";
    echo "🔒 Senha: $senha\n";
} else {
    echo "❌ Falha ao criar o administrador. Verifique se o email já está cadastrado.\n";
}
?>