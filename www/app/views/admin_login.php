<?php
require_once 'conexao_db/conexao.php';
require_once 'conexao_db/usuarios_crud.php';

session_start();

// Verificar se já está logado como admin
if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
    header('Location: admin.php');
    exit;
}

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    if (!empty($email) && !empty($senha)) {
        // Buscar usuário
        $usuario = buscarUsuario($email, $senha);
        
        // Verificar se é admin
        if ($usuario) {
            // Verificar se o usuário é um administrador
            $stmt = $pdo->prepare("SELECT tipo FROM usuarios WHERE id = :id");
            $stmt->execute([':id' => $usuario['id']]);
            $tipo = $stmt->fetchColumn();
            
            if ($tipo === 'admin') {
                $_SESSION['id'] = $usuario['id'];
                $_SESSION['nome'] = $usuario['nome'];
                $_SESSION['email'] = $usuario['email'];
                $_SESSION['admin'] = true;
                
                header('Location: admin.php');
                exit;
            } else {
                $erro = 'Você não tem permissão de administrador.';
            }
        } else {
            $erro = 'Email ou senha incorretos!';
        }
    } else {
        $erro = 'Preencha todos os campos!';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <title>Login Administrador - Do it</title>
    <style>
        .erro {
            color: red;
            margin-bottom: 15px;
            text-align: center;
        }
    </style>
</head>
<body>
    <form class="container" method="POST" action="">
        <div class="content-login">
            <img src="./imagens/logo_preta.png" alt="polvo-user">
            <h2>Login Administrador</h2>
            
            <?php if (!empty($erro)): ?>
                <div class="erro"><?php echo $erro; ?></div>
            <?php endif; ?>
            
            <input type="text" placeholder="Email" class="email" name="email">
            <input type="password" placeholder="Senha" class="lock" name="senha">
            
            <button class="btn-login" type="submit">Entrar</button>
            <a href="login.php" class="voltar">Voltar para login de usuário</a>
        </div>
    </form>
</body>
</html>