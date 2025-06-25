<?php
session_start();
require_once __DIR__ .'../../conexao_db/conexao.php';

$action = $_POST['action'] ?? null;

try {
    if ($action === 'restaurar') {
        $id = $_POST['id'] ?? null;
        $id_usuario = $_SESSION['id'];

        if ($id && $id_usuario) {
            // Buscar a nota na lixeira
            $stmt = $pdo->prepare("SELECT * FROM lixeira WHERE id = :id AND id_usuario = :id_usuario");
            $stmt->execute([':id' => $id, ':id_usuario' => $id_usuario]);
            $nota = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($nota) {
                // Inserir de volta na tabela notas
                $stmtNota = $pdo->prepare("INSERT INTO notas (titulo, descricao, data_hora, pasta, id_usuario, tipo, imagem_url, video_url) VALUES (:titulo, :descricao, :data_hora, :pasta, :id_usuario, :tipo, :imagem_url, :video_url)");
                $stmtNota->execute([
                    ':titulo' => $nota['titulo'],
                    ':descricao' => $nota['descricao'],
                    ':data_hora' => $nota['data_hora'],
                    ':pasta' => $nota['pasta'],
                    ':id_usuario' => $nota['id_usuario'],
                    ':tipo' => $nota['tipo'],
                    ':imagem_url' => $nota['imagem_url'],
                    ':video_url' => $nota['video_url']
                ]);
                // Remover da lixeira
                $stmtDelete = $pdo->prepare("DELETE FROM lixeira WHERE id = :id AND id_usuario = :id_usuario");
                $stmtDelete->execute([':id' => $id, ':id_usuario' => $id_usuario]);

                echo json_encode(['success' => true, 'message' => 'Nota restaurada com sucesso!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Nota não encontrada na lixeira.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'ID inválido.']);
        }
    } elseif ($action === 'excluir') {
        $id = $_POST['id'] ?? null;
        $id_usuario = $_SESSION['id'];

        if ($id && $id_usuario) {
            $stmtDelete = $pdo->prepare("DELETE FROM lixeira WHERE id = :id AND id_usuario = :id_usuario");
            $stmtDelete->execute([':id' => $id, ':id_usuario' => $id_usuario]);
            echo json_encode(['success' => true, 'message' => 'Nota excluída definitivamente.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'ID inválido.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Ação inválida.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erro no banco de dados: ' . $e->getMessage()]);
}
?>