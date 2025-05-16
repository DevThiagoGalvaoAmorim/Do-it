<?php
session_start();

require_once 'conexao_db/conexao.php';
require_once 'conexao_db/usuarios_crud.php';

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if (empty($nome) || empty($email) || empty($senha)) {
        $erro = "Preencha todos os campos!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = "E-mail inválido!";
    } else {
        $id = criarUsuario($nome, $email, $senha); // Usa password_hash internamente

        if ($id) {
            $_SESSION['id'] = $id;
            $_SESSION['nome'] = $nome;
            $_SESSION['email'] = $email;
            header('Location: main.php');
            exit;
        } else {
            $erro = "Erro ao criar usuário. E-mail já pode estar em uso.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Do it</title>
    <link rel="stylesheet" href="cadastro.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="js/parallax.js"></script>
    <style>
        .stars {
            position: fixed !important;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -2;
            background-image: url('./imagens/background-login.png');
            background-size: cover;
        }

        .planet {
            position: fixed !important;
            bottom: -50px;
            right: 50px;
            z-index: -1;
        }

        .container {
            position: relative;
            z-index: 1;
        }

        .error-message {
            color: red;
            font-size: 0.9em;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <!-- Elementos de fundo com parallax -->
    <div class="stars parallax" data-speed="0.2"></div>
    <div class="planet parallax" data-speed="0.4"></div>

    <form class="container" method="POST" action="">
        <div class="content-sign">
            <img src="./imagens/logo_preta.png" alt="polvo-user" class="transition-element">
            <input type="text" placeholder="Usuário" class="user transition-element" name="nome" required>
            <input type="email" placeholder="Email" class="email transition-element" name="email" required>
            <input type="password" placeholder="Senha" class="lock transition-element" name="senha" required>
            <a href="login.php" class="jatenhoconta transition-element">Já tenho conta</a>
            <button class="btn-sign transition-element" type="submit">Cadastre-se</button>

            <?php if (!empty($erro)): ?>
                <div class="error-message"><?= htmlspecialchars($erro) ?></div>
            <?php endif; ?>
        </div>
    </form>
</body>

</html>