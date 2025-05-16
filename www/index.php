<?php
session_start();
require_once __DIR__ .'/conexao_db/conexao.php';

$_SESSION['nome'] = 'user';
$_SESSION['email'] = 'example@email';
$_SESSION['senha'] = '123';

$stmt = $pdo->query("SELECT * FROM usuarios WHERE id = 1");
$data = $stmt->fetch(PDO::FETCH_ASSOC);
if($data == false){
    $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha);");
    $stmt->execute([
        ':nome' => $_SESSION['nome'],
        ':email' => $_SESSION['email'],
        ':senha' => $_SESSION['senha']
    ]);
}


?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Do it</title>
  <link rel="stylesheet" href="landing_page.css">
  <script src="js/parallax.js"></script>
  <style>
      .parallax-bg {
          position: fixed !important;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          z-index: -1;
          background-image: url('./imagens/fundo polvo.png');
          background-size: cover;
      }
      
      .content {
          position: relative;
          z-index: 1;
      }
  </style>
</head>
<body>
<<<<<<< HEAD
  <!-- Elemento de fundo com parallax -->
  <div class="parallax-bg parallax" data-speed="0.3"></div>
  
  <div class="content">
=======

  <header class="navbar">
    <div class="logo">
      <img src="imagens/logo_preta.png" alt="Do it Logo">
      <span class="logo-text">Do it</span>
    </div>
    <nav class="nav-links">
      <a href="#">Sobre</a>
      <a href="./login.php" class="btn-outline">Login</a>
      <a href="./cadastro.php" class="btn-dark">Registrar</a>
    </nav>
  </header>

  <main>
>>>>>>> ace72197039c8ba0b59a3c63a1ec82d09ba39637
    <section class="hero">
      <div class="hero-content">
        <div class="hero-text">
          <h1>Transforme anotações em produtividade</h1>
          <p>Do it é um app de anotações intuitivo para quem precisa registrar ideias e tarefas rapidamente</p>
          <a href="./login.php" class="btn-dark">ACESSE</a>
        </div>
        <div class="hero-image">
          <img src="imagens/polvo_landing.png" alt="Do it App Interface">
        </div>
      </div>
    </section>

    <section class="features">
      <div class="features-image">
        <img src="imagens/logo_preta.png" alt="Do it Features">
      </div>
      <div class="features-text">
        <h2>"Anote tudo, lembre de tudo"</h2>
        <p>Anotar tarefas e pensamentos nunca foi tão simples. Chega de bagunça mental e listas espalhadas</p>
        <ul>
          <li>• Interface limpa, intuitiva e rápida: abra, anote, siga em frente.</li>
          <li>• Do it traz praticidade de verdade.</li>
          <li>• Notas rápidas, vida organizada</li>
        </ul>
      </div>
    </section>
  </main>

  <footer>
    <?php include 'footer.php'; ?>
  </footer>

</body>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar o efeito parallax
        initParallax();
    });
</script>
</html>