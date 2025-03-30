<?php
session_start();
require_once 'conexao.php';

$errorMessage = null; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM usuarios WHERE email = :email AND senha = :senha";

    try {
        $buscar = $pdo->prepare($sql);
        $buscar->execute(['email' => $email, 'senha' => $senha]);

        $resultado = $buscar->fetch(PDO::FETCH_ASSOC);

        if ($resultado) {
            $_SESSION['nome'] = $resultado['nome'];;
            $_SESSION['email'] = $email;
            $_SESSION['usuario_id'] = $resultado['usuario_id'];
            header("Location: main.php");
            exit();
        } else {
           echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                alert('Erro ao fazer login. E-mail ou senha incorretos! Tente novamente.');
            }); </script>";
        }
    } catch (PDOException $e) {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                alert('Erro ao buscar usuário: " . $e->getMessage() . "');
            }); </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Do !t</title>
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
</head>

<body>

    <div class="container">
        <div class="left-panel">
            <div class="welcome">BEM VINDO</div>
            <div class="login-title">Novo Login</div>
            <button class="create-button"><a href="cadastrar.php">Criar conta</a></button>
        </div>

        <div class="right-panel">
            <h1 class="title">Do !t</h1>
            <div class="profile">
                <img src="/image/Login_DO_IT.png" alt="not found">
            </div>


            <form action="index.php" method="POST">

                <div class="input-group">
                    <input type="text" name="email" class="input-control" placeholder="Usuário" required>
                </div>

                <div class="input-group">
                    <input type="password" name="senha" class="input-control" placeholder="Senha" required>
                </div>

                <button type="submit" class="login-button">Entrar</button>
            </form>
        </div>
    </div>
    <script src="script.js"></script>
</body>

</html>