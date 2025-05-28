<?php
require_once __DIR__ . '/../controllers/safe_page.php';
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Do it</title>

    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/stylePlus.css">

</head>

<body id="body">

    <?php include './partials/header.php'; ?>

    <main>
        <?php include './partials/sidebar.php'; ?>

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
        <div id="popupCriarLembrete" class="popup" onclick="fecharPopupLembrete(event, this)">
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
        <?php include './partials/footer.php'; ?>
    </footer>

    <script src="../public/Javascript/scriptplus.js"></script>
    <script src="../public/Javascript/search.js"></script>

</body>

</html>