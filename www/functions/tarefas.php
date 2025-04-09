<?php
session_start();
require_once __DIR__ . '/../conexao.php';

header('Content-Type: application/json');

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        echo json_encode(lerTodasTarefas());
        break;
        
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        if (!empty($data['nomeTarefa'])) {
            $result = criarTarefa(trim($data['nomeTarefa']));
            http_response_code($result ? 201 : 500);
            echo json_encode(['success' => $result]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Nome obrigatório']);
        }
        break;
        
    case 'DELETE':
        if (!empty($_GET['id']) && is_numeric($_GET['id'])) {
            $result = removerTarefa((int)$_GET['id']);
            http_response_code($result ? 200 : 500);
            echo json_encode(['success' => $result]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'ID inválido']);
        }
        break;
        
    default:
        http_response_code(405);
        header('Allow: GET, POST, DELETE');
}

function criarTarefa($nomeTarefa) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO tarefas (titulo, descricao, status, usuario_id) VALUES (:titulo, NULL, FALSE, :usuario_id)");
    $result = $stmt->execute(['titulo' => $nomeTarefa,':usuario_id' => $_SESSION['usuario_id']]);
    return $result;
}

function lerTodasTarefas() {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM tarefas WHERE usuario_id = :usuario_id ORDER BY id DESC");
    $stmt->execute(['usuario_id' => $_SESSION['usuario_id']]);
    return $stmt->fetchAll();
}

function removerTarefa($idTarefa) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM tarefas WHERE id = :id AND usuario_id = :usuario_id");
    $stmt->execute(['id' => $idTarefa , 'usuario_id' => $_SESSION['usuario_id']]);
    return $stmt->execute();
}
?>