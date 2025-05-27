<?php
require_once 'safe_page.php';
require_once 'conexao_db/conexao.php';
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Do it</title>

    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="stylePlus.css">

</head>

<body id="body">

    <?php include 'header.php'; ?>

    <main>
        <?php include 'sidebar.php'; ?>

        <section class="conteudo_lembretes">
            <div class="criacao_de_lembretes">
                <div class="criar-lembrete" onclick="abrirPopupCriarLembrete('popupCriarLembrete')">
                    <input type="text" placeholder="Criar um lembrete..." readonly>
                    <button type="button">+</button>
                </div>
            </div>

            <div class="listagem_de_lembretes">
                <div class="lembretes"></div>
            </div>
        </section>

        <!-- Popup de criação -->
        <div id="popupCriarLembrete" class="popup" onclick="fecharPopup(event, this)">
            <div class="conteudo" onclick="event.stopPropagation()">
                <input type="hidden" class="id-lembrete-input" value="">
                <input type="text" class="titulo-lembrete-input" placeholder="Título" />

                <textarea class="descricao-lembrete-input" placeholder="Escreva seu lembrete..."></textarea>

                <div class="datatime_lembrete">
                    <input type="datetime-local" name="datatime_lembrete" id="datatime_lembrete">
                </div>

                <div class="footer-form">
                    <button class="salvar-botao" onclick="salvarLembrete()">Salvar</button>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <?php include 'footer.php'; ?>
    </footer>

    <script src="./scriptplus.js"></script>
    <script src="./js/search.js"></script>

</body>

</html>