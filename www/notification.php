<?php
require_once 'safe_page.php';
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lembretes</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">

</head>

<body id="body">

    <?php include 'header.php'; ?>

    <main>

        <?php include 'sidebar.php'; ?>

        <section class="bell-container">
            <div class="new-bell">
                <div class="input-bell">
                    <input type="text" name="add-bell" id="input-folder" placeholder="Criar novo lembrete...">
                    <button>
                        +
                    </button>
                </div>
            </div>

            <div class="content-bell">
                <h1>Lembretes</h1>
                <hr>

                <div class="grid-bell">
                    <div class="bell">
                        <h3>Titulo</h3>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Perferendis delectus sequi animi
                            .</p>
                        <input type="datetime-local" name="bell-time" id="bell-time">
                        <button>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-pencil-square" viewBox="0 0 16 16">
                                <path
                                    d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                <path fill-rule="evenodd"
                                    d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                            </svg>
                        </button>
                    </div>

                </div>
            </div>
        </section>

    </main>

    <footer>
        <?php include 'footer.php'; ?>
    </footer>

    <script src="script.js"></script>
    <script src="./js/search.js"></script>
</body>

</html>