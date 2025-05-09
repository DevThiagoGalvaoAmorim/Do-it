<?php
session_start();
require_once __DIR__ .'/conexao.php';


$action = $_POST['action'] ?? null;

try {
    if ($action === 'create') {
        $id = $_POST['id'] ?? '';
        $nome = $_POST['nome'] ?? '';
        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';
    
        if ($id_usuario) {
            // Inserir a nota com o ID do usuário
            $stmt = $pdo->prepare("INSERT INTO usuarios (id, nome, email, senha) VALUES (:id, :nome, :email, :senha)");
            $stmt->execute([
                ':id' => $id,
                ':nome' => $nome,
                ':email' => $email,
                ':senha' => $senha
            ]);
    
            echo json_encode(['success' => true, 'message' => 'Nota criada com sucesso!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'ID do usuário não fornecido.']);
        }
    } elseif ($action === 'read') {
        // Ler todas as notas
        $stmt = $pdo->query("SELECT * FROM usuarios");
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($usuarios);
    } elseif ($action === 'update') {
        // Atualizar uma nota
        $id = $_POST['id'] ?? null; // ID da nota a ser atualizada
        $nome = $_POST['nome'] ?? '';
        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';
    
        if ($id) {
            $stmt = $pdo->prepare("UPDATE usuarios SET nome = :nome, email = :email, senha = :senha WHERE id = :id");
            $stmt->execute([
                ':nome' => $nome,
                ':email' => $email,
                ':senha' => $senha,
                ':id' => $id
            ]);
    
            echo json_encode(['success' => true, 'message' => 'Dados atualizados com sucesso!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'ID da nota']);
        }
    } elseif ($action === 'delete') {
        // Deletar uma nota
        $id = $_POST['id'];

        if ($id) {
            $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = :id");
            $stmt->execute([':id' => 1]);

            echo json_encode(['success' => true, 'message' => 'Nota deletada com sucesso!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'titulo da nota não fornecido.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Ação inválida.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erro no banco de dados: ' . $e->getMessage()]);
}
?>