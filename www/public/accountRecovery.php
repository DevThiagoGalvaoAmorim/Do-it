<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../public/css/login.css">
    <title>Do it</title>
    <style></style>
</head>

<body>
    <form class="container" id="container" method="POST" action="../controllers/enviar_link_recuperacao.php">
        <div class="loginbox" id="loginbox">

            <div class="btn-cadastrar">
                <h2 class="recuperacao">Recuperar Conta</h2>
                <a href="../views/auth/cadastro.php">Criar conta</a>
            </div>

            <div class="content-login">
                <img src="../../public/imagens/logo_preta.png" alt="user-polvo">
                <!-- Adicionados os atributos name -->
                 <h3 class="texto-recuperacao">Insira o seu email cadastrado: </h3>
                <input type="text" name="email" placeholder="email@exemplo.com" class="lock">
                <a href="login.php">Fazer Login</a>
                <button class="btn-enter" type="submit">Enviar link </button>
            </div>

        </div>
    </form>
</body>

</html>
