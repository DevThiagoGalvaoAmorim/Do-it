<?php
session_start();
require_once __DIR__ . '../conexao_db/conexao.php';

header("Content-Type: application/json");

$action = $_POST['action'] ?? null;
$id_usuario = $_SESSION['id'] ?? null;

if (!$id_usuario) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
    exit;
}

try {
    if ($action === 'create') {
        $titulo = filter_input(INPUT_POST, 'titulo', FILTER_SANITIZE_SPECIAL_CHARS);
        $descricao = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_SPECIAL_CHARS);
        $data_hora = $_POST['data_hora'] ?? null;

        if (!empty($titulo) && !empty($data_hora)) {
            $stmt = $pdo->prepare("INSERT INTO lembrete (titulo, descricao, data_hora, id_usuario) VALUES (:titulo, :descricao, :data_hora, :id_usuario)");
            $stmt->execute([
                ':titulo' => $titulo,
                ':descricao' => $descricao,
                ':data_hora' => $data_hora,
                ':id_usuario' => $id_usuario
            ]);

            echo json_encode(['success' => true, 'message' => 'Lembrete criado com sucesso!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Título e data são obrigatórios!']);
        }
    } elseif ($action === 'read') {
        $stmt = $pdo->prepare("SELECT id, titulo, descricao, data_hora FROM lembrete WHERE id_usuario = :id_usuario ORDER BY data_hora DESC");
        $stmt->execute([':id_usuario' => $id_usuario]);
        $lembretes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['success' => true, 'lembretes' => $lembretes]);
    } elseif ($action === 'delete') {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

        if ($id) {
            $stmt = $pdo->prepare("DELETE FROM lembrete WHERE id = :id AND id_usuario = :id_usuario");
            $stmt->execute([':id' => $id, ':id_usuario' => $id_usuario]);

            echo json_encode(['success' => true, 'message' => 'Lembrete deletado com sucesso!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'ID do lembrete inválido.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Ação inválida.']);
    }
} catch (PDOException $e) {
    error_log("Erro no banco de dados: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Erro ao processar a solicitação.']);
}
