<?php
$token = $_GET['token'] ?? '';
if (!$token) {
    echo "Token inválido.";
    exit;
}

$conn = new PDO("mysql:host=mysql_server;dbname=doitdb", "user", "password");
$stmt = $conn->prepare("SELECT email FROM password_resets WHERE token = ?");
$stmt->execute([$token]);
$email = $stmt->fetchColumn();

if (!$email) {
    echo "Token inválido ou já usado.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Nova Senha</title>
</head>
<body>
    <h2>Digite sua nova senha</h2>
    <form action="atualizar_senha.php" method="post">
        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
        <label>Nova senha:</label><br>
        <input type="password" name="senha" required><br><br>
        <button type="submit">Salvar nova senha</button>
    </form>
</body>
</html>
