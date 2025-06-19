<?php 

require __DIR__ . '/../../controllers/log_atividades.php';

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../public/css/admin_log.css">
    <link rel="stylesheet" href="../../public/css/style.css">

    <style>
        
        .filtro-opcoes {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 60px;
        }

        .filtro-opcoes label {
            font-weight: bold;
            color: #555;
            margin-right: 5px;
        }

        .filtro-opcoes input[type="number"] {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .filtro-opcoes input[type="number"]:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.15);
        }

        .filtro-opcoes button {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
            background-color: #007bff;
            color: white;
            transition: background-color 0.2s;
        }

        .filtro-opcoes button:hover {
            background-color: #0056b3;
        }

    </style>

</head>

<body>
    <header>
        <section class="search">
            <div class="header-content">
                <div class="header-logo">
                    <a href="./main.php">
                        <img src="../../public/imagens/logo_branca.png" alt="Do it Logo">
                    </a>
                </div>
                <h1>Administrador</h1>
                <div class="header-actions">
                    <a href="logout.php" class="sair-btn">Sair</a>
                </div>
            </div>
        </section>
    </header>

    <main>
        <div class="admin-sidebar">
            <nav class="admin-nav">
                <a href="../admin_view.php">Usuários</a>
                <a href="admin_stats_view.php">Estatísticas</a>
                <a href="#" class="active">Log De Atividades</a>
            </nav>
        </div>

        <div class="admin-content">
            <h2 class="page-title">Log de Atividades</h2>
            <input type="text" class="search-box" placeholder="Buscar Atividade..." id="searchLogInput">

            <div class="filtro-opcoes">
                <label for="filterUserId">Filtrar por ID do Usuário:</label>
                <input type="number" id="filterUserId" placeholder="Digite o ID">
                <button type="button" onclick="filtrarPorUsuario()">Filtrar</button>
                <button type="button" onclick="limparFiltroUsuario()">Limpar</button>
            </div>

            <table class="users-table" id="logTable">
                <thead>
                    <tr>
                        <th>ID do Usuário</th>
                        <th>Data e Hora</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody id="logTableBody">
                    <?php echo gerar_linhas_tabela_atividades();?>
                </tbody>
            </table>
        </div>
    </main>

    <script src="../../public/javascript/log_admin.js"></script>

    <footer>
        <?php include 'footer.php'; ?>
    </footer>
</body>

</html>