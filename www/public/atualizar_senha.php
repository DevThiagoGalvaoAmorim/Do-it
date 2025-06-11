<?php
$token = $_POST['token'];
$novaSenha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

$conn = new PDO("mysql:host=mysql_server;dbname=doitdb", "user", "password");

// Busca o e-mail
$stmt = $conn->prepare("SELECT email FROM password_resets WHERE token = ?");
$stmt->execute([$token]);
$email = $stmt->fetchColumn();

if (!$email) {
    echo "Token inválido.";
    exit;
}

// Atualiza a senha do usuário
$stmt = $conn->prepare("UPDATE usuarios SET senha = ? WHERE email = ?");
$stmt->execute([$novaSenha, $email]);

// Apaga o token
$conn->prepare("DELETE FROM password_resets WHERE token = ?")->execute([$token]);

echo "Senha atualizada com sucesso. <a href='login.php'>Faça login</a>";
?>
