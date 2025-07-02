<?php require_once __DIR__ . '/../../controllers/admin_only.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../public/css/stats_admin.css">
    <link rel="stylesheet" href="../../public/css/pie_chart.css">
    <link rel="stylesheet" href="../../public/css/bar_chart.css">
</head>

<body>
    <header class="admin-stats-header">
        <div class="admin-stats-header-content">
            <div class="admin-stats-header-logo">
                <a href="../main.php">
                    <img src="../../public/imagens/logo_branca.png" alt="Do it Logo">
                </a>
            </div>
            <h1>Administrador</h1>
            <div class="admin-stats-header-actions">
                <a href="../../controllers/logout.php" class="admin-stats-sair-btn">Sair</a>
            </div>
        </div>
    </header>

    <main class="admin-stats-main">
        <div class="admin-stats-sidebar">
            <nav class="admin-stats-nav">
                <a href="admin_view.php">Usuários</a>
                <a href="admin_stats_view.php" class="active">Estatísticas</a>
                <a href="admin_log_view.php">Log De Atividades</a>
            </nav>
        </div>

        <div class="admin-stats-main-content">
            <div class="admin-stats-top-row">
                <div class="admin-stats-card">
                    <p>Usuários Cadastrados</p>
                    <div id="userCount" class="admin-stats-big-number">0</div>
                </div>
                <div class="admin-stats-card">
                    <p>Notas/Lembretes Criados</p>
                    <div id="notesCount" class="admin-stats-big-number">0</div>
                </div>
            </div>
            
            <div class="admin-stats-card admin-stats-full-width">
                <div class="chart-container">
                    <div class="chart-title">Usuários Novos - Últimos 6 Meses</div>
                    <div class="bar-chart-wrapper">
                        <svg id="barChart" viewBox="0 0 800 400"></svg>
                    </div>
                </div>
            </div>
            
            <div class="admin-stats-card admin-stats-full-width">
                <div class="chart-container">
                    <div class="chart-title">Tipos De Anotação Mais Usados</div>
                    <div class="pie-chart-wrapper">
                        <svg id="pieChart" viewBox="0 0 400 400"></svg>
                        <div id="chartLegend" class="chart-legend"></div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <?php include '../partials/footer.php'; ?>
    </footer>
    <script src="../../public/Javascript/graficos.js"></script>
    <script src="../../public/Javascript/stats_admin.js"></script>

</body>

</html>