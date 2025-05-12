<?php
session_start();
require_once 'safe_page.php';
require_once 'conexao_db/conexao.php';

// Verificar se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: index.php");
    exit();
}

// Verificar se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_usuario = $_SESSION['id_usuario'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $confirmar_senha = $_POST['confirmar_senha'];
    
    // Validar os dados
    $erro = false;
    $mensagem = "";
    
    // Verificar se as senhas coincidem
    if (!empty($senha) && $senha !== $confirmar_senha) {
        $erro = true;
        $mensagem = "As senhas não coincidem.";
    }
    
    // Se não houver erros, atualizar os dados
    if (!$erro) {
        try {
            // Iniciar a consulta SQL
            $sql = "UPDATE usuarios SET nome = :nome, email = :email";
            $params = [
                ':nome' => $nome,
                ':email' => $email,
                ':id' => $id_usuario
            ];
            
            // Adicionar senha à consulta se foi fornecida
            if (!empty($senha)) {
                $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
                $sql .= ", senha = :senha";
                $params[':senha'] = $senha_hash;
            }
            
            // Finalizar a consulta
            $sql .= " WHERE id = :id";
            
            // Executar a consulta
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            
            // Atualizar os dados da sessão
            $_SESSION['nome'] = $nome;
            $_SESSION['email'] = $email;
            
            // Redirecionar com mensagem de sucesso
            $_SESSION['mensagem'] = "Perfil atualizado com sucesso!";
            header("Location: main.php");
            exit();
        } catch (PDOException $e) {
            $mensagem = "Erro ao atualizar o perfil: " . $e->getMessage();
        }
    }
    
    // Se chegou aqui, houve um erro
    $_SESSION['erro'] = $mensagem;
    header("Location: main.php");
    exit();
}
?>