<?php
session_start();
require_once __DIR__ . '/conexao_db/usuarios_crud.php';

// Supondo que o ID do usuário está salvo na sessão
$idUsuario = $_SESSION['id'] ?? null;

// Atualizar dados
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'salvar') {
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? null;
    if ($senha === '') $senha = null; // Não atualizar senha se campo vazio

    atualizarUsuario($idUsuario, $nome, $email, $senha);
    // Opcional: recarregar os dados do usuário após atualização
}

// Deletar conta
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'deletar') {
    deletarUsuario($idUsuario);
    session_destroy();
    header('Location: index.php');
    exit;
}

// Buscar dados do usuário para exibir no formulário


$qtdNotas = 0;
$dataCriacao = '';
if ($idUsuario) {
    // Buscar quantidade de notas
    require_once __DIR__ . '/conexao_db/conexao.php';
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

$usuario = buscarUsuarioPorId($idUsuario);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Dados</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="perfil.css">
</head>

<body>
    <header style="display: flex; align-items: center; padding: 16px 30px; background-color:#18191c;">
        <a href="main.php">
            <img src="./imagens/logo_branca.png" alt="Polvo escrevendo" class="logo" style="width: 90px;">
        </a>
    </header>

    <div class="background">
        <div class="main-container">
            <div class="profile-container">
                <div class="profile-pic-container">
                    <img src="./imagens/astronauta-user.png" alt="Foto de perfil" class="profile-pic">
                </div>
                <div class="profile-content">
                    <div class="profile-header">
                        <h1><?= htmlspecialchars($usuario['nome'] ?? 'Seu Nome Aqui') ?></h1>
                        <p><?= htmlspecialchars($usuario['email'] ?? 'email@exemplo.com') ?></p>
                    </div>
                    <div class="stats-row">
                        <p><strong>Notas:</strong> <?= $qtdNotas ?></p>
                        <p><strong>Entrou em <?= $dataCriacao ? htmlspecialchars($dataCriacao) : 'Data desconhecida' ?></strong></p>
                    </div>
                </div>
            </div>

            <div>
                <h2 class="edit-title">Editar Dados</h2>
                <form class="edit-form" method="post">
                    <input type="hidden" name="acao" value="salvar">
                    <div class="form-group">
                        <label for="name">Nome:</label>
                        <input type="text" id="name" name="nome" value="<?= htmlspecialchars($usuario['nome'] ?? '') ?>" placeholder="Digite seu nome">
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($usuario['email'] ?? '') ?>" placeholder="email@exemplo.com">
                    </div>
                    <div class="form-group">
                        <label for="password">Senha:</label>
                        <input type="password" id="password" name="senha" placeholder="Nova Senha">
                    </div>
                    <div class="form-group">
                        <!-- <label for="confirm-password">Confirme a Senha</label> -->
                        <input type="password" id="confirm-password" placeholder="Confirme a Senha">
                    </div>
                    <button type="submit" class="save-btn"><i>💾</i>Salvar</button>
                </form>
            </div>
            <div class="delete-section">
                <h3 style="color: #e57373;">Deletar Conta</h3>
                <p style="color: #ccc;">Esta ação é irreversível. Tem certeza que deseja excluir sua conta?</p>
                <form method="post" onsubmit="return confirm('Tem certeza que deseja deletar sua conta? Esta ação não pode ser desfeita.');">
                    <input type="hidden" name="acao" value="deletar">
                    <button type="submit" style="background-color: #e57373; color: white; border: none; padding: 12px 32px; border-radius: 6px; font-size: 1.1rem; font-weight: 500; cursor: pointer;">
                        Deletar Conta
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>