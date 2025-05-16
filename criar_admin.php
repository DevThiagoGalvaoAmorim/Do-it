<?php
require_once __DIR__ . '/www/conexao_db/conexao.php';
require_once __DIR__ . '/www/conexao_db/usuarios_crud.php';

$nome = "Administrador";
$email = "admin2@teste.com";
$senha = "senha123"; // senha em texto puro
$tipo = "admin";

// Criar o usuário com a senha em texto puro (será criptografada dentro da função)
$id = criarUsuario($nome, $email, $senha, $tipo);

if ($id) {
    echo "✅ Usuário administrador criado com sucesso! ID: $id\n";
    echo "📧 Email: $email\n";
    echo "🔒 Senha: $senha\n";
} else {
    echo "❌ Falha ao criar o administrador. Verifique se o email já está cadastrado.\n";
}
?>