<?php
session_start();

// Verifica se a sessão está ativa e se o usuário está autenticado
if (!isset($_SESSION['id']) || !isset($_SESSION['nome']) || !isset($_SESSION['email'])) {
    // Redireciona para a página de login
    header('Location: ../views/auth/login.php');
    exit;
}
?>