<?php
session_start();

// Verificar se o usuário está logado e é administrador
if (!isset($_SESSION['id']) || !isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('Location: admin_login.php');
    exit;
}
?>