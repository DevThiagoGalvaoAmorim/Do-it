<?php
session_start();
require_once __DIR__ .'/conexao.php';

function criarUsuario($nome, $email, $senha) {
    global $pdo;

    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = :email");
    $stmt->execute([':email' => $email]);
    if ($stmt->rowCount() > 0) {
        throw new Exception("E-mail já cadastrado.");
    }

    $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)");
    $stmt->execute([
        ':nome' => $nome,
        ':email' => $email,
        ':senha' => $senha
    ]);
}

function listarUsuarios() {
    global $pdo;

    $stmt = $pdo->query("SELECT * FROM usuarios");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function atualizarUsuario($id, $nome, $email, $senha) {
    global $pdo;

    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = :email AND id != :id");
    $stmt->execute([':email' => $email, ':id' => $id]);
    if ($stmt->rowCount() > 0) {
        throw new Exception("E-mail já cadastrado.");
    }

    $stmt = $pdo->prepare("UPDATE usuarios SET nome = :nome, email = :email, senha = :senha WHERE id = :id");
    $stmt->execute([
        ':nome' => $nome,
        ':email' => $email,
        ':senha' => $senha,
        ':id' => $id
    ]);
}

function deletarUsuario($id) {
    global $pdo;

    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE id = :id");
    $stmt->execute([':id' => $id]);
    if ($stmt->rowCount() == 0) {
        throw new Exception("Usuário não encontrado.");
    }
    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = :id");
    $stmt->execute([':id' => $id]);
}
?>