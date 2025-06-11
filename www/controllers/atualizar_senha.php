<?php
require_once  '../views/auth/warning2.php';
require_once __DIR__ . '/../models/account_config.php';

$token = $_POST['token'];
$novaSenha = $_POST['senha'];

$email = buscarToken($token);

if (!$email) {
    echo "Token inválido.";
    exit;
}

echo $novaSenha;
echo "\n".$token;

if(atualizarSenha($novaSenha, $email)){
    tela_de_mensagem("Deu certo ao atualizar a senha!");
}else{
    tela_de_mensagem("Erro ao atualizar senha! Tente novamente.");
}

apagarToken($token);

?>
