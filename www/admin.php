<?php
require_once 'safe_page.php';
require_once 'conexao_db/usuarios_crud.php';
?>

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
    <header>
        <section class="search">
            <div class="header-content">
                <div class="header-logo">
                    <img src="imagens/logo_branca.png" alt="Do it Logo">
                </div>
                <h1>Administrador</h1>
                <div class="header-actions">
                    <a href="index.php" class="sair-btn">Sair</a>
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
            <input type="text" class="search-box" placeholder="Buscar...">
            
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
                    // Buscar usuários reais do banco de dados
                    $usuarios = listarUsuarios();
                    
                    if ($usuarios) {
                        foreach ($usuarios as $user) {
                            echo "<tr>
                                <td>{$user['nome']}</td>
                                <td>{$user['email']}</td>
                                <td>
                                    <button class='action-btn edit-btn' data-id='{$user['id']}'>✏️</button>
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
</body>
</html>