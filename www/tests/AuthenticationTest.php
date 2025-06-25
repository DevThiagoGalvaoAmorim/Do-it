<?php

use PHPUnit\Framework\TestCase;

class AuthenticationTest extends TestCase
{
    protected function setUp(): void
    {
        // Limpa variáveis globais antes de cada teste
        $_POST = [];
        $_SESSION = [];
        $_SERVER['REQUEST_METHOD'] = 'POST';
    }

    public function testLoginAdminSuccess()
    {
        // Mock dos dados de admin
        $_POST['email'] = 'admin@email.com';
        $_POST['senha'] = 'admin123';
        $_POST['nome'] = '';

        // Mock do PDO e fetch
        $pdo = $this->createMock(PDO::class);
        $stmt = $this->createMock(PDOStatement::class);

        $pdo->method('prepare')->willReturn($stmt);
        $stmt->method('execute')->willReturn(true);
        $stmt->method('fetch')->willReturn([
            'id' => 1,
            'nome' => 'Admin',
            'email' => 'admin@email.com',
            'tipo' => 'admin'
        ]);

        // Inclui o controller com mocks
        ob_start();
        require __DIR__ . '/../controllers/auth_controller.php';
        ob_end_clean();

        $this->assertEquals(1, $_SESSION['id']);
        $this->assertEquals('Admin', $_SESSION['nome']);
        $this->assertEquals('admin@email.com', $_SESSION['email']);
        $this->assertEquals('admin', $_SESSION['tipo']);
    }

    public function testLoginUserFail()
    {
        $_POST['email'] = 'user@email.com';
        $_POST['senha'] = 'wrongpass';
        $_POST['nome'] = '';

        // Mock do buscarUsuario para retornar falso
        require_once __DIR__ . '/../models/usuarios_crud.php';
        function buscarUsuario($email, $senha) {
            return false;
        }

        ob_start();
        require __DIR__ . '/../controllers/auth_controller.php';
        $output = ob_get_clean();

        $this->assertStringContainsString('Email ou senha incorretos', $output);
    }

    public function testCadastroSuccess()
    {
        $_POST['nome'] = 'Novo Usuário';
        $_POST['email'] = 'novo@email.com';
        $_POST['senha'] = 'senha123';

        // Mock do criarUsuario
        require_once __DIR__ . '/../models/usuarios_crud.php';
        function criarUsuario($nome, $email, $senha) {
            return 99;
        }

        ob_start();
        require __DIR__ . '/../controllers/auth_controller.php';
        ob_end_clean();

        $this->assertEquals(99, $_SESSION['id']);
        $this->assertEquals('Novo Usuário', $_SESSION['nome']);
        $this->assertEquals('novo@email.com', $_SESSION['email']);
        $this->assertEquals('user', $_SESSION['tipo']);
    }

    public function testCadastroCamposVazios()
    {
        $_POST['nome'] = '';
        $_POST['email'] = '';
        $_POST['senha'] = '';

        ob_start();
        require __DIR__ . '/../controllers/auth_controller.php';
        $output = ob_get_clean();

        $this->assertStringContainsString('Preencha todos os campos', $output);
    }
}