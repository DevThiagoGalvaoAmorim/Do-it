 <?php

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="cadastro.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <title>Do it</title>
</head>

<body>
    <form class="container" method="POST" action="../../controllers/AuthController.php?action=login">
        <div class="content-sign">
            <img src="./imagens/logo_preta.png" alt="polvo-user">
            <input type="text" placeholder="Usuário" class="user" name="nome">
            <input type="text" placeholder="Email" class="email" name="email">
            <input type="password" placeholder="Senha" class="lock" name="senha">
            <a href="login.php" class="jatenhoconta">Já tenho conta</a>
            <button class="btn-sign" type="submit">Cadastre-se</button>
        </div>
    </form>
</body>

</html>
?>
