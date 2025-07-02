<?php 

function tela_de_mensagem($mensagem){
  echo '
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../public/css/warning.css">
    <title>Do it</title>
</head>

<body>
    <section class="container" id="container">
        <div class="warningbox" id="warningbox">

            <div class="content-warning">
                <img src="../../public/imagens/logo_preta.png" alt="user-polvo">
                <div class="mensagem">
                    <p> '. $mensagem . ' </p>
                </div>
                <button class="btn-enter" type="button" ><a href="/views/auth/login.php" class="jatenhoconta" color="white">Voltar ao Login</a></button>
            </div>

        </div>
    </section>
</body>

</html>';
}

