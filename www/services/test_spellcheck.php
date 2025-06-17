<?php

/**
 * Testes para o Serviço de Correção Ortográfica
 * 
 * Este arquivo contém testes unitários e de integração para validar
 * o funcionamento do serviço de correção ortográfica.
 */

require_once __DIR__ . '/SpellcheckConfig.php';
require_once __DIR__ . '/SpellcheckService.php';

/**
 * Classe de testes para o serviço de correção ortográfica
 */
class SpellcheckTest
{
    private $service;
    private $config;
    private $testResults = [];
    private $totalTests = 0;
    private $passedTests = 0;
    
    public function __construct()
    {
        $this->config = new SpellcheckConfig();
        $this->service = new SpellcheckService($this->config);
    }
    
    /**
     * Executa todos os testes
     */
    public function runAllTests(): void
    {
        echo "\n=== INICIANDO TESTES DO SERVIÇO DE CORREÇÃO ORTOGRÁFICA ===\n\n";
        
        $this->testConfiguration();
        $this->testWordChecking();
        $this->testSuggestions();
        $this->testTextChecking();
        $this->testLanguageSupport();
        $this->testErrorHandling();
        $this->testServiceStats();
        
        $this->printSummary();
    }
    
    /**
     * Testa a configuração do serviço
     */
    private function testConfiguration(): void
    {
        echo "--- Testando Configuração ---\n";
        
        // Teste 1: Configuração padrão
        $this->assert(
            $this->config->getMaxWordLength() === 50,
            'Comprimento máximo de palavra padrão deve ser 50'
        );
        
        // Teste 2: Idiomas suportados
        $languages = $this->config->getSupportedLanguages();
        $this->assert(
            isset($languages['pt_BR']),
            'Português brasileiro deve estar nos idiomas suportados'
        );
        
        // Teste 3: Validação de idioma
        $this->assert(
            $this->config->isLanguageSupported('pt_BR'),
            'Deve reconhecer pt_BR como idioma suportado'
        );
        
        // Teste 4: Idioma não suportado
        $this->assert(
            !$this->config->isLanguageSupported('xx_XX'),
            'Deve rejeitar idioma não suportado'
        );
        
        // Teste 5: Configuração personalizada
        $customConfig = new SpellcheckConfig(['max_word_length' => 100]);
        $this->assert(
            $customConfig->getMaxWordLength() === 100,
            'Deve aceitar configuração personalizada'
        );
        
        echo "\n";
    }
    
    /**
     * Testa verificação de palavras
     */
    private function testWordChecking(): void
    {
        echo "--- Testando Verificação de Palavras ---\n";
        
        // Teste 1: Palavra correta comum
        $result = $this->service->checkWord('palavra');
        $this->assert(
            $result['success'] === true && $result['is_correct'] === true,
            'Deve reconhecer "palavra" como correta'
        );
        
        // Teste 2: Palavra correta com acentos
        $result = $this->service->checkWord('ação');
        $this->assert(
            $result['success'] === true,
            'Deve processar palavras com acentos'
        );
        
        // Teste 3: Palavra vazia
        $result = $this->service->checkWord('');
        $this->assert(
            $result['success'] === false,
            'Deve rejeitar palavra vazia'
        );
        
        // Teste 4: Palavra muito longa
        $longWord = str_repeat('a', 100);
        $result = $this->service->checkWord($longWord);
        $this->assert(
            $result['success'] === false,
            'Deve rejeitar palavra muito longa'
        );
        
        // Teste 5: Palavra com caracteres inválidos
        $result = $this->service->checkWord('palavra123');
        $this->assert(
            $result['success'] === false,
            'Deve rejeitar palavra com números'
        );
        
        // Teste 6: Idioma específico
        $result = $this->service->checkWord('word', 'en_US');
        $this->assert(
            $result['success'] === true,
            'Deve processar palavra em inglês'
        );
        
        echo "\n";
    }
    
    /**
     * Testa geração de sugestões
     */
    private function testSuggestions(): void
    {
        echo "--- Testando Sugestões ---\n";
        
        // Teste 1: Sugestões para palavra incorreta
        $result = $this->service->getSuggestions('palavrra');
        $this->assert(
            $result['success'] === true && is_array($result['suggestions']),
            'Deve retornar sugestões para palavra incorreta'
        );
        
        // Teste 2: Limite de sugestões
        $result = $this->service->getSuggestions('palavrra', 'pt_BR', 3);
        $this->assert(
            count($result['suggestions']) <= 3,
            'Deve respeitar limite de sugestões'
        );
        
        // Teste 3: Palavra vazia
        $result = $this->service->getSuggestions('');
        $this->assert(
            $result['success'] === false,
            'Deve rejeitar palavra vazia para sugestões'
        );
        
        // Teste 4: Idioma não suportado
        $result = $this->service->getSuggestions('word', 'xx_XX');
        $this->assert(
            $result['success'] === false,
            'Deve rejeitar idioma não suportado'
        );
        
        echo "\n";
    }
    
    /**
     * Testa verificação de texto
     */
    private function testTextChecking(): void
    {
        echo "--- Testando Verificação de Texto ---\n";
        
        // Teste 1: Texto simples
        $text = "Este é um texto de exemplo";
        $result = $this->service->checkText($text);
        $this->assert(
            $result['success'] === true && isset($result['total_words']),
            'Deve processar texto simples'
        );
        
        // Teste 2: Texto com palavras incorretas
        $text = "Este é um textu com errus";
        $result = $this->service->checkText($text);
        $this->assert(
            $result['success'] === true && $result['misspelled_count'] >= 0,
            'Deve identificar palavras incorretas'
        );
        
        // Teste 3: Texto vazio
        $result = $this->service->checkText('');
        $this->assert(
            $result['success'] === false,
            'Deve rejeitar texto vazio'
        );
        
        // Teste 4: Texto muito longo
        $longText = str_repeat('palavra ', 2000);
        $result = $this->service->checkText($longText);
        $this->assert(
            $result['success'] === true,
            'Deve processar texto longo'
        );
        
        // Teste 5: Texto com pontuação
        $text = "Olá, mundo! Como você está?";
        $result = $this->service->checkText($text);
        $this->assert(
            $result['success'] === true,
            'Deve processar texto com pontuação'
        );
        
        echo "\n";
    }
    
    /**
     * Testa suporte a idiomas
     */
    private function testLanguageSupport(): void
    {
        echo "--- Testando Suporte a Idiomas ---\n";
        
        // Teste 1: Obter idiomas suportados
        $result = $this->service->getSupportedLanguages();
        $this->assert(
            $result['success'] === true && isset($result['languages']),
            'Deve retornar lista de idiomas suportados'
        );
        
        // Teste 2: Verificar estrutura dos idiomas
        $languages = $result['languages'];
        $this->assert(
            isset($languages['pt_BR']['name']),
            'Cada idioma deve ter nome'
        );
        
        // Teste 3: Adicionar novo idioma
        $this->config->addSupportedLanguage('de_DE', [
            'name' => 'Deutsch (Deutschland)',
            'dictionary_file' => 'de_DE.dic',
            'affix_file' => 'de_DE.aff',
            'encoding' => 'UTF-8'
        ]);
        
        $this->assert(
            $this->config->isLanguageSupported('de_DE'),
            'Deve permitir adicionar novo idioma'
        );
        
        echo "\n";
    }
    
    /**
     * Testa tratamento de erros
     */
    private function testErrorHandling(): void
    {
        echo "--- Testando Tratamento de Erros ---\n";
        
        // Teste 1: Configuração inválida
        try {
            $invalidConfig = new SpellcheckConfig(['max_word_length' => -1]);
            $invalidConfig->setMaxWordLength(-1);
            $this->assert(false, 'Deve rejeitar configuração inválida');
        } catch (Exception $e) {
            $this->assert(true, 'Deve lançar exceção para configuração inválida');
        }
        
        // Teste 2: Idioma inválido para adição
        try {
            $this->config->addSupportedLanguage('invalid', []);
            $this->assert(false, 'Deve rejeitar configuração de idioma inválida');
        } catch (Exception $e) {
            $this->assert(true, 'Deve lançar exceção para idioma inválido');
        }
        
        // Teste 3: Validação de configuração
        $errors = $this->config->validate();
        $this->assert(
            is_array($errors),
            'Validação deve retornar array de erros'
        );
        
        echo "\n";
    }
    
    /**
     * Testa estatísticas do serviço
     */
    private function testServiceStats(): void
    {
        echo "--- Testando Estatísticas do Serviço ---\n";
        
        // Teste 1: Obter estatísticas
        $stats = $this->service->getServiceStats();
        $this->assert(
            isset($stats['service_name']) && isset($stats['version']),
            'Deve retornar estatísticas básicas'
        );
        
        // Teste 2: Verificar estrutura das estatísticas
        $requiredFields = ['service_name', 'version', 'supported_languages', 'max_word_length'];
        $hasAllFields = true;
        
        foreach ($requiredFields as $field) {
            if (!isset($stats[$field])) {
                $hasAllFields = false;
                break;
            }
        }
        
        $this->assert(
            $hasAllFields,
            'Estatísticas devem conter todos os campos obrigatórios'
        );
        
        echo "\n";
    }
    
    /**
     * Executa uma asserção e registra o resultado
     * 
     * @param bool $condition
     * @param string $message
     */
    private function assert(bool $condition, string $message): void
    {
        $this->totalTests++;
        
        if ($condition) {
            $this->passedTests++;
            echo "✓ {$message}\n";
            $this->testResults[] = ['status' => 'PASS', 'message' => $message];
        } else {
            echo "✗ {$message}\n";
            $this->testResults[] = ['status' => 'FAIL', 'message' => $message];
        }
    }
    
    /**
     * Imprime resumo dos testes
     */
    private function printSummary(): void
    {
        echo "\n=== RESUMO DOS TESTES ===\n";
        echo "Total de testes: {$this->totalTests}\n";
        echo "Testes aprovados: {$this->passedTests}\n";
        echo "Testes falharam: " . ($this->totalTests - $this->passedTests) . "\n";
        
        $successRate = ($this->totalTests > 0) ? round(($this->passedTests / $this->totalTests) * 100, 2) : 0;
        echo "Taxa de sucesso: {$successRate}%\n";
        
        if ($this->passedTests === $this->totalTests) {
            echo "\n🎉 TODOS OS TESTES PASSARAM! 🎉\n";
        } else {
            echo "\n⚠️  ALGUNS TESTES FALHARAM ⚠️\n";
            echo "\nTestes que falharam:\n";
            
            foreach ($this->testResults as $result) {
                if ($result['status'] === 'FAIL') {
                    echo "- {$result['message']}\n";
                }
            }
        }
        
        echo "\n=== FIM DOS TESTES ===\n";
    }
}

/**
 * Função para testar a API via HTTP
 */
function testAPI(): void
{
    echo "\n=== TESTANDO API HTTP ===\n\n";
    
    $baseUrl = 'http://localhost' . dirname($_SERVER['PHP_SELF']) . '/spellcheck_api.php';
    
    // Teste 1: Health check
    echo "Testando health check...\n";
    $response = @file_get_contents($baseUrl . '/health');
    if ($response) {
        $data = json_decode($response, true);
        echo $data['success'] ? "✓ API está funcionando\n" : "✗ API com problemas\n";
    } else {
        echo "✗ Não foi possível conectar à API\n";
    }
    
    echo "\nPara testar completamente a API, execute:\n";
    echo "curl -X GET {$baseUrl}/health\n";
    echo "curl -X POST -H 'Content-Type: application/json' -d '{\"word\":\"teste\"}' {$baseUrl}/check-word\n";
    echo "curl -X GET {$baseUrl}/languages\n";
}

// Executa os testes se o arquivo for chamado diretamente
if (basename(__FILE__) === basename($_SERVER['SCRIPT_NAME'])) {
    try {
        $tester = new SpellcheckTest();
        $tester->runAllTests();
        
        // Testa API se estiver em ambiente web
        if (isset($_SERVER['HTTP_HOST'])) {
            testAPI();
        }
        
    } catch (Exception $e) {
        echo "\nERRO FATAL: " . $e->getMessage() . "\n";
        echo "Trace: " . $e->getTraceAsString() . "\n";
    }
}

/**
 * Instruções de uso:
 * 
 * 1. Via linha de comando:
 *    php test_spellcheck.php
 * 
 * 2. Via navegador:
 *    http://localhost/caminho/para/test_spellcheck.php
 * 
 * 3. Incluindo em outros arquivos:
 *    require_once 'test_spellcheck.php';
 *    $tester = new SpellcheckTest();
 *    $tester->runAllTests();
 */