<?php
require_once  '../views/auth/warning2.php';
require_once __DIR__ . '/../models/account_config.php';

$token = $_POST['token'];
$novaSenha = $_POST['senha'];

$email = "douglasalvesdacruz7@gmail.com";

if (!$email) {
    echo "Token inválido.";
    exit;
}

if(atualizarSenha($novaSenha, $email)){
    tela_de_mensagem("Deu certo ao atualizar a senha!");
}else{
    tela_de_mensagem("Erro ao atualizar senha! Tente novamente.");
}

?>
