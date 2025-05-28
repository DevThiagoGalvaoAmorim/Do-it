<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/login.css">
    <title>Do it</title>
</head>

<body>
    <form class="container" id="container" method="POST" action="../../controllers/auth_controller.php?action=login">
        <div class="loginbox" id="loginbox">

            <div class="btn-cadastrar">
                <h2>Novo Login</h2>
                <a href="cadastro.php">Criar conta</a>
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
