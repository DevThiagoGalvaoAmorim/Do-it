<?php
require_once __DIR__ . '/../models/usuarios_model.php';
require_once __DIR__ . '/../middlewares/AuthAdminMiddleware.php';

class UsuariosController {
    public function index() {
        AuthAdminMiddleware::handle();
        $usuarios = listUsers();
        $mensagem = $_GET['msg'] ?? '';
        $erro = $_GET['erro'] ?? '';
        include __DIR__ . '/../views/admin/usuarios.php';
    }

    public function delete() {
        AuthAdminMiddleware::handle();
        if (isset($_POST['id'])) {
            $resultado = deleteUser($_POST['id']);
            $msg = $resultado ? "Usuário excluído com sucesso!" : "";
            $erro = $resultado ? "" : "Erro ao excluir usuário.";
            header("Location: usuarios_controller.php?action=index&msg=$msg&erro=$erro");
            exit;
        }
    }

    public function update() {
        AuthAdminMiddleware::handle();
        $id = $_POST['id'] ?? '';
        $nome = $_POST['nome'] ?? '';
        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';

        if (!empty($id) && !empty($nome) && !empty($email)) {
            $resultado = updateUser($id, $nome, $email, $senha);
            $msg = $resultado ? "Usuário atualizado com sucesso!" : "";
            $erro = $resultado ? "" : "Erro ao atualizar usuário.";
        } else {
            $erro = "Preencha todos os campos obrigatórios!";
            $msg = "";
        }

        header("Location: usuarios_controller.php?action=index&msg=$msg&erro=$erro");
        exit;
    }
}

$controller = new UsuariosController();
$action = $_GET['action'] ?? 'index';

if (method_exists($controller, $action)) {
    $controller->$action();
} else {
    echo "Ação inválida.";
}