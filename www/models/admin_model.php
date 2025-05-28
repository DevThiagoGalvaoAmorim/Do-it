<?php
require_once __DIR__ . '/../conexao_db/conexao.php';

class AdminModel {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function listUsers() {
        $stmt = $this->pdo->query("SELECT * FROM usuarios");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteUser($id) {
        $stmt = $this->pdo->prepare("DELETE FROM usuarios WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function updateUser($id, $nome, $email, $senha = null) {
        if ($senha) {
            $stmt = $this->pdo->prepare("UPDATE usuarios SET nome = ?, email = ?, senha = ? WHERE id = ?");
            return $stmt->execute([$nome, $email, $senha, $id]);
        } else {
            $stmt = $this->pdo->prepare("UPDATE usuarios SET nome = ?, email = ? WHERE id = ?");
            return $stmt->execute([$nome, $email, $id]);
        }
    }

    public function buscarPorEmailSenha($email, $senha) {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE email = ? AND senha = ?");
        $stmt->execute([$email, $senha]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createUser($nome, $email, $senha, $tipo = 'user') {
        $stmt = $this->pdo->prepare("INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$nome, $email, $senha, $tipo]) ? $this->pdo->lastInsertId() : false;
    }
}