<?php
<<<<<<< HEAD
// Verificar se a sessão já está ativa antes de iniciá-la
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar se o usuário está logado e é administrador
if (!isset($_SESSION['id']) || !isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    // Adicionar um parâmetro para evitar redirecionamento infinito
    header('Location: admin_login.php?redirect=0');
=======
session_start();

// Verificar se o usuário está logado e é administrador
if (!isset($_SESSION['id']) || !isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    header('Location: admin_login.php');
>>>>>>> ace72197039c8ba0b59a3c63a1ec82d09ba39637
    exit;
}
?>