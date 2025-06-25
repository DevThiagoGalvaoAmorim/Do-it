<?php
session_start();
require_once __DIR__ . '/../conexao_db/conexao.php';

$action = $_POST['action'] ?? null;

try {
    if ($action === 'desarquivar') {
        $id = $_POST['id'] ?? null;
        $id_usuario = $_SESSION['id'];

        if ($id && $id_usuario) {
            // Buscar a nota arquivada
            $stmt = $pdo->prepare("SELECT * FROM arquivadas WHERE id = :id AND id_usuario = :id_usuario");
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
                
                // Remover das arquivadas
                $stmtDelete = $pdo->prepare("DELETE FROM arquivadas WHERE id = :id AND id_usuario = :id_usuario");
                $stmtDelete->execute([':id' => $id, ':id_usuario' => $id_usuario]);

                echo json_encode(['success' => true, 'message' => 'Nota desarquivada com sucesso!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Nota não encontrada nos arquivos.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'ID inválido.']);
        }
    } elseif ($action === 'excluir') {
        // Mover nota arquivada para lixeira
        $id = $_POST['id'] ?? null;
        $id_usuario = $_SESSION['id'];

        if ($id && $id_usuario) {
            // Buscar a nota arquivada
            $stmt = $pdo->prepare("SELECT * FROM arquivadas WHERE id = :id AND id_usuario = :id_usuario");
            $stmt->execute([':id' => $id, ':id_usuario' => $id_usuario]);
            $nota = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($nota) {
                // Inserir na lixeira
                $stmtLixeira = $pdo->prepare("INSERT INTO lixeira (titulo, descricao, data_hora, pasta, id_usuario, tipo, imagem_url, video_url) VALUES (:titulo, :descricao, :data_hora, :pasta, :id_usuario, :tipo, :imagem_url, :video_url)");
                $stmtLixeira->execute([
                    ':titulo' => $nota['titulo'],
                    ':descricao' => $nota['descricao'],
                    ':data_hora' => $nota['data_hora'],
                    ':pasta' => $nota['pasta'],
                    ':id_usuario' => $nota['id_usuario'],
                    ':tipo' => $nota['tipo'],
                    ':imagem_url' => $nota['imagem_url'],
                    ':video_url' => $nota['video_url']
                ]);

                // Remover das arquivadas
                $stmtDelete = $pdo->prepare("DELETE FROM arquivadas WHERE id = :id AND id_usuario = :id_usuario");
                $stmtDelete->execute([':id' => $id, ':id_usuario' => $id_usuario]);

                echo json_encode(['success' => true, 'message' => 'Nota movida para a lixeira com sucesso!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Nota não encontrada nos arquivos.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'ID inválido.']);
        }
    } elseif ($action === 'read') {
        // Ler todas as notas arquivadas do usuário
        $id_usuario = $_SESSION['id'];
        
        if ($id_usuario) {
            $stmt = $pdo->prepare("SELECT * FROM arquivadas WHERE id_usuario = :id_usuario ORDER BY data_hora DESC");
            $stmt->execute([':id_usuario' => $id_usuario]);
            $notas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($notas);
        } else {
            echo json_encode(['success' => false, 'message' => 'ID do usuário não encontrado']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Ação inválida.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erro no banco de dados: ' . $e->getMessage()]);
}
?>
