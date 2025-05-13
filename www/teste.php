<?php

require_once 'conexao_db/conexao.php';
require_once 'conexao_db/usuarios_crud.php';

$nome = $_POST['nome'];
$email = $_POST['email'];
$senha = $_POST['senha'];

$insercao = criarUsuario($nome, $email, $senha);

if ($insercao){
        
    session_start(); // Inicia a sessão
    $_SESSION['id'] = $insercao['id']  
    $_SESSION['nome'] = $insercao['nome'];
    $_SESSION['email'] = $insercao['email'];
    header('Location: main.php'); // Redireciona para main.php
    exit;
    
}else{
    echo '<script>alert("Não foi possível criar o usuário!");</script>';
}

?>


