<?php
require_once __DIR__ . '/../conexao_db/conexao.php';


function salvarToken($email, $token) {
    global $pdo;
    try {
        $expires_at = date('Y-m-d H:i:s', strtotime('+5 minutes')); // expira em 5 minutos
        $stmt = $pdo->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
        $stmt->execute([$email, $token, $expires_at]);
        return true;
    } catch (Exception $e) {
        print($e);
        return false;
    }
}

function buscarToken($token){
    global $pdo;
    try{
        $stmt = $pdo->prepare("SELECT email FROM password_resets WHERE token = ?");
        $stmt->execute([$token]);
        $email = $stmt->fetchColumn();
        return $email;
    
    } catch (Exception $e) {
        
        return false;
    
    }
}

function atualizarSenha($novaSenha, $email){
    global $pdo;
    try{
        $stmt = $pdo->prepare("UPDATE usuarios SET senha = ? WHERE email = ?");
        $stmt->execute([$novaSenha, $email]);
        return true;
    } catch (Exception $e) {
        return false;
    }    

}

function apagarToken($token){
    global $pdo;
    try {
        $stmt = $pdo->prepare("DELETE FROM password_resets WHERE token = ?");
        $stmt->execute([$token]);
        return true;
    } catch (Exception $e) {
        return false;
    }
}
