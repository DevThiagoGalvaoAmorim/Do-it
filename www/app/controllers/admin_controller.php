<?php
session_start();
require_once 'conexao_db/conexao.php';
require_once 'conexao_db/usuarios_crud.php';

// Verificar se o usuário está logado e é administrador
if (!isset($_SESSION['id']) || !isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Processar as ações do administrador
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $response = ['success' => false, 'message' => 'Ação inválida'];
    
    switch ($action) {
        case 'create':
            $nome = $_POST['nome'] ?? '';
            $email = $_POST['email'] ?? '';
            $senha = $_POST['senha'] ?? '';
            $tipo = $_POST['tipo'] ?? 'user';
            
            if (!empty($nome) && !empty($email) && !empty($senha)) {
                $id = criarUsuario($nome, $email, $senha, $tipo);
                if ($id) {
                    $response = ['success' => true, 'message' => 'Usuário criado com sucesso', 'id' => $id];
                } else {
                    $response = ['success' => false, 'message' => 'Erro ao criar usuário'];
                }
            } else {
                $response = ['success' => false, 'message' => 'Preencha todos os campos'];
            }
            break;
            
        case 'update':
            $id = $_POST['id'] ?? '';
            $nome = $_POST['nome'] ?? '';
            $email = $_POST['email'] ?? '';
            $senha = $_POST['senha'] ?? null;
            
            if (!empty($id) && !empty($nome) && !empty($email)) {
                $result = atualizarUsuario($id, $nome, $email, $senha);
                if ($result) {
                    $response = ['success' => true, 'message' => 'Usuário atualizado com sucesso'];
                } else {
                    $response = ['success' => false, 'message' => 'Erro ao atualizar usuário'];
                }
            } else {
                $response = ['success' => false, 'message' => 'Dados incompletos'];
            }
            break;
            
        case 'delete':
            $id = $_POST['id'] ?? '';
            
            if (!empty($id)) {
                $result = deletarUsuario($id); // Corrigido: usando a função deletarUsuario em vez de excluirUsuario
                if ($result) {
                    $response = ['success' => true, 'message' => 'Usuário excluído com sucesso'];
                } else {
                    $response = ['success' => false, 'message' => 'Erro ao excluir usuário'];
                }
            } else {
                $response = ['success' => false, 'message' => 'ID não fornecido'];
            }
            break;
            
        case 'change_type':
            $id = $_POST['id'] ?? '';
            $tipo = $_POST['tipo'] ?? '';
            
            if (!empty($id) && !empty($tipo)) {
                $result = atualizarUsuario($id, null, null, null, $tipo);
                if ($result) {
                    $response = ['success' => true, 'message' => 'Tipo de usuário alterado com sucesso'];
                } else {
                    $response = ['success' => false, 'message' => 'Erro ao alterar tipo de usuário'];
                }
            } else {
                $response = ['success' => false, 'message' => 'Dados incompletos'];
            }
            break;
    }
    
    // Retornar resposta em formato JSON
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Se for uma requisição GET, retornar a lista de usuários
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
    if ($_GET['action'] === 'list') {
        $busca = $_GET['busca'] ?? null;
        $usuarios = listarUsuarios($busca);
        
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'usuarios' => $usuarios]);
        exit;
    } elseif ($_GET['action'] === 'get' && isset($_GET['id'])) {
        $usuario = buscarUsuarioPorId($_GET['id']);
        
        if ($usuario) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'usuario' => $usuario]);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Usuário não encontrado']);
        }
        exit;
    }
}

// Se chegou aqui, redirecionar para a página de administração
header('Location: admin.php');
exit;
?>