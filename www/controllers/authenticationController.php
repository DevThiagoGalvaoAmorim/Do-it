<?php
session_start();
require_once 'conexao_db/conexao.php';
require_once 'conexao_db/usuarios_crud.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

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
}
