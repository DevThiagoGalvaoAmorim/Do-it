<!DOCTYPE html>
<html lang="pt-br">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>


<body>
    <header class="admin-stats-header">
        <div class="admin-stats-header-content">
            <div class="admin-stats-header-logo">
                <img src="imagens/logo_branca.png" alt="Do it Logo">
            </div>
            <h1>Administrador</h1>
            <div class="admin-stats-header-actions">
                <a href="index.php" class="admin-stats-sair-btn">Sair</a>
            </div>
        </div>
    </header>


    <main class="admin-stats-main">
        <div class="admin-stats-sidebar">
            <nav class="admin-stats-nav">
                <a href="admin.php" class="active">Usuários</a>
                <a href="admin_stats.php">Estatísticas</a>
            </nav>
        </div>


        <div class="admin-stats-main-content">
            <div class="admin-stats-card">
                <p>Usuários Cadastrados</p>
                <div class="admin-stats-big-number"><?php echo $userCount; ?></div>
            </div>
            <div class="admin-stats-card">
                <p>Notas Criadas</p>
                <div class="admin-stats-big-number"><?php echo $notesCreated; ?></div>
            </div>
            <div class="admin-stats-card admin-stats-full-width">
                <p>Número de Usuários Novos Por Mês</p>
                <div class="admin-stats-big-number"><?php echo $newUsersPerMonth; ?></div>
            </div>
            <div class="admin-stats-card admin-stats-full-width">
                <p><strong>Tipos De Notas Mais Usadas</strong></p>
                <div class="admin-stats-pie-chart-container">
                    <div class="admin-stats-pie-chart"></div>
                    <div class="admin-stats-legend">
                        <?php foreach ($noteTypesUsage as $type => $count): ?>
                            <div><span class="admin-stats-color-box admin-stats-<?php echo strtolower($type); ?>"></span> <?php echo $type; ?> (<?php echo $count; ?>)</div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>


    <footer>
        <?php include 'footer.php'; ?>
    </footer>


</body>


</html>