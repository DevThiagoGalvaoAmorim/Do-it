<?php
require_once 'conexao_db/conexao.php';
require_once 'conexao_db/usuarios_crud.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    // Aqui você pode processar os dados, como salvar no banco de dados
    if (!empty($nome) && !empty($email) && !empty($senha)) {
        $id = criarUsuario($nome, $email, $senha);

        if ($id){
            session_start(); // Inicia a sessão
            $_SESSION['id'] = $id;
            $_SESSION['nome'] = $nome;
            $_SESSION['email'] = $email;
            header('Location: main.php');
            exit;
        }

    } else {
        echo '<script>alert("Preencha todos os campos!");</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="cadastro.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="js/parallax.js"></script>
    <title>Cadastro - Do it</title>
    <style>
        /* Correção para o posicionamento dos elementos de fundo */
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
    </style>
</head>

<body>
    <!-- Elementos de fundo com parallax -->
    <div class="stars parallax" data-speed="0.2"></div>
    <div class="planet parallax" data-speed="0.4"></div>
    
    <form class="container" method="POST" action="">
        <div class="content-sign">
            <img src="./imagens/logo_preta.png" alt="polvo-user" class="transition-element">
            <input type="text" placeholder="Usuário" class="user transition-element" name="nome">
            <input type="text" placeholder="Email" class="email transition-element" name="email">
            <input type="password" placeholder="Senha" class="lock transition-element" name="senha">
            <a href="login.php" onclick="return parallaxTransition('login.php')" class="jatenhoconta transition-element">Já tenho conta</a>
            <button class="btn-sign transition-element" type="submit">Cadastre-se</button>
        </div>
    </form>
</body>

</html>