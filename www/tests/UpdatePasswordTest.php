<?php

use PHPUnit\Framework\TestCase;

class UpdatePasswordTest extends TestCase
{
    protected function setUp(): void
    {
        $_POST = [];
        // Limpa funções globais que possam ter sido setadas em outros testes
    }

    public function testTokenInvalido()
    {
        $_POST['token'] = 'token_invalido';
        $_POST['senha'] = 'novaSenha123';

        // Mock da função buscarToken para retornar falso
        require_once __DIR__ . '/../models/account_config.php';
        if (!function_exists('buscarToken')) {
            function buscarToken($token) {
                return false;
            }
        }

        ob_start();
        require __DIR__ . '/../controllers/atualizar_senha.php';
        $output = ob_get_clean();

        $this->assertStringContainsString('Token inválido.', $output);
    }

    public function testAtualizarSenhaSucesso()
    {
        $_POST['token'] = 'token_valido';
        $_POST['senha'] = 'novaSenha123';

        // Mocks das funções usadas no controller
        require_once __DIR__ . '/../models/account_config.php';
        if (!function_exists('buscarToken')) {
            function buscarToken($token) {
                return 'usuario@email.com';
            }
        }
        if (!function_exists('atualizarSenha')) {
            function atualizarSenha($senha, $email) {
                return true;
            }
        }
        if (!function_exists('tela_de_mensagem')) {
            function tela_de_mensagem($msg) {
                echo $msg;
            }
        }
        if (!function_exists('apagarToken')) {
            function apagarToken($token) {
                // Simula apagar token
            }
        }

        ob_start();
        require __DIR__ . '/../controllers/atualizar_senha.php';
        $output = ob_get_clean();

        $this->assertStringContainsString('Deu certo ao atualizar a senha!', $output);
    }

    public function testAtualizarSenhaErro()
    {
        $_POST['token'] = 'token_valido';
        $_POST['senha'] = 'novaSenha123';

        // Mocks das funções usadas no controller
        require_once __DIR__ . '/../models/account_config.php';
        if (!function_exists('buscarToken')) {
            function buscarToken($token) {
                return 'usuario@email.com';
            }
        }
        if (!function_exists('atualizarSenha')) {
            function atualizarSenha($senha, $email) {
                return false;
            }
        }
        if (!function_exists('tela_de_mensagem')) {
            function tela_de_mensagem($msg) {
                echo $msg;
            }
        }
        if (!function_exists('apagarToken')) {
            function apagarToken($token) {
                // Simula apagar token
            }
        }

        ob_start();
        require __DIR__ . '/../controllers/atualizar_senha.php';
        $output = ob_get_clean();

        $this->assertStringContainsString('Erro ao atualizar senha! Tente novamente.', $output);
    }
}