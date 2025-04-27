<?php
session_start();
require_once __DIR__ .'/conexao_db/conexao.php';


$action = $_POST['action'] ?? null;

try {
    if ($action === 'create') {
        $titulo = $_POST['titulo'] ?? '';
        $descricao = $_POST['descricao'] ?? '';
        $pasta = $_POST['pasta'] ?? '';
        $tipo = $_POST['tipo'] ?? 'Checklist';
        $id_usuario = $_POST['id_usuario'] ?? 1; 
    
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
        // Ler todas as notas
        $stmt = $pdo->query("SELECT * FROM notas");
        $notas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($notas);
    } elseif ($action === 'update') {
        // Atualizar uma nota
        $id = $_POST['id'] ?? null; // ID da nota a ser atualizada
        $id_usuario = $_POST['id_usuario'] ?? 1;
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
        $titulo = $_POST['titulo'];

        if ($titulo) {
            $stmt = $pdo->prepare("DELETE FROM notas WHERE titulo = :titulo AND id_usuario = :id");
            $stmt->execute([':titulo' => $titulo, ':id' => 1]);

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