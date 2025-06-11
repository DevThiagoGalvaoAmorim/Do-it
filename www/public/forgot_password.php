<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Senha</title>
</head>
<body>
    <h2>Recuperar Senha</h2>
    <form action="enviar_email.php" method="post">
        <label for="email">Digite seu e-mail:</label><br>
        <input type="email" name="email" required>
        <br><br>
        <button type="submit">Enviar link de recuperação</button>
    </form>
</body>
</html>
