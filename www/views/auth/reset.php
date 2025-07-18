<?php
require_once '../../models/account_config.php';

// $token = $_GET['token'] ?? '';
// if (!$token) {
//     echo "Token inválido.";
//     exit;
// }

// $email = buscarToken($token);

// if (!$email) {
//     echo "Token inválido ou já usado.";
//     exit;
// }
?>

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
    <form class="container" id="container" method="POST" action="../../controllers/atualizar_senha.php">
        <div class="loginbox" id="loginbox">

            <div class="btn-cadastrar">
                <h2 class="recuperacao">Redefinir Senha</h2>
            </div>

            <div class="content-login">
                <img src="../../public/imagens/logo_preta.png" alt="user-polvo">
                <!-- Adicionados os atributos name -->
                 <h3 class="texto-recuperacao">Insira a sua nova senha: </h3>
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                <input type="password" name="senha" placeholder="Insira sua nova senha" class="lock">
                <input type="password" name="senha-confirm" placeholder="Confirme a senha" class="lock">
                <a href="login.php">Fazer Login</a>
                <button class="btn-enter" type="submit">Salvar Senha </button>
            </div>

        </div>
    </form>
</body>

</html>
