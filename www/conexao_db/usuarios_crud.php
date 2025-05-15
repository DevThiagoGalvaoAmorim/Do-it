<?php
session_start();
require_once __DIR__ .'/conexao.php';

function criarUsuario($nome, $email, $senha, $tipo = 'user') {
    global $pdo;
    try{
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = :email");
        $stmt->execute([':email' => $email]);
        if ($stmt->rowCount() > 0) {
            throw new Exception("E-mail já cadastrado.");
        }

        $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha, tipo) VALUES (:nome, :email, :senha, :tipo)");
        $stmt->execute([
            ':nome' => $nome,
            ':email' => $email,
            ':senha' => $senha,
            ':tipo' => $tipo
        ]);
        return $pdo->lastInsertId();
    } catch (Exception $e) {
        return false;       
    }
}

function buscarUsuario($email, $senha) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT id, nome, email FROM usuarios WHERE email = :email AND senha = :senha");
        $stmt->execute([
            ':email' => $email,
            ':senha' => $senha
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        return false;
    }
}

function listarUsuarios() {
    global $pdo;
    try {
        $stmt = $pdo->query("SELECT id, nome, email, tipo FROM usuarios ORDER BY nome");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        return false;
    }
}

function buscarUsuarioPorId($id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT id, nome, email, tipo FROM usuarios WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        return false;
    }
}

function atualizarUsuario($id, $nome, $email, $senha = null) {
    global $pdo;
    try {
        // Verificar se o email já existe para outro usuário
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = :email AND id != :id");
        $stmt->execute([
            ':email' => $email,
            ':id' => $id
        ]);
        if ($stmt->rowCount() > 0) {
            throw new Exception("E-mail já está sendo usado por outro usuário.");
        }
        
        // Se a senha foi fornecida, atualiza com a senha
        if (!empty($senha)) {
            $stmt = $pdo->prepare("UPDATE usuarios SET nome = :nome, email = :email, senha = :senha WHERE id = :id");
            $stmt->execute([
                ':nome' => $nome,
                ':email' => $email,
                ':senha' => $senha,
                ':id' => $id
            ]);
        } else {
            // Se a senha não foi fornecida, mantém a senha atual
            $stmt = $pdo->prepare("UPDATE usuarios SET nome = :nome, email = :email WHERE id = :id");
            $stmt->execute([
                ':nome' => $nome,
                ':email' => $email,
                ':id' => $id
            ]);
        }
        
        return $stmt->rowCount() > 0;
    } catch (Exception $e) {
        return false;
    }
}

function deletarUsuario($id) {
    global $pdo;
    try {
        // Proteger contra exclusão do próprio usuário logado
        if (isset($_SESSION['id']) && $_SESSION['id'] == $id) {
            throw new Exception("Você não pode excluir seu próprio usuário.");
        }
        
        $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->rowCount() > 0;
    } catch (Exception $e) {
        return false;
    }
}
?>
