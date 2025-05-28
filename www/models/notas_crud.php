<?php
session_start();
require_once __DIR__ .'../../conexao_db/conexao.php';

$action = $_POST['action'] ?? null;

try {
    if ($action === 'create') {
        $titulo = $_POST['titulo'] ?? '';
        $descricao = $_POST['descricao'] ?? '';
        $pasta = $_POST['pasta'] ?? '';
        $tipo = $_POST['tipo'] ?? 'Checklist';
        $id_usuario = $_SESSION['id']; 
    
        if ($id_usuario) {
            // Inserir a nota com o ID do usuário
            $stmt = $pdo->prepare("INSERT INTO notas (titulo, descricao, pasta, tipo, id_usuario) VALUES (:titulo, :descricao, :pasta, :tipo, :id_usuario)");
            $stmt->execute([
                ':titulo' => $titulo,
                ':descricao' => $descricao,
                ':pasta' => $pasta,
                ':tipo' => $tipo,
                ':id_usuario' => $id_usuario
            ]);
    
            echo json_encode(['success' => true, 'message' => 'Nota criada com sucesso!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'ID do usuário não fornecido.']);
        }
    } elseif ($action === 'read') {
        // Ler todas as notas do usuário
        $id_usuario = $_SESSION['id'];
        if($id_usuario){
            $stmt = $pdo->prepare("SELECT * FROM notas WHERE id_usuario = :id_usuario");
            $stmt->execute([':id_usuario' => $id_usuario]);
            $notas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo json_encode(['success' => false, 'message' => 'ID do usuário não encontrado']);
        }
        echo json_encode($notas);
    } elseif ($action === 'update') {
        // Atualizar uma nota
        $id = $_POST['id'] ?? null; // ID da nota a ser atualizada
        $id_usuario = $_SESSION['id'];
        $titulo = $_POST['titulo'] ?? '';
        $descricao = $_POST['descricao'] ?? '';
        $pasta = $_POST['pasta'] ?? '';
    
        if ($id && $id_usuario) {
            $stmt = $pdo->prepare("UPDATE notas SET titulo = :titulo, descricao = :descricao, pasta = :pasta WHERE id = :id AND id_usuario = :id_usuario");
            $stmt->execute([
                ':titulo' => $titulo,
                ':descricao' => $descricao,
                ':pasta' => $pasta,
                ':id' => $id,
                ':id_usuario' => $id_usuario
            ]);
    
            echo json_encode(['success' => true, 'message' => 'Nota atualizada com sucesso!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'ID da nota ou ID do usuário não fornecido.']);
        }
    } elseif ($action === 'delete') {
        // Deletar uma nota
        $id = $_POST['id'];
        $id_usuario = $_SESSION['id'];

        if ($id && $id_usuario) {
            $stmt = $pdo->prepare("DELETE FROM notas WHERE id = :id AND id_usuario = :id_usuario");
            $stmt->execute([':id' => $id, ':id_usuario' => $id_usuario]);

            echo json_encode(['success' => true, 'message' => 'Nota deletada com sucesso!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'id da nota não fornecido.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Ação inválida.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erro no banco de dados: ' . $e->getMessage()]);
}
?>