<?php
require_once __DIR__ . '/../models/AdminModel.php';
require_once __DIR__ . '/../middlewares/AuthAdminMiddleware.php';

class AdminController {
    private $model;

    public function __construct() {
        $this->model = new AdminModel();
    }

    public function index() {
        AuthAdminMiddleware::handle();
        $usuarios = $this->model->listUsers();
        $mensagem = $_GET['msg'] ?? '';
        $erro = $_GET['erro'] ?? '';
        include __DIR__ . '/../views/admin/usuarios.php';
    }

    public function delete() {
        AuthAdminMiddleware::handle();
        if (isset($_POST['id'])) {
            $resultado = $this->model->deleteUser($_POST['id']);
            $msg = $resultado ? "Usuário excluído com sucesso!" : "";
            $erro = $resultado ? "" : "Erro ao excluir usuário.";
            header("Location: admin_controller.php?action=index&msg=$msg&erro=$erro");
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
            $resultado = $this->model->updateUser($id, $nome, $email, $senha);
            $msg = $resultado ? "Usuário atualizado com sucesso!" : "";
            $erro = $resultado ? "" : "Erro ao atualizar usuário.";
        } else {
            $erro = "Preencha todos os campos obrigatórios!";
            $msg = "";
        }

        header("Location: admin_controller.php?action=index&msg=$msg&erro=$erro");
        exit;
    }

    public function stats() {
        AuthAdminMiddleware::handle();
        include __DIR__ . '/../views/admin/stats.php';
    }

    public function login() {
        session_start();
        $erro = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $senha = $_POST['senha'] ?? '';

            if (!empty($email) && !empty($senha)) {
                $usuario = $this->model->buscarPorEmailSenha($email, $senha);

                if ($usuario && $usuario['tipo'] === 'admin') {
                    $_SESSION['id'] = $usuario['id'];
                    $_SESSION['nome'] = $usuario['nome'];
                    $_SESSION['email'] = $usuario['email'];
                    $_SESSION['tipo'] = 'admin';
                    header('Location: admin_controller.php?action=index');
                    exit;
                } else {
                    $erro = 'Acesso negado. Usuário não autorizado.';
                }
            } else {
                $erro = 'Preencha todos os campos.';
            }
        }

        include __DIR__ . '/../views/admin/login.php';
    }
}

// roteamento
$controller = new AdminController();
$action = $_GET['action'] ?? 'index';

if (method_exists($controller, $action)) {
    $controller->$action();
} else {
    echo "Ação inválida.";
}