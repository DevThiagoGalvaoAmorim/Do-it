<?php
require_once './controllers/safe_page.php';
require_once './conexao_db/conexao.php';
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Do it</title>

  <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="./public/css/style.css">

</head>

<body id="body">

  <?php include './partials/header.php'; ?>

  <main>

    <?php include './partials/sidebar.php'; ?>

    <section class="conteudo_notas">

      <div class="criacao_de_notas">
        <div class="criar-nota" onclick="abrirPopupCriar('popupCriar')">
          <input type="text" placeholder="Criar uma nota..." readonly>
          <button type="button">+</button>
        </div>
        <div class="sort-buttons">
          <button class="filtro-btn" onclick="sortNotes()">
            <img src="imagens/icones/filtro.png" alt="Filtrar notas">
          </button>
          <button class="filtro-btn" onclick="sortByDate()">
            <img src="imagens/icones/calendar_down.png" alt="Ordenar por data">
          </button>
        </div>
      </div>

      <div class="listagem_de_notas">
        <div class="notas">
          <div class="listagem_de_notas">
            <div class="notas"></div>
          </div>
        </div>
      </div>

    </section>

    <!-- Popups -->
    <div id="popupCriar" class="popup" onclick="fecharPopup(event, this)">
      <div class="conteudo" onclick="event.stopPropagation()">
        <input type="hidden" class="id-input" value="">
        <input type="text" class="titulo-input" placeholder="Título" />

        <textarea class="texto-input" placeholder="Escreva seu texto aqui..."></textarea>

        <div class="linha-icones">
          <button class="icone botao1"></button>
          <button class="icone botao2"></button>
          <button class="icone botao3"></button>
        </div>

        <div class="footer-form">
          <button class="salvar-botao" onclick="salvarNota()">Salvar</button>
        </div>
      </div>
    </div>
  </main>

  <footer>
    <?php include './partials/footer.php'; ?>
  </footer>

  <script src="./public/javascript/script.js"></script>
  <script src="./public/javascript/search.js"></script>
</body>

</html>