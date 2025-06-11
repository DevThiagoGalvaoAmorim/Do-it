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
    echo "Deu certo ao atualizar a senha! Faça o login em <a href='/views/auth/login.php'>Faça login</a>";
}else{
    echo "Erro ao atualizar senha! Tente novamente.";
}

apagarToken($token);

?>
