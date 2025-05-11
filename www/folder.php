<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pastas</title>

    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">

</head>

<body id="body">

    <?php include 'header.php'; ?>

    <main>

        <?php include 'sidebar.php'; ?>

        <section class="folder-container">
            <div class="new-folder">
                <div class="input-folder">
                    <input type="text" name="add-folder" id="input-folder" placeholder="Criar nova pasta...">
                    <button>
                        +
                    </button>
                </div>
            </div>

            <div class="content-folder">
                <h1>Pastas</h1>
                <hr>

                <div class="grid-folder">
                    <div class="folder">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-folder-fill" viewBox="0 0 16 16">
                            <path
                                d="M9.828 3h3.982a2 2 0 0 1 1.992 2.181l-.637 7A2 2 0 0 1 13.174 14H2.825a2 2 0 0 1-1.991-1.819l-.637-7a2 2 0 0 1 .342-1.31L.5 3a2 2 0 0 1 2-2h3.672a2 2 0 0 1 1.414.586l.828.828A2 2 0 0 0 9.828 3m-8.322.12q.322-.119.684-.12h5.396l-.707-.707A1 1 0 0 0 6.172 2H2.5a1 1 0 0 0-1 .981z" />
                        </svg>
                        <span>New Folder</span>
                    </div>

                    <div class="folder">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-folder-fill" viewBox="0 0 16 16">
                            <path
                                d="M9.828 3h3.982a2 2 0 0 1 1.992 2.181l-.637 7A2 2 0 0 1 13.174 14H2.825a2 2 0 0 1-1.991-1.819l-.637-7a2 2 0 0 1 .342-1.31L.5 3a2 2 0 0 1 2-2h3.672a2 2 0 0 1 1.414.586l.828.828A2 2 0 0 0 9.828 3m-8.322.12q.322-.119.684-.12h5.396l-.707-.707A1 1 0 0 0 6.172 2H2.5a1 1 0 0 0-1 .981z" />
                        </svg>
                        <span>New Folder 1</span>
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