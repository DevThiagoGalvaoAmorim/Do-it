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
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Do it</title>
  <link rel="stylesheet" href="landing_page.css">
</head>
<body>

  <header class="navbar">
    <div class="logo">
      <img src="imagens/logo_preta.png" alt="Do it Logo">
      <span class="logo-text">Do it</span>
    </div>
    <nav class="nav-links">
      <a href="#">Sobre</a>
      <a href="./login.html" class="btn-outline">Login</a>
      <a href="./cadastro.html" class="btn-dark">Registrar</a>
    </nav>
  </header>

  <main>
    <section class="hero">
      <div class="hero-content">
        <div class="hero-text">
          <h1>Transforme anotações em produtividade</h1>
          <p>Do it é um app de anotações intuitivo para quem precisa registrar ideias e tarefas rapidamente</p>
          <a href="./login.html" class="btn-dark">ACESSE</a>
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

  <footer class="footer">
    <div class="footer-content">
        <p>© 2025. Todos os direitos reservados.</p>
        <div class="footer-links">
            <a href="#">Política de Privacidade</a>
            <a href="#">Termos e Condições</a>
            <a href="#">Política de Cookies</a>
        </div>
        <div class="footer-right">
            <a href="#"><img src="imagens/icones/icons8-facebook.svg" alt="Facebook"></a>
            <a href="#"><img src="imagens/icones/icons8-instagram.svg" alt="Instagram"></a>
            <a href="#"><img src="imagens/icones/icons8-twitter.svg" alt="Twitter"></a>
            <a href="#"><img src="imagens/icones/icons8-linkedin.svg" alt="LinkedIn"></a>
        </div>
    </div>
  </footer>

</body>
</html>