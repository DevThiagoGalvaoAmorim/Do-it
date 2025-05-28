<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        /* CSS styles here */
    </style>
</head>
<body>
    <header>
        <section class="search">
            <div class="header-content">
                <div class="header-logo">
                    <img src="imagens/logo_branca.png" alt="Do it Logo">
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
                <a href="#" class="active">Usuários</a>
                <a href="#">Estatísticas</a>
                <a href="#">Log De Atividades</a>
            </nav>
        </div>


        <div class="admin-content">
            <h2 class="page-title">Usuários</h2>


            <?php if (isset($mensagem)): ?>
                <div class="mensagem"><?php echo $mensagem; ?></div>
            <?php endif; ?>


            <?php if (isset($erro)): ?>
                <div class="erro"><?php echo $erro; ?></div>
            <?php endif; ?>


            <input type="text" class="search-box" placeholder="Buscar..." id="searchInput">


            <table class="users-table">
                <thead>
                    <tr>
                        <th>Nome de Usuário</th>
                        <th>E-mail</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($usuarios) {
                        foreach ($usuarios as $user) {
                            echo "<tr>
                                <td>{$user['nome']}</td>
                                <td>{$user['email']}</td>
                                <td>
                                    <button class='action-btn edit-btn' data-id='{$user['id']}' data-nome='{$user['nome']}' data-email='{$user['email']}'>✏️</button>
                                    <button class='action-btn delete-btn' data-id='{$user['id']}'>🗑️</button>
                                </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>Nenhum usuário encontrado</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </main>


    <!-- Modal de Edição -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <h3>Editar Usuário</h3>
            <form id="editForm" method="POST" action="">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id" id="editId">


                <div class="form-group">
                    <label for="editNome">Nome:</label>
                    <input type="text" id="editNome" name="nome" required>
                </div>


                <div class="form-group">
                    <label for="editEmail">Email:</label>
                    <input type="email" id="editEmail" name="email" required>
                </div>


                <div class="form-group">
                    <label for="editSenha">Nova Senha (deixe em branco para manter a atual):</label>
                    <input type="password" id="editSenha" name="senha">
                </div>


                <div class="btn-group">
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>


    <script>
        // JavaScript functions here
    </script>


</body>


<footer>
     <?php include 'footer.php'; ?>
</footer>
</html>