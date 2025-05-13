<?php
require_once 'conexao_db/conexao.php';
require_once 'conexao_db/usuarios_crud.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'], $_POST['senha'])) {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $resultadoBusca = buscarUsuario($email, $senha);

    if ($resultadoBusca) {
        session_start();
        $_SESSION['id'] = $resultadoBusca['id'];
        $_SESSION['nome'] = $resultadoBusca['nome'];
        $_SESSION['email'] = $resultadoBusca['email'];
        header('Location: main.php');
        exit;
    } else {
        session_start();
        $_SESSION = [];
        session_destroy();
        echo '<script>alert("Erro ao fazer login. Credenciais não encontradas!");</script>';
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
                <input type="text" name="email" placeholder="Email@exemplo.com" class="user">
                <input type="password" name="senha" placeholder="Senha" class="lock">
                <a href="">Esqueceu a senha</a>
                <button class="btn-enter" type="submit">Entrar</button>
            </div>

        </div>
    </form>
</body>

</html>
