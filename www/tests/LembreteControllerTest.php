<?php
use PHPUnit\Framework\TestCase;

class LembreteControllerTest extends TestCase
{
    protected function setUp(): void
    {
        $_POST = [];
        $_SESSION = [];
    }

    /** @test */
    public function testCreateComDadosValidos()
    {
        $_POST = [
            'action' => 'create',
            'titulo' => 'Novo lembrete',
            'descricao' => 'Algo a fazer',
            'data_hora' => '2025-07-01 10:00:00'
        ];
        $_SESSION = ['id' => 1];

        /** @var PDO&\PHPUnit\Framework\MockObject\MockObject $pdoMock */
        $pdoMock = $this->createMock(PDO::class);
        $stmtMock = $this->createMock(PDOStatement::class);

        $pdoMock->method('prepare')->willReturn($stmtMock);
        $stmtMock->method('execute')->willReturn(true);

        require_once __DIR__ . '/../models/lembrete_crud.php';
        $resposta = executarAcaoLembrete($pdoMock, $_POST, $_SESSION);

        $this->assertTrue($resposta['success']);
        $this->assertEquals('Lembrete criado com sucesso!', $resposta['message']);
    }

    /** @test */
    public function testCreateComDadosIncompletosFalha()
    {
        $_POST = ['action' => 'create', 'titulo' => '', 'data_hora' => ''];
        $_SESSION = ['id' => 1];

        $pdoMock = $this->createMock(PDO::class);

        require_once __DIR__ . '/../models/lembrete_crud.php';
        $resposta = executarAcaoLembrete($pdoMock, $_POST, $_SESSION);

        $this->assertFalse($resposta['success']);
        $this->assertEquals('Título e data são obrigatórios!', $resposta['message']);
    }

    /** @test */
    public function testReadRetornaListaDeLembretes()
    {
        $_POST = ['action' => 'read'];
        $_SESSION = ['id' => 1];

        $pdoMock = $this->createMock(PDO::class);
        $stmtMock = $this->createMock(PDOStatement::class);

        $pdoMock->method('prepare')->willReturn($stmtMock);
        $stmtMock->method('execute')->willReturn(true);
        $stmtMock->method('fetchAll')->willReturn([
            ['id' => 1, 'titulo' => 'Exemplo', 'descricao' => 'desc', 'data_hora' => '2025-07-01 10:00:00']
        ]);

        require_once __DIR__ . '/../models/lembrete_crud.php';
        $resposta = executarAcaoLembrete($pdoMock, $_POST, $_SESSION);

        $this->assertTrue($resposta['success']);
        $this->assertIsArray($resposta['lembretes']);
        $this->assertCount(1, $resposta['lembretes']);
    }

    /** @test */
    public function testDeleteComIdValido()
    {
        $_POST = ['action' => 'delete', 'id' => 3];
        $_SESSION = ['id' => 1];

        $pdoMock = $this->createMock(PDO::class);
        $stmtMock = $this->createMock(PDOStatement::class);

        $pdoMock->method('prepare')->willReturn($stmtMock);
        $stmtMock->method('execute')->willReturn(true);

        require_once __DIR__ . '/../models/lembrete_crud.php';
        $resposta = executarAcaoLembrete($pdoMock, $_POST, $_SESSION);

        $this->assertTrue($resposta['success']);
        $this->assertEquals('Lembrete deletado com sucesso!', $resposta['message']);
    }

    /** @test */
    public function testDeleteComIdInvalidoFalha()
    {
        $_POST = ['action' => 'delete', 'id' => null];
        $_SESSION = ['id' => 1];

        $pdoMock = $this->createMock(PDO::class);

        require_once __DIR__ . '/../models/lembrete_crud.php';
        $resposta = executarAcaoLembrete($pdoMock, $_POST, $_SESSION);

        $this->assertFalse($resposta['success']);
        $this->assertEquals('ID do lembrete inválido.', $resposta['message']);
    }

    /** @test */
    public function testSemUsuarioLogadoFalha()
    {
        $_POST = ['action' => 'read'];
        $_SESSION = []; // sem id

        $pdoMock = $this->createMock(PDO::class);

        require_once __DIR__ . '/../models/lembrete_crud.php';
        $resposta = executarAcaoLembrete($pdoMock, $_POST, $_SESSION);

        $this->assertFalse($resposta['success']);
        $this->assertEquals('Usuário não autenticado.', $resposta['message']);
    }

    /** @test */
    public function testAcaoInvalidaFalha()
    {
        $_POST = ['action' => 'editar_coisa_estranha'];
        $_SESSION = ['id' => 1];

        $pdoMock = $this->createMock(PDO::class);

        require_once __DIR__ . '/../models/lembrete_crud.php';
        $resposta = executarAcaoLembrete($pdoMock, $_POST, $_SESSION);

        $this->assertFalse($resposta['success']);
        $this->assertEquals('Ação inválida.', $resposta['message']);
    }
}