<?php
require_once __DIR__ . '/usuarios_crud.php';

header('Content-Type: application/json');

$action = $_GET['action'] ?? $_POST['action'] ?? '';

switch ($action) {
    case 'listar':
        $usuarios = listarUsuarios();
        echo json_encode($usuarios);
        break;
    case 'atualizar':
        $id = $_POST['id'];
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $senha = $_POST['senha'] ?? null;
        $ok = atualizarUsuario($id, $nome, $email, $senha);
        echo json_encode(['success' => $ok]);
        break;
    case 'deletar':
        $id = $_POST['id'];
        $ok = deletarUsuario($id);
        echo json_encode(['success' => $ok]);
        break;
    default:
        echo json_encode(['error' => 'Ação inválida']);
}