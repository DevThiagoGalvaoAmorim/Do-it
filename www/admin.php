<?php
require_once 'admin_safe_page.php';
require_once 'conexao_db/usuarios_crud.php';

// Processar exclusão de usuário
if (isset($_POST['action']) && $_POST['action'] === 'delete' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $resultado = deletarUsuario($id);
    
    if ($resultado) {
        $mensagem = "Usuário excluído com sucesso!";
    } else {
        $erro = "Erro ao excluir usuário.";
    }
}

// Processar atualização de usuário
if (isset($_POST['action']) && $_POST['action'] === 'update') {
    $id = $_POST['id'] ?? '';
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';
    
    if (!empty($id) && !empty($nome) && !empty($email)) {
        $resultado = atualizarUsuario($id, $nome, $email, $senha);
        
        if ($resultado) {
            $mensagem = "Usuário atualizado com sucesso!";
        } else {
            $erro = "Erro ao atualizar usuário.";
        }
    } else {
        $erro = "Preencha todos os campos obrigatórios!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .mensagem {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        
        .erro {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        
        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            width: 400px;
            max-width: 90%;
        }
        
        .modal-content h3 {
            margin-top: 0;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        
        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .btn-group {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        
        .btn {
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .btn-primary {
            background-color: #007bff;
            color: white;
        }
        
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
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
                    // Buscar usuários reais do banco de dados
                    $usuarios = listarUsuarios();
                    
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
        // Função para abrir o modal de edição
        function openEditModal(id, nome, email) {
            document.getElementById('editId').value = id;
            document.getElementById('editNome').value = nome;
            document.getElementById('editEmail').value = email;
            document.getElementById('editSenha').value = '';
            document.getElementById('editModal').style.display = 'flex';
        }
        
        // Função para fechar o modal
        function closeModal() {
            document.getElementById('editModal').style.display = 'none';
        }
        
        // Adicionar evento aos botões de edição
        document.addEventListener('DOMContentLoaded', function() {
            // Botões de edição
            const editButtons = document.querySelectorAll('.edit-btn');
            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const nome = this.getAttribute('data-nome');
                    const email = this.getAttribute('data-email');
                    openEditModal(id, nome, email);
                });
            });
            
            // Botões de exclusão
            const deleteButtons = document.querySelectorAll('.delete-btn');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    if (confirm('Tem certeza que deseja excluir este usuário?')) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '';
                        
                        const actionInput = document.createElement('input');
                        actionInput.type = 'hidden';
                        actionInput.name = 'action';
                        actionInput.value = 'delete';
                        
                        const idInput = document.createElement('input');
                        idInput.type = 'hidden';
                        idInput.name = 'id';
                        idInput.value = id;
                        
                        form.appendChild(actionInput);
                        form.appendChild(idInput);
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            });
            
            // Funcionalidade de busca
            const searchInput = document.getElementById('searchInput');
            searchInput.addEventListener('keyup', function() {
                const searchTerm = this.value.toLowerCase();
                const rows = document.querySelectorAll('.users-table tbody tr');
                
                rows.forEach(row => {
                    const nome = row.cells[0].textContent.toLowerCase();
                    const email = row.cells[1].textContent.toLowerCase();
                    
                    if (nome.includes(searchTerm) || email.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });
        
        // Fechar o modal quando clicar fora dele
        window.onclick = function(event) {
            const modal = document.getElementById('editModal');
            if (event.target === modal) {
                closeModal();
            }
        }
    </script>
</body>
</html>