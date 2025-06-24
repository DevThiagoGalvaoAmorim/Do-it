<?php
session_start();
require_once __DIR__ . '/../conexao_db/conexao.php';
require_once __DIR__ . '/../models/usuarios_crud.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';
    $nome = $_POST['nome'] ?? '';

    if (empty($nome)) {
        if (!empty($email) && !empty($senha)) {
            // Verifica se o email e a senha são "admin"
            if ($email === 'admin' && $senha === 'admin') {
                // Define o usuário como admin
                $_SESSION['id'] = 1; // Você pode definir um ID fixo ou buscar no banco
                $_SESSION['nome'] = 'Admin';
                $_SESSION['email'] = $email;
                $_SESSION['tipo'] = 'admin';
                
                header('Location: ../views/admin/admin_view.php');
                exit;
            } else {
                // Busca o usuário no banco de dados
                $usuario = buscarUsuario($email, $senha);
                
                if ($usuario) {
                    $_SESSION['id'] = $usuario['id'];
                    $_SESSION['nome'] = $usuario['nome'];
                    $_SESSION['email'] = $usuario['email'];
                    $_SESSION['tipo'] = $usuario['tipo'] ?? 'user';
                    
                    // Redirecionar com base no tipo de usuário
                    if (isset($usuario['tipo']) && $usuario['tipo'] === 'admin') {
                        header('Location: ../views/admin/admin_view.php');
                    } else {
                        header('Location: ../views/main.php');
                    }
                    exit;
                } else {
                    echo '<script>alert("Email ou senha incorretos!");</script>';
                }
            }
        } else {
            echo '<script>alert("Preencha todos os campos!");</script>';
        }
    } else {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = $_POST['nome'] ?? '';
            $email = $_POST['email'] ?? '';
            $senha = $_POST['senha'] ?? '';

            // Aqui você pode processar os dados, como salvar no banco de dados
            if (!empty($nome) && !empty($email) && !empty($senha)) {
                $id = criarUsuario($nome, $email, $senha);

                if ($id) {
                    session_start(); // Inicia a sessão
                    $_SESSION['id'] = $id;
                    $_SESSION['nome'] = $nome;
                    $_SESSION['email'] = $email;
                    header('Location: ../views/main.php');
                    exit;
                }
            } else {
                echo '<script>alert("Preencha todos os campos!");</script>';
            }
        }
    }
}
