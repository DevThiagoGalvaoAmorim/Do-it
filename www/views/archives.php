<?php
require_once __DIR__ . '/../controllers/safe_page.php';
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notas Arquivadas</title>

    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/style.css">

</head>

<body id="body">

    <?php include 'partials/header.php'; ?>

    <main>

        <?php
        session_start();
        //corrigir verificação posteriormente
        if ($_SESSION['email'] == 'admin@mail') {
            include './partials/sidebarAdmin.php';
        } else {
            include './partials/sidebar.php';
        }
        ?>

        <div class="container-notas">
            <div class="cabecalho-notas">
                <h2>Notas Arquivadas</h2>
                <hr>
            </div>

            <div class="listagem-de-notas">

                <?php
                $a = 0;

                while ($a < 10) {
                    echo '
                <div class="nota-arquivada">
                    <h3 class="nota-titulo">Título</h3>
                    <p class="nota-texto">Usuário vai anotar aqui...</p>
                    <div class="nota-botoes">
                        <button>📦</button>
                        <button>🗑️</button>
                        <button>✏️</button>
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
        <?php include 'partials/footer.php'; ?>
    </footer>
    <script src="../public/javascript/search.js"></script>
</body>

</html>