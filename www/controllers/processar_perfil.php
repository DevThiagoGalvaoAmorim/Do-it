<?php
session_start();
require_once __DIR__ . '/../conexao_db/conexao.php';
require_once __DIR__ . '/../models/usuarios_crud.php';

$idUsuario = $_SESSION['id'] ?? null;

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
} else {
    
$qtdNotas = 0;
$dataCriacao = '';
if ($idUsuario) {
    // Buscar quantidade de notas
    require_once __DIR__ . '/../conexao_db/conexao.php';
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM notas WHERE id_usuario = :id_usuario");
    $stmt->execute([':id_usuario' => $idUsuario]);
    $qtdNotas = $stmt->fetchColumn();

    // Buscar data de criação do usuário
    $stmt = $pdo->prepare("SELECT criado_em FROM usuarios WHERE id = :id");
    $stmt->execute([':id' => $idUsuario]);
    $dataCriacao = $stmt->fetchColumn();
    if ($dataCriacao) {
        setlocale(LC_TIME, 'pt_BR.utf8', 'pt_BR', 'Portuguese_Brazil.1252');
        $dataCriacao = date('F \d\e Y', strtotime($dataCriacao));
    }
}

return $usuario = buscarUsuarioPorId($idUsuario);
}



header('Location: ../views/perfil.php?erro=1');
exit;