<?php
session_start();
require_once __DIR__ . '/../conexao_db/conexao.php';

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

function atualizarUsuario($id, $nome, $email, $senha = null, $tipo = null) {
    global $pdo;
    $usuarioAtual = buscarUsuarioPorId($id);
    try {
        if (!empty($email) && $email != $usuarioAtual['email']) {
            $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = :email AND id != :id");
            $stmt->execute([
                ':email' => $email,
                ':id' => $id
            ]);
            if ($stmt->rowCount() > 0) {
                throw new Exception("E-mail já está sendo usado por outro usuário.");
            }
        }
        
        // Construir a query de atualização com base nos parâmetros fornecidos
        $campos = [];
        $params = [':id' => $id];
        
        if (!empty($nome)) {
            $campos[] = "nome = :nome";
            $params[':nome'] = $nome;
        }
        
        if (!empty($email)) {
            $campos[] = "email = :email";
            $params[':email'] = $email;
        }
        
        if (!empty($senha)) {
            $campos[] = "senha = :senha";
            $params[':senha'] = $senha;
        }
        
        if (!empty($tipo)) {
            $campos[] = "tipo = :tipo";
            $params[':tipo'] = $tipo;
        }
        
        if (empty($campos)) {
            return false;
        }
        
        $query = "UPDATE usuarios SET " . implode(", ", $campos) . " WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        $_SESSION['email'] = $email;
        
        return $stmt->rowCount() > 0;
    } catch (Exception $e) {
        return false;
    }
}

function deletarUsuario($id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->rowCount() > 0;
    } catch (Exception $e) {
        return false;
    }
}
?>
