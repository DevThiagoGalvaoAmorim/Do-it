<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/style.css">
    <style>
        #editModal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 8px;
            min-width: 400px;
            max-width: 500px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .modal-content h3 {
            margin-top: 0;
            margin-bottom: 20px;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }

        .form-group input {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
        }

        .form-group input:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
        }

        .btn-group {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.2s;
        }

        .btn-primary {
            background-color: #007bff;
            color: white;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #545b62;
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
                <a href="#" class="active">Usuários</a>
                <a href="./admin/admin_stats_view.php">Estatísticas</a>
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
                    if (isset($usuarios) && $usuarios) {
                        foreach ($usuarios as $user) {
                            echo "<tr>
                                <td>" . htmlspecialchars($user['nome']) . "</td>
                                <td>" . htmlspecialchars($user['email']) . "</td>
                                <td>
                                    <button class='action-btn edit-btn' data-id='" . htmlspecialchars($user['id']) . "' data-nome='" . htmlspecialchars($user['nome']) . "' data-email='" . htmlspecialchars($user['email']) . "'>✏️</button>
                                    <button class='action-btn delete-btn' data-id='" . htmlspecialchars($user['id']) . "'>🗑️</button>
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

    <script src="../public/javascript/admin-user.js"></script>

    <footer>
        <?php include 'footer.php'; ?>
    </footer>
</body>

</html>