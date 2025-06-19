<?php
require_once __DIR__ . '/../controllers/safe_page.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../public/css/sac.css">
    <title>Do it</title>
</head>

<body>
    <header>
        <div class="back">
            <a href="../views/main.php">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-arrow-return-left" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M14.5 1.5a.5.5 0 0 1 .5.5v4.8a2.5 2.5 0 0 1-2.5 2.5H2.707l3.347 3.346a.5.5 0 0 1-.708.708l-4.2-4.2a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 8.3H12.5A1.5 1.5 0 0 0 14 6.8V2a.5.5 0 0 1 .5-.5" />
                </svg>
            </a>

        </div>

        <div class="Contact">
            <a href="#">Sobre nós</a>

            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-github"
                viewBox="0 0 16 16">
                <path
                    d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27s1.36.09 2 .27c1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.01 8.01 0 0 0 16 8c0-4.42-3.58-8-8-8" />
            </svg>

            <a href="../public/index.php">Landing page</a>
        </div>
    </header>
    <main>
        <div class="container">
            <h1>Entre em contato</h1>

            <div class="Subject">
                <span>Nome <span class="obrigatorio">*</span> </span>
                <input type="text" aria-label="Seu nome" class="name" placeholder="Ex: Cristiano Ronaldo" required>

                <span>Email <span class="obrigatorio">*</span> </span>
                <input type="text" aria-label="Seu nome" class="email" placeholder="Ex: Doit@gmail.com" required>


                <span>Assunto <span class="obrigatorio">*</span> </span>
                <input type="text" aria-label="Seu nome" class="assunto" placeholder="Escreve seu problema aqui..."
                    required>

                <button class="enviar" id="enviar">Enviar</button>

            </div>
        </div>

        <div class="modal">
            <span id="closed">x</span>
            <h3>Sua reclamação foi enviada</h3>
            <p>Sua reclamação foi enviado com sucesso, não se preocupe resolveremos seu problema os mais rápido possivel
            </p>
        </div>
    </main>

    <script src="../public/Javascript/sac.js"></script>
</body>

</html>