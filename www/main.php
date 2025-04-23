<?php
require_once 'conexao_db/conexao.php';
//require_once 'conexao_db/notas_crud.php';
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Do it</title>

  <!--   <link rel="shortcut icon" href="/imagens/logo_preta.png" type="image/x-icon"> 
  Consertar depois!!!   
  -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="main.css">

</head>

<body>

  <header>
    <section class="search">
      <img src="/imagens/logo_branca.png" alt="Polvo escrevendo">
      <div class="buscar">
        <input type="text" placeholder=" 🍳    Buscar..." onkeydown="verificaEnter(event)">
    
      </div>
      <svg id="user" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
        class="bi bi-person-circle" viewBox="0 0 16 16">
        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
        <path fill-rule="evenodd"
          d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
      </svg>
    </section>
  </header>

  <main>
    
    <section class="sidebar">

      <button class="toggle-btn">
          <span>Menu</span>
      </button>

      <ul class="menu">
          <li>
              <a href="#">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                      class="bi bi-folder-fill" viewBox="0 0 16 16">
                      <path
                          d="M9.828 3h3.982a2 2 0 0 1 1.992 2.181l-.637 7A2 2 0 0 1 13.174 14H2.825a2 2 0 0 1-1.991-1.819l-.637-7a2 2 0 0 1 .342-1.31L.5 3a2 2 0 0 1 2-2h3.672a2 2 0 0 1 1.414.586l.828.828A2 2 0 0 0 9.828 3m-8.322.12q.322-.119.684-.12h5.396l-.707-.707A1 1 0 0 0 6.172 2H2.5a1 1 0 0 0-1 .981z" />
                  </svg>
                  <span>Pastas</span>
              </a>
          </li>
          <li>
              <a href="#">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                      class="bi bi-bell-fill" viewBox="0 0 16 16">
                      <path
                          d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2m.995-14.901a1 1 0 1 0-1.99 0A5 5 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901" />
                  </svg>
                  <span>Notificações</span>
              </a>
          </li>
          <li>
              <a href="#">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                      class="bi bi-archive-fill" viewBox="0 0 16 16">
                      <path
                          d="M12.643 15C13.979 15 15 13.845 15 12.5V5H1v7.5C1 13.845 2.021 15 3.357 15zM5.5 7h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1 0-1M.8 1a.8.8 0 0 0-.8.8V3a.8.8 0 0 0 .8.8h14.4A.8.8 0 0 0 16 3V1.8a.8.8 0 0 0-.8-.8z" />
                  </svg>
                  <span>Arquivados</span>
              </a>
          </li>
          <li>
              <a href="#">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                      class="bi bi-trash-fill" viewBox="0 0 16 16">
                      <path
                          d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0" />
                  </svg>
                  <span>Lixeira</span>
              </a>
          </li>
      </ul>
  </section>

    <section class="conteudo_notas">

      <div class="criacao_de_notas">
        <div class="criar-nota" onclick="abrirPopup('popupCriar')">
          <input type="text" placeholder="Criar uma nota..." readonly>
          <button type="button">+</button>
        </div>
      </div>
  
      <div class="listagem_de_notas">
        <div class="notas">
  
          <!-- O código em php vai gerar várias das divs a seguir, de acordo com o que estiver no banco de dados -->
          <!--<div class="nota" onclick="abrirPopup('popupCriar')">
            <h4>Título</h4>
            <p>Usuário vai anotar aqui…</p>
            <div class="icones">
              <button type="button" class="archive_button">🗑️</button>
              <button type="button" class="">📥</button>
            </div>
          </div> -->

          <div class="listagem_de_notas">
            <div class="notas"></div>
          </div>
          
  
        </div>
  
      </div>

    </section>

    <!-- Popups -->
    <div id="popupCriar" class="popup" onclick="fecharPopup(event, this)">
      <div class="conteudo" onclick="event.stopPropagation()">
        <input type="text" class="titulo-input" placeholder="Título" />
    
        <textarea class="texto-input" placeholder="Escreva seu texto aqui..."></textarea>
    
        <div class="linha-icones">
          <button class="icone botao1"></button>
          <button class="icone botao2"></button>
          <button class="icone botao3"></button>
        </div>
    
        <div class="footer-form">
          <button class="salvar-botao" onclick="criarNota()">Salvar</button>
        </div>
      </div>
    </div>
    <script src="script.js"></script>
</body>

</html>