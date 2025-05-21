<?php
session_start();

// Verificar se o usuário está logado e é administrador
if (!isset($_SESSION['id']) || !isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    header('Location: admin_login.php');
    exit;
}
?>