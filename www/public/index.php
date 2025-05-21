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
      <a href="./login.php" class="btn-outline">Login</a>
      <a href="./cadastro.php" class="btn-dark">Registrar</a>
    </nav>
  </header>

  <main>
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
</html>