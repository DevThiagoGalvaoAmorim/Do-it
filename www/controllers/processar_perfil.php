<?php
session_start();
require_once __DIR__ . '/../conexao_db/conexao.php';
require_once __DIR__ . '/../models/usuarios_crud.php';

$idUsuario = $_SESSION['id'] ?? null;

if (!$idUsuario) {
    header('Location: ../views/auth/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';

    if ($acao === 'salvar') {
        $nome = $_POST['nome'] ?? '';
        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? null;
        if ($senha === '') $senha = null;

        atualizarUsuario($idUsuario, $nome, $email, $senha);
        header('Location: ../views/perfil.php?atualizado=1');
        exit;

    } elseif ($acao === 'deletar') {
        deletarUsuario($idUsuario);
        session_destroy();
        header('Location: ../public/index.php');
        exit;
    }
}
header('Location: ../views/perfil.php?erro=1');
exit;