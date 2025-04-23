<?php
require_once __DIR__ .'/conexao.php';

$action = $_POST['action'] ?? null;

try {
    if ($action === 'create') {
        $titulo = $_POST['titulo'] ?? '';
        $descricao = $_POST['descricao'] ?? '';
        $pasta = $_POST['pasta'] ?? '';
        $id_usuario = $_POST['id_usuario'] ?? 1; // Exemplo: ID do usuário fixo

        $stmt = $pdo->prepare("INSERT INTO notas (titulo, descricao, pasta, id_usuario, tipo) VALUES (:titulo, :descricao, :pasta, :id_usuario, 'Anotação')");
        $stmt->execute([
            ':titulo' => $titulo,
            ':descricao' => $descricao,
            ':pasta' => $pasta,
            ':id_usuario' => $id_usuario
        ]);

        echo json_encode(['success' => true, 'message' => 'Nota criada com sucesso!']);
    } elseif ($action === 'read') {
        // Ler todas as notas
        $stmt = $pdo->query("SELECT * FROM notas");
        $notas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($notas);
    } elseif ($action === 'update') {
        // Atualizar uma nota
        $id = $_POST['id'] ?? null;
        $titulo = $_POST['titulo'] ?? '';
        $descricao = $_POST['descricao'] ?? '';
        $pasta = $_POST['pasta'] ?? '';

        if ($id) {
            $stmt = $pdo->prepare("UPDATE notas SET titulo = :titulo, descricao = :descricao, pasta = :pasta WHERE id = :id");
            $stmt->execute([
                ':titulo' => $titulo,
                ':descricao' => $descricao,
                ':pasta' => $pasta,
                ':id' => $id
            ]);

            echo json_encode(['success' => true, 'message' => 'Nota atualizada com sucesso!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'ID da nota não fornecido.']);
        }
    } elseif ($action === 'delete') {
        // Deletar uma nota
        $id = $_POST['id'] ?? null;

        if ($id) {
            $stmt = $pdo->prepare("DELETE FROM notas WHERE id = :id");
            $stmt->execute([':id' => $id]);

            echo json_encode(['success' => true, 'message' => 'Nota deletada com sucesso!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'ID da nota não fornecido.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Ação inválida.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erro no banco de dados: ' . $e->getMessage()]);
}
?>