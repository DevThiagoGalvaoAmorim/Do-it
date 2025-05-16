<?php
session_start();
require_once 'conexao_db/conexao.php';
require_once 'conexao_db/usuarios_crud.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    if (!empty($email) && !empty($senha)) {
        $usuario = buscarUsuario($email, $senha);
        
        if ($usuario) {
            $_SESSION['id'] = $usuario['id'];
            $_SESSION['nome'] = $usuario['nome'];
            $_SESSION['email'] = $usuario['email'];
            $_SESSION['tipo'] = $usuario['tipo'] ?? 'user';
            
            // Redirecionar com base no tipo de usuário
            if (isset($usuario['tipo']) && $usuario['tipo'] === 'admin') {
                header('Location: admin.php');
            } else {
                header('Location: main.php');
            }
            exit;
        } else {
            echo '<script>alert("Email ou senha incorretos!");</script>';
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
    <link rel="stylesheet" href="login.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="js/parallax.js"></script>
    <title>Login - Do it</title>
    <style>
        /* Correção para o posicionamento dos elementos de fundo */
        body {
            margin: 0;
            padding: 0;
            overflow: hidden; /* Impede barras de rolagem */
            background-color: #000000; /* Cor de fallback */
        }
        
        /* Fundo com a imagem background-login.png preenchendo toda a tela */
        .background-full {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -3;
            background-image: url('./imagens/background-login.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        
        /* Removido o bloco .small-polvo */
        
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
        
        /* Estilo para as estrelas com imagem star.png */
        .star-img {
            position: fixed;
            width: 20px;
            height: 20px;
            background-image: url('./imagens/star.png');
            background-size: contain;
            background-repeat: no-repeat;
            z-index: -2;
        }
    </style>
</head>
<body>
    <!-- Adicionar o fundo completo -->
    <div class="background-full"></div>
    
    <!-- Removido o elemento small-polvo -->
    <div class="planet parallax" data-speed="0.4"></div>
    
    <!-- Adicionar várias estrelas com efeito parallax -->
    <div class="star-img parallax" data-speed="0.1" style="top: 10%; left: 20%;"></div>
    <div class="star-img parallax" data-speed="0.15" style="top: 15%; left: 80%;"></div>
    <div class="star-img parallax" data-speed="0.12" style="top: 30%; left: 40%;"></div>
    <div class="star-img parallax" data-speed="0.18" style="top: 70%; left: 10%;"></div>
    <div class="star-img parallax" data-speed="0.14" style="top: 85%; left: 70%;"></div>
    <div class="star-img parallax" data-speed="0.16" style="top: 50%; left: 90%;"></div>
    <div class="star-img parallax" data-speed="0.13" style="top: 25%; left: 60%;"></div>
    <div class="star-img parallax" data-speed="0.17" style="top: 60%; left: 30%;"></div>
    <div class="star-img parallax" data-speed="0.11" style="top: 40%; left: 15%;"></div>
    <div class="star-img parallax" data-speed="0.19" style="top: 75%; left: 85%;"></div>
    <div class="star-img parallax" data-speed="0.13" style="top: 20%; left: 45%;"></div>
    <div class="star-img parallax" data-speed="0.15" style="top: 55%; left: 75%;"></div>
    <div class="star-img parallax" data-speed="0.17" style="top: 35%; left: 25%;"></div>
    <div class="star-img parallax" data-speed="0.14" style="top: 65%; left: 55%;"></div>
    <div class="star-img parallax" data-speed="0.12" style="top: 5%; left: 35%;"></div>
    
    <form class="container" method="POST" action="">
        <div class="loginbox" id="loginbox">

            <div class="btn-cadastrar">
                <h2>Novo Login</h2>
                <a href="./cadastro.php" onclick="return parallaxTransition('cadastro.php')">Criar conta</a>
            </div>

            <div class="content-login">
                <img src="./imagens/logo_preta.png" alt="user-polvo" class="transition-element">
                <!-- Adicionados os atributos name -->
<<<<<<< HEAD
                <input type="text" name="email" placeholder="Email@exemplo.com" class="email transition-element">
                <input type="password" name="senha" placeholder="Senha" class="lock transition-element">
                <a href="" class="transition-element">Esqueceu a senha</a>
                <button class="btn-enter transition-element" type="submit" onclick="if(this.form.checkValidity()) { parallaxTransition('main.php'); return false; }">Entrar</button>
=======
                <input type="text" name="email" placeholder="Email@exemplo.com" class="lock">
                <input type="password" name="senha" placeholder="Senha" class="lock">
                <a href="">Esqueceu a senha</a>
                <button class="btn-enter" type="submit">Entrar</button>
>>>>>>> ace72197039c8ba0b59a3c63a1ec82d09ba39637
            </div>

        </div>
    </form>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar o efeito parallax
            initParallax();
        });
    </script>
</body>
</html>
