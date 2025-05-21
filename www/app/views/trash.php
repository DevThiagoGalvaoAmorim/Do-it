<?php
require_once 'safe_page.php';
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lixeira</title>

    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">

</head>

<body id="body">

    <?php include 'header.php'; ?>

    <main>

        <?php include 'sidebar.php'; ?>

        <div class="container-notas">
            <div class="cabecalho-notas">
                <h2>Lixeira</h2>
                <hr>
            </div>

            <div class="listagem-de-notas">

                <?php
                $a = 0;

                while ($a < 10) {
                    echo '
                <div class="nota-lixeira">
                    <h3 class="nota-titulo">Título</h3>
                    <p class="nota-texto">Usuário vai anotar aqui...</p>
                    <div class="nota-botoes">
                        <button>❎</button>
                    </div>
                </div>
            ';
                    $a++;
                }
                ?>

            </div>
        </div>
    </main>

    <footer>
        <?php include 'footer.php'; ?>
    </footer>
    <script src="./js/search.js"></script>
</body>

</html>