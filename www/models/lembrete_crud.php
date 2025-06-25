<?php

function executarAcaoLembrete(PDO $pdo, array $dadosPost, array $dadosSession): array
{
    $action = $dadosPost['action'] ?? null;
    $id_usuario = $dadosSession['id'] ?? null;

    if (!$id_usuario) {
        return ['success' => false, 'message' => 'Usuário não autenticado.'];
    }

    try {
        if ($action === 'create') {
            $titulo = filter_var($dadosPost['titulo'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
            $descricao = filter_var($dadosPost['descricao'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
            $data_hora = $dadosPost['data_hora'] ?? null;

            if (!empty($titulo) && !empty($data_hora)) {
                $stmt = $pdo->prepare("INSERT INTO lembrete (titulo, descricao, data_hora, id_usuario) VALUES (:titulo, :descricao, :data_hora, :id_usuario)");
                $stmt->execute([
                    ':titulo' => $titulo,
                    ':descricao' => $descricao,
                    ':data_hora' => $data_hora,
                    ':id_usuario' => $id_usuario
                ]);

                return ['success' => true, 'message' => 'Lembrete criado com sucesso!'];
            } else {
                return ['success' => false, 'message' => 'Título e data são obrigatórios!'];
            }
        } elseif ($action === 'read') {
            $stmt = $pdo->prepare("SELECT id, titulo, descricao, data_hora FROM lembrete WHERE id_usuario = :id_usuario ORDER BY data_hora DESC");
            $stmt->execute([':id_usuario' => $id_usuario]);
            $lembretes = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return ['success' => true, 'lembretes' => $lembretes];
        } elseif ($action === 'delete') {
            $id = filter_var($dadosPost['id'] ?? null, FILTER_VALIDATE_INT);

            if ($id) {
                $stmt = $pdo->prepare("DELETE FROM lembrete WHERE id = :id AND id_usuario = :id_usuario");
                $stmt->execute([':id' => $id, ':id_usuario' => $id_usuario]);

                return ['success' => true, 'message' => 'Lembrete deletado com sucesso!'];
            } else {
                return ['success' => false, 'message' => 'ID do lembrete inválido.'];
            }
        } else {
            return ['success' => false, 'message' => 'Ação inválida.'];
        }
    } catch (PDOException $e) {
        error_log("Erro no banco de dados: " . $e->getMessage());
        return ['success' => false, 'message' => 'Erro ao processar a solicitação.'];
    }
}

// Executa somente se for via navegador
if (PHP_SAPI !== 'cli') {
    require_once __DIR__ . '/../../conexao_db/conexao.php';
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    header("Content-Type: application/json");
    $resposta = executarAcaoLembrete($pdo, $_POST, $_SESSION);
    echo json_encode($resposta);
}
