<?php

use PHPUnit\Framework\TestCase;

class UserControllersTest extends TestCase
{
    protected function setUp(): void
    {
        $_GET = [];
        $_POST = [];
        $_SESSION = [];
        $_SERVER['REQUEST_METHOD'] = 'GET';
    }

    public function testIndexAction()
    {
        // Mock AuthAdminMiddleware e listUsers
        require_once __DIR__ . '/../models/usuarios_model.php';
        require_once __DIR__ . '/../middlewares/AuthAdminMiddleware.php';

        if (!class_exists('AuthAdminMiddleware')) {
            class AuthAdminMiddleware {
                public static function handle() {
                    UserControllersTest::$authCalled = true;
                }
            }
        }
        if (!function_exists('listUsers')) {
            function listUsers() {
                return [
                    ['id' => 1, 'nome' => 'Teste', 'email' => 'teste@email.com']
                ];
            }
        }

        $_GET['action'] = 'index';
        $_GET['msg'] = 'Mensagem de sucesso';
        $_GET['erro'] = '';

        // Mock include
        $GLOBALS['__included'] = false;
        set_include_path(__DIR__);
        function include_override($file) {
            $GLOBALS['__included'] = $file;
        }
        // Executa o controller
        ob_start();
        require __DIR__ . '/../controllers/usuarios_controller.php';
        ob_end_clean();

        $this->assertTrue(true); // Se chegou até aqui, passou
    }

    public function testDeleteActionSuccess()
    {
        require_once __DIR__ . '/../models/usuarios_model.php';
        require_once __DIR__ . '/../middlewares/AuthAdminMiddleware.php';

        if (!class_exists('AuthAdminMiddleware')) {
            class AuthAdminMiddleware {
                public static function handle() {}
            }
        }
        if (!function_exists('deleteUser')) {
            function deleteUser($id) {
                UserControllersTest::$deleteUserCalled = $id;
                return true;
            }
        }

        $_GET['action'] = 'delete';
        $_POST['id'] = 5;
        $_SERVER['REQUEST_METHOD'] = 'POST';

        $this->expectOutputRegex('/Location: usuarios_controller\.php\?action=index&msg=Usuário excluído com sucesso!&erro=/');
        try {
            require __DIR__ . '/../controllers/usuarios_controller.php';
        } catch (\Throwable $e) {
            // Ignora exit
        }
        $this->assertEquals(5, UserControllersTest::$deleteUserCalled);
    }

    public function testDeleteActionFail()
    {
        require_once __DIR__ . '/../models/usuarios_model.php';
        require_once __DIR__ . '/../middlewares/AuthAdminMiddleware.php';

        if (!class_exists('AuthAdminMiddleware')) {
            class AuthAdminMiddleware {
                public static function handle() {}
            }
        }
        if (!function_exists('deleteUser')) {
            function deleteUser($id) {
                UserControllersTest::$deleteUserCalled = $id;
                return false;
            }
        }

        $_GET['action'] = 'delete';
        $_POST['id'] = 7;
        $_SERVER['REQUEST_METHOD'] = 'POST';

        $this->expectOutputRegex('/Location: usuarios_controller\.php\?action=index&msg=&erro=Erro ao excluir usuário\./');
        try {
            require __DIR__ . '/../controllers/usuarios_controller.php';
        } catch (\Throwable $e) {
            // Ignora exit
        }
        $this->assertEquals(7, UserControllersTest::$deleteUserCalled);
    }

    public function testUpdateActionSuccess()
    {
        require_once __DIR__ . '/../models/usuarios_model.php';
        require_once __DIR__ . '/../middlewares/AuthAdminMiddleware.php';

        if (!class_exists('AuthAdminMiddleware')) {
            class AuthAdminMiddleware {
                public static function handle() {}
            }
        }
        if (!function_exists('updateUser')) {
            function updateUser($id, $nome, $email, $senha) {
                UserControllersTest::$updateUserCalled = [$id, $nome, $email, $senha];
                return true;
            }
        }

        $_GET['action'] = 'update';
        $_POST['id'] = 2;
        $_POST['nome'] = 'Novo Nome';
        $_POST['email'] = 'novo@email.com';
        $_POST['senha'] = 'novasenha';
        $_SERVER['REQUEST_METHOD'] = 'POST';

        $this->expectOutputRegex('/Location: usuarios_controller\.php\?action=index&msg=Usuário atualizado com sucesso!&erro=/');
        try {
            require __DIR__ . '/../controllers/usuarios_controller.php';
        } catch (\Throwable $e) {
            // Ignora exit
        }
        $this->assertEquals([2, 'Novo Nome', 'novo@email.com', 'novasenha'], UserControllersTest::$updateUserCalled);
    }

    public function testUpdateActionCamposObrigatorios()
    {
        require_once __DIR__ . '/../models/usuarios_model.php';
        require_once __DIR__ . '/../middlewares/AuthAdminMiddleware.php';

        if (!class_exists('AuthAdminMiddleware')) {
            class AuthAdminMiddleware {
                public static function handle() {}
            }
        }
        if (!function_exists('updateUser')) {
            function updateUser($id, $nome, $email, $senha) {
                UserControllersTest::$updateUserCalled = [$id, $nome, $email, $senha];
                return true;
            }
        }

        $_GET['action'] = 'update';
        $_POST['id'] = '';
        $_POST['nome'] = '';
        $_POST['email'] = '';
        $_POST['senha'] = '';
        $_SERVER['REQUEST_METHOD'] = 'POST';

        $this->expectOutputRegex('/Location: usuarios_controller\.php\?action=index&msg=&erro=Preencha todos os campos obrigatórios!/');

        try {
            require __DIR__ . '/../controllers/usuarios_controller.php';
        } catch (\Throwable $e) {
            // Ignora exit
        }
    }

    public function testAcaoInvalida()
    {
        $_GET['action'] = 'acao_inexistente';

        ob_start();
        require __DIR__ . '/../controllers/usuarios_controller.php';
        $output = ob_get_clean();

        $this->assertStringContainsString('Ação inválida.', $output);
    }

    // Variáveis estáticas para mocks
    public static $deleteUserCalled = null;
    public static $updateUserCalled = null;
    public static $authCalled = false;
}