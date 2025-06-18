<?php
session_start();
require_once __DIR__ . '/../controllers/safe_page.php';
require_once __DIR__ . '/../models/usuarios_crud.php';
require_once __DIR__ . '/../controllers/processar_perfil.php';
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Dados</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <!-- <link rel="stylesheet" href="../public/css/style.css"> -->
    <link rel="stylesheet" href="../public/css/perfil.css">
</head>

<body>
    <header>
        <section class="search">

            <li>
                <a href="main.php">
                    <img src="../public/imagens/logo_branca.png" alt="Polvo escrevendo" class="logo">
                </a>
            </li>

        </section>
    </header>

    <div class="layout-container">
        <?php
        session_start();
        //corrigir verificação posteriormente
        if ($_SESSION['email'] == 'admin@mail') {
            include './partials/sidebarAdmin.php';
        } else {
            include './partials/sidebar.php';
        }
        ?>
        <section class="background">

            <div class="main-container">

                <div class="profile-container">
                    <div class="background-perfil"></div>
                    <div class="profile-pic-container">
                        <img src="../public/imagens/astronauta-user.png" alt="Foto de perfil" class="profile-pic">
                    </div>
                    <div class="profile-content">
                        <div class="profile-header">
                            <h1><?= htmlspecialchars($_SESSION['nome'] ?? 'Seu Nome Aqui') ?></h1>
                            <p><?= htmlspecialchars($_SESSION['email'] ?? 'email@exemplo.com') ?></p>
                        </div>
                        <div class="stats-row">
                            <p><strong>Notas:</strong> <?= $qtdNotas ?></p>
                            <p><strong>Entrou em
                                    <?= $dataCriacao ? htmlspecialchars($dataCriacao) : 'Data desconhecida' ?></strong>
                            </p>
                        </div>
                    </div>
                </div>

                <div>
                    <h2 class="edit-title">Editar Dados</h2>
                    <form class="edit-form" method="post" action="../controllers/processar_perfil.php">
                        <input type="hidden" name="acao" value="salvar">
                        <div class="form-group">
                            <label for="name">Nome:</label>
                            <input type="text" id="name" name="nome"
                                value="<?= htmlspecialchars($usuario['nome'] ?? '') ?>" placeholder="Digite seu nome">
                        </div>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email"
                                value="<?= htmlspecialchars($usuario['email'] ?? '') ?>"
                                placeholder="email@exemplo.com">
                        </div>
                        <div class="form-group">
                            <label for="password">Senha:</label>
                            <input type="password" id="password" name="senha" placeholder="Nova Senha">
                        </div>
                        <div class="form-group">
                            <!-- <label for="confirm-password">Confirme a Senha</label> -->
                            <input type="password" id="confirm-password" placeholder="Confirme a Senha">
                        </div>
                        <button type="submit" class="save-btn">
                            <img src="../public/imagens/icones/salvar-perfil.png" alt="Salvar"
                                style="width: 24px; height: 24px; vertical-align: middle;">
                            Salvar
                        </button>
                    </form>
                </div>
                <div class="delete-section">
                    <h3 style="color: #e57373;">Deletar Conta</h3>
                    <p style="color: #ccc;">Esta ação é irreversível. Tem certeza que deseja excluir sua conta?</p>
                    <form method="POST" action="../controllers/processar_perfil.php"
                        onsubmit="return confirm('Tem certeza que deseja deletar sua conta? Esta ação não pode ser desfeita.');">
                        <input type="hidden" name="acao" value="deletar">
                        <button type="submit"
                            style="background-color: #e57373; color: white; border: none; padding: 12px 32px; border-radius: 6px; font-size: 1.1rem; font-weight: 500; cursor: pointer;">
                            Deletar Conta
                        </button>
                    </form>
                </div>
            </div>
        </section>
    </div>


    <footer>
        <?php include './partials/footer.php'; ?>
    </footer>

    <script src="../public/javascript/perfil.js"></script>
</body>

</html>