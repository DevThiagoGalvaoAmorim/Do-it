<?php
session_start();
require_once 'conexao_db/conexao.php';
require_once 'conexao_db/usuarios_crud.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';
    $nome = $_POST['nome'] ?? '';

    if (!empty($nome)){
        
        if (!empty($email) && !empty($senha)) {
        $usuario = buscarUsuario($email, $senha);
        
        if ($usuario) {
            $_SESSION['id'] = $usuario['id'];
            $_SESSION['nome'] = $usuario['nome'];
            $_SESSION['email'] = $usuario['email'];
            $_SESSION['tipo'] = $usuario['tipo'] ?? 'user';
            
            // Redirecionar com base no tipo de usuário
            if (isset($usuario['tipo']) && $usuario['tipo'] === 'admin') {
                header('Location: admin.php');
            } else {
                header('Location: main.php');
            }
            exit;
        } else {
            echo '<script>alert("Email ou senha incorretos!");</script>';
        }
        } else {
            echo '<script>alert("Preencha todos os campos!");</script>';
        }

    }else {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nome = $_POST['nome'] ?? '';
        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';

        // Aqui você pode processar os dados, como salvar no banco de dados
        if (!empty($nome) && !empty($email) && !empty($senha)) {
            $id = criarUsuario($nome, $email, $senha);

            if ($id){
                session_start(); // Inicia a sessão
                $_SESSION['id'] = $id;
                $_SESSION['nome'] = $nome;
                $_SESSION['email'] = $email;
                header('Location: main.php');
                exit;
            }

            } else {
                echo '<script>alert("Preencha todos os campos!");</script>';
            }
        }
    }

    
}


