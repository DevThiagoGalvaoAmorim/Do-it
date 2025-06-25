<?php
<?php

use PHPUnit\Framework\TestCase;

class ProfileTest extends TestCase
{
    protected function setUp(): void
    {
        $_POST = [];
        $_SESSION = [];
        $_SERVER['REQUEST_METHOD'] = 'GET';
        // Limpa variáveis estáticas de mock
        self::$atualizarUsuarioCalled = null;
        self::$deletarUsuarioCalled = null;
    }

    public function testSalvarPerfil()
    {
        $_SESSION['id'] = 10;
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['acao'] = 'salvar';
        $_POST['nome'] = 'Novo Nome';
        $_POST['email'] = 'novo@email.com';
        $_POST['senha'] = 'novasenha';

        // Mock das funções
        require_once __DIR__ . '/../models/usuarios_crud.php';
        if (!function_exists('atualizarUsuario')) {
            function atualizarUsuario($id, $nome, $email, $senha) {
                ProfileTest::$atualizarUsuarioCalled = [$id, $nome, $email, $senha];
            }
        }

        // Captura o header
        $this->expectOutputRegex('/Location: \.\.\/views\/perfil\.php\?atualizado=1/');
        try {
            require __DIR__ . '/../controllers/processar_perfil.php';
        } catch (\Throwable $e) {
            // Ignora exit
        }
        $this->assertEquals([10, 'Novo Nome', 'novo@email.com', 'novasenha'], ProfileTest::$atualizarUsuarioCalled);
    }

    public function testDeletarPerfil()
    {
        $_SESSION['id'] = 11;
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['acao'] = 'deletar';

        // Mock das funções
        require_once __DIR__ . '/../models/usuarios_crud.php';
        if (!function_exists('deletarUsuario')) {
            function deletarUsuario($id) {
                ProfileTest::$deletarUsuarioCalled = $id;
            }
        }

        // Captura o header
        $this->expectOutputRegex('/Location: \.\.\/public\/index\.php/');
        try {
            require __DIR__ . '/../controllers/processar_perfil.php';
        } catch (\Throwable $e) {
            // Ignora exit
        }
        $this->assertEquals(11, ProfileTest::$deletarUsuarioCalled);
    }

    public function testBuscarNotasEDataCriacao()
    {
        $_SESSION['id'] = 12;
        $_SERVER['REQUEST_METHOD'] = 'GET';

        // Mock do buscarUsuarioPorId
        require_once __DIR__ . '/../models/usuarios_crud.php';
        if (!function_exists('buscarUsuarioPorId')) {
            function buscarUsuarioPorId($id) {
                return ['id' => $id, 'nome' => 'Teste'];
            }
        }

        // Mock do PDO
        global $pdo;
        $pdo = $this->createMock(PDO::class);
        $stmt = $this->createMock(PDOStatement::class);

        $pdo->method('prepare')->willReturn($stmt);
        $stmt->method('execute')->willReturn(true);
        $stmt->method('fetchColumn')->will($this->onConsecutiveCalls(5, '2024-06-01 12:00:00'));

        ob_start();
        $usuario = require __DIR__ . '/../controllers/processar_perfil.php';
        ob_end_clean();

        $this->assertEquals(['id' => 12, 'nome' => 'Teste'], $usuario);
    }

    public function testRedirecionaErroSeNaoLogado()
    {
        $_SESSION['id'] = null;
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $this->expectOutputRegex('/Location: \.\.\/views\/perfil\.php\?erro=1/');
        try {
            require __DIR__ . '/../controllers/processar_perfil.php';
        } catch (\Throwable $e) {
            // Ignora exit
        }
    }

    // Variáveis estáticas para mocks
    public static $atualizarUsuarioCalled = null;
    public static $deletarUsuarioCalled = null;
}
