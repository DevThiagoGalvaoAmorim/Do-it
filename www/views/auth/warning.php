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
                <img src="logo_preta.png" alt="user-polvo">
                <div class="mensagem">
                    <p> '. $mensagem . ' </p>
                </div>
                <button class="btn-enter" type="button" onclick="window.location.href='pagina_de_login.html';">Voltar ao Login</button>
            </div>

        </div>
    </section>
</body>

</html>';
}

