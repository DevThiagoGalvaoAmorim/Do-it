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
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="login.css">
    <title>Do it</title>
</head>

<body>
    <form class="container" id="container" method="POST" action="login.php">
        <div class="loginbox" id="loginbox">

            <div class="btn-cadastrar">
                <h2>Novo Login</h2>
                <a href="./cadastro.php">Criar conta</a>
            </div>

            <div class="content-login">
                <img src="./imagens/logo_preta.png" alt="user-polvo">
                <!-- Adicionados os atributos name -->
                <input type="text" name="email" placeholder="Email@exemplo.com" class="lock">
                <input type="password" name="senha" placeholder="Senha" class="lock">
                <a href="">Esqueceu a senha</a>
                <button class="btn-enter" type="submit">Entrar</button>
            </div>

        </div>
    </form>
</body>

</html>
