<?php
session_start();
require_once __DIR__ . '/../conexao_db/conexao.php';
require_once __DIR__ . '/../models/usuarios_crud.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';
    $nome = $_POST['nome'] ?? '';

    // Login 
    if (empty($nome)) {
        if (!empty($email) && !empty($senha)) {
            // Verificação explícita para admin primeiro 
            $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email AND senha = :senha AND tipo = 'admin'");
            $stmt->execute([':email' => $email, ':senha' => $senha]);
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($admin) {
                $_SESSION['id'] = $admin['id'];
                $_SESSION['nome'] = $admin['nome'];
                $_SESSION['email'] = $admin['email'];
                $_SESSION['tipo'] = $admin['tipo'];
                header('Location: ../views/admin/admin_view.php');
                exit;
            }

            // Se não for admin, busca usuário normal
            $usuario = buscarUsuario($email, $senha);
            
            if ($usuario) {
                $_SESSION['id'] = $usuario['id'];
                $_SESSION['nome'] = $usuario['nome'];
                $_SESSION['email'] = $usuario['email'];
                $_SESSION['tipo'] = $usuario['tipo'] ?? 'user';
                
                header('Location: ../views/main.php');
                exit;
            } else {
                echo '<script>alert("Email ou senha incorretos!");</script>';
            }
        } else {
            echo '<script>alert("Preencha todos os campos!");</script>';
        }
    } 
    // Cadastro (quando nome está presente)
    else {
        if (!empty($nome) && !empty($email) && !empty($senha)) {
            $id = criarUsuario($nome, $email, $senha);
            
            if ($id) {
                $_SESSION['id'] = $id;
                $_SESSION['nome'] = $nome;
                $_SESSION['email'] = $email;
                $_SESSION['tipo'] = 'user'; // Definindo tipo padrão
                header('Location: ../views/main.php');
                exit;
            }
        } else {
            echo '<script>alert("Preencha todos os campos!");</script>';
        }
    }
}
