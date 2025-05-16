<?php
// Verificar se a sessão já está ativa antes de iniciá-la
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ .'/conexao.php';

function criarUsuario($nome, $email, $senha) {
    global $pdo;
    try{
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = :email");
        $stmt->execute([':email' => $email]);
        if ($stmt->rowCount() > 0) {
            throw new Exception("E-mail já cadastrado.");
        }

        $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)");
        $stmt->execute([
            ':nome' => $nome,
            ':email' => $email,
            ':senha' => $senha
        ]);
        return $pdo->lastInsertId();
    } catch (Exception $e) {
        return false;       
    }
}

function buscarUsuario($email, $senha) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT id, nome, email, tipo FROM usuarios WHERE email = :email AND senha = :senha");
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
        $stmt = $pdo->query("SELECT * FROM usuarios");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        return false;
        throw new Exception("Erro ao listar usuários: " . $e->getMessage());
    }
}

function atualizarUsuario($id, $nome, $email, $senha) {
    global $pdo;

    try {
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = :email AND id != :id");
        $stmt->execute([':email' => $email, ':id' => $id]);
        if ($stmt->rowCount() > 0) {
            throw new Exception("E-mail já cadastrado.");
        }

        $stmt = $pdo->prepare("UPDATE usuarios SET nome = :nome, email = :email, senha = :senha WHERE id = :id");
        $stmt->execute([
            ':nome' => $nome,
            ':email' => $email,
            ':senha' => $senha,
            ':id' => $id
        ]);
        return true;

    } catch (Exception $e) {
        return false;
        throw new Exception("Erro ao atualizar usuário: " . $e->getMessage());
    }
    
}

function deletarUsuario($id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE id = :id");
        $stmt->execute([':id' => $id]);
        if ($stmt->rowCount() == 0) {
            return false;
            throw new Exception("Usuário não encontrado.");
        }
        
        $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return true;
    } catch (Exception $e) {
        return false;
        throw new Exception("Erro ao deletar usuário: " . $e->getMessage());
    }
}

function obterUsuarioPorId($id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT id, nome, email, tipo FROM usuarios WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        return false;
    }
}
