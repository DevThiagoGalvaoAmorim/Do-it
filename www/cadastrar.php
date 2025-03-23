<?php 
require_once 'conexao.php';

if($_SERVER["REQUEST_METHOD"] == "POST") { 
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sql = "INSERT INTO usuarios(nome, email, senha) VALUES (:nome, :email, :senha)";

    try {
        $criar = $pdo->prepare($sql);
        $criar->execute(['nome' => $nome, 'email' => $email, 'senha' => $senha]);
        
        header("Location: main.php");
        exit();
        
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                alert('Erro ao cadastrar usuário: E-mail já cadastrado!');
            }); </script>";
        } else {
            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                alert('Erro ao cadastrar usuário: " . addslashes($e->getMessage()) . "');
            }); </script>";
        }
    
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Do !t</title>
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="style_cadastrar.css">
</head>

<body>
    <div class="container">
        <form action="#" method="POST">
            <div class="profile"> <img src="/image/Login_DO_IT.png" alt="not found"></div>

            <div class="input-group">
                <input class="input-control" name="nome" type="text" required placeholder="Nome">
            </div>

            <div class="input-group">
                <input class="input-control" name="email" type="text" required placeholder="Email">
            </div>

            <div class="input-group">
                <input class="input-control" name="senha" type="password" required placeholder="Senha">
            </div>

            <button class="button-sign" type="submit">Cadastrar-se</button>
        </form>
    </div>
    <script src="script_cadastrar.js"></script>
</body>

</html>