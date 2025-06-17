<?php

/**
 * Testes para o Serviço de Correção Ortográfica
 *
 * Este arquivo contém testes unitários e de integração para validar
 * o funcionamento do serviço de correção ortográfica e sua API REST.
 */

// --- Inclusão das classes necessárias ---
// Certifique-se de que os caminhos para suas classes estão corretos
require_once __DIR__ . '/SpellcheckConfig.php';
require_once __DIR__ . '/SpellcheckService.php';
// Se você está testando a API HTTP, inclua também o arquivo da API.
// Este é o arquivo que contém a classe SpellcheckAPI e seu ponto de entrada.
require_once __DIR__ . '/spellcheck_api.php'; // Ajuste 'spellcheck_api.php' para o nome real do seu arquivo da API

/**
 * Classe de testes para o serviço de correção ortográfica.
 * Implementa um framework de teste básico com asserções e resumo.
 */
class SpellcheckTest
{
    private SpellcheckService $service; // Instância do serviço de verificação ortográfica
    private SpellcheckConfig $config;   // Instância da configuração do serviço
    private array $testResults = [];    // Armazena os resultados de cada teste
    private int $totalTests = 0;        // Contador de testes totais
    private int $passedTests = 0;       // Contador de testes aprovados

    /**
     * Construtor da classe SpellcheckTest.
     * Inicializa as instâncias de SpellcheckConfig e SpellcheckService.
     */
    public function __construct()
    {
        // Inicializa a configuração (pode ser personalizada para testes, se necessário)
        $this->config = new SpellcheckConfig([
            // Para testes, você pode querer garantir que um idioma específico está carregado
            // ou que os caminhos dos dicionários são válidos para o ambiente de teste.
            // Exemplo: 'dictionary_path' => __DIR__ . '/../public/Javascript/dictionaries/'
            'supported_languages' => [
                'pt_BR' => [
                    'name' => 'Português (Brasil)',
                    'dictionary_file' => 'pt_BR.dic',
                    'affix_file' => 'pt_BR.aff',
                    'encoding' => 'UTF-8'
                ],
                 'en_US' => [
                    'name' => 'English (United States)',
                    'dictionary_file' => 'en_US.dic',
                    'affix_file' => 'en_US.aff',
                    'encoding' => 'UTF-8'
                ]
            ]
        ]);

        // Valida a configuração antes de criar o serviço
        $configErrors = $this->config->validate();
        if (!empty($configErrors)) {
            echo "ERRO: Configuração de teste inválida. Por favor, verifique os caminhos dos dicionários e a estrutura dos idiomas.\n";
            foreach ($configErrors as $error) {
                echo "- " . $error . "\n";
            }
            // Não lança exceção para permitir que o script continue e mostre o erro no resumo.
            // Em um framework de teste real (ex: PHPUnit), você lançaria uma exceção.
            exit(1); // Sai com código de erro
        }

        // Inicializa o serviço com a configuração
        $this->service = new SpellcheckService($this->config);
    }

    /**
     * Executa todos os conjuntos de testes definidos na classe.
     * Imprime um resumo dos resultados ao final.
     */
    public function runAllTests(): void
    {
        echo "\n=== INICIANDO TESTES UNITÁRIOS E DE INTEGRAÇÃO DO SERVIÇO DE CORREÇÃO ORTOGRÁFICA ===\n\n";

        // Executa os conjuntos de testes
        $this->testConfiguration();
        $this->testWordChecking();
        $this->testSuggestions();
        $this->testTextChecking();
        $this->testLanguageSupport();
        $this->testErrorHandling();
        $this->testServiceStats();

        $this->printSummary(); // Imprime o resumo final dos testes
    }

    /**
     * Testa a classe SpellcheckConfig e suas funcionalidades.
     */
    private function testConfiguration(): void
    {
        echo "--- Testando Configuração (SpellcheckConfig) ---\n";

        // Teste 1: Valores padrão
        $this->assert(
            $this->config->getMaxWordLength() === 50,
            'Comprimento máximo de palavra padrão deve ser 50 (SpellcheckConfig::MAX_WORD_LENGTH)'
        );
        $this->assert(
            $this->config->getMinWordLength() === 2,
            'Comprimento mínimo de palavra padrão deve ser 2'
        );
        $this->assert(
            $this->config->getCacheTimeout() === 300, // SpellcheckConfig::CACHE_TTL
            'Timeout do cache padrão deve ser 300 segundos'
        );

        // Teste 2: Idiomas suportados padrão
        $languages = $this->config->getSupportedLanguages();
        $this->assert(
            isset($languages['pt_BR']) && isset($languages['en_US']),
            'Português e Inglês devem estar nos idiomas suportados por padrão'
        );

        // Teste 3: Validação de idioma
        $this->assert(
            $this->config->isLanguageSupported('pt_BR'),
            'Deve reconhecer pt_BR como idioma suportado'
        );
        $this->assert(
            !$this->config->isLanguageSupported('xx_XX'),
            'Deve rejeitar idioma não suportado (xx_XX)'
        );

        // Teste 4: Configuração personalizada no construtor
        $customConfig = new SpellcheckConfig(['max_word_length' => 100, 'cache_enabled' => false]);
        $this->assert(
            $customConfig->getMaxWordLength() === 100,
            'Deve aceitar e aplicar configuração personalizada no construtor (max_word_length)'
        );
        $this->assert(
            $customConfig->isCacheEnabled() === false,
            'Deve aceitar e aplicar configuração personalizada no construtor (cache_enabled)'
        );

        // Teste 5: Setters de configuração
        $this->config->setMaxWordLength(75);
        $this->assert(
            $this->config->getMaxWordLength() === 75,
            'Setter setMaxWordLength deve funcionar'
        );
        $this->config->setCacheEnabled(false);
        $this->assert(
            $this->config->isCacheEnabled() === false,
            'Setter setCacheEnabled deve funcionar'
        );

        // Teste 6: Obter caminho de dicionário
        $dictPath = $this->config->getDictionaryFilePath('pt_BR', 'dictionary');
        $this->assert(
            str_ends_with($dictPath, 'pt_BR.dic'),
            'Deve retornar o caminho correto para o arquivo .dic de pt_BR'
        );
        $affixPath = $this->config->getDictionaryFilePath('en_US', 'affix');
        $this->assert(
            str_ends_with($affixPath, 'en_US.aff'),
            'Deve retornar o caminho correto para o arquivo .aff de en_US'
        );
        $this->assert(
            $this->config->getDictionaryFilePath('non_existent', 'dictionary') === null,
            'Deve retornar null para idioma não configurado em getDictionaryFilePath'
        );

        echo "\n";
    }

    /**
     * Testa as funcionalidades de verificação de palavras do SpellcheckService.
     */
    private function testWordChecking(): void
    {
        echo "--- Testando Verificação de Palavras (SpellcheckService::checkWord) ---\n";

        // Teste 1: Palavra correta em português (assumindo que "teste" está no dicionário pt_BR)
        $result = $this->service->checkWord('teste', 'pt_BR');
        $this->assert(
            $result['success'] === true && $result['is_correct'] === true,
            'Deve reconhecer "teste" como correta em pt_BR'
        );

        // Teste 2: Palavra incorreta em português
        $result = $this->service->checkWord('testandoo', 'pt_BR');
        $this->assert(
            $result['success'] === true && $result['is_correct'] === false,
            'Deve reconhecer "testandoo" como incorreta em pt_BR'
        );

        // Teste 3: Palavra correta em inglês (assumindo "hello" está no dicionário en_US)
        $result = $this->service->checkWord('hello', 'en_US');
        $this->assert(
            $result['success'] === true && $result['is_correct'] === true,
            'Deve reconhecer "hello" como correta em en_US'
        );

        // Teste 4: Palavra incorreta em inglês
        $result = $this->service->checkWord('hallo', 'en_US');
        $this->assert(
            $result['success'] === true && $result['is_correct'] === false,
            'Deve reconhecer "hallo" como incorreta em en_US'
        );

        // Teste 5: Palavra vazia
        $result = $this->service->checkWord('');
        $this->assert(
            $result['success'] === false && str_contains($result['error'], SpellcheckConfig::getErrorMessage('WORD_REQUIRED')),
            'Deve rejeitar palavra vazia com erro WORD_REQUIRED'
        );

        // Teste 6: Palavra muito longa (usando a configuração)
        $longWord = str_repeat('a', $this->config->getMaxWordLength() + 1);
        $result = $this->service->checkWord($longWord);
        $this->assert(
            $result['success'] === false && str_contains($result['error'], SpellcheckConfig::getErrorMessage('WORD_TOO_LONG')),
            'Deve rejeitar palavra muito longa com erro WORD_TOO_LONG'
        );

        // Teste 7: Palavra com caracteres inválidos
        $result = $this->service->checkWord('palavra123', 'pt_BR');
        $this->assert(
            $result['success'] === false && str_contains($result['error'], 'caracteres inválidos'),
            'Deve rejeitar palavra com caracteres inválidos'
        );

        // Teste 8: Idioma não suportado
        $result = $this->service->checkWord('word', 'xx_XX');
        $this->assert(
            $result['success'] === false && str_contains($result['error'], SpellcheckConfig::getErrorMessage('LANGUAGE_NOT_SUPPORTED')),
            'Deve rejeitar idioma não suportado com erro LANGUAGE_NOT_SUPPORTED'
        );

        echo "\n";
    }

    /**
     * Testa as funcionalidades de geração de sugestões do SpellcheckService.
     */
    private function testSuggestions(): void
    {
        echo "--- Testando Sugestões (SpellcheckService::getSuggestions) ---\n";

        // Teste 1: Sugestões para palavra incorreta (esperando "teste" ou similar)
        $result = $this->service->getSuggestions('tsete', 'pt_BR');
        $this->assert(
            $result['success'] === true && is_array($result['suggestions']) && !empty($result['suggestions']),
            'Deve retornar sugestões para "tsete" em pt_BR'
        );
        $this->assert(
            in_array('teste', $result['suggestions']) || in_array('testes', $result['suggestions']),
            'Deve conter "teste" ou "testes" nas sugestões para "tsete"'
        );

        // Teste 2: Limite de sugestões
        $result = $this->service->getSuggestions('tsete', 'pt_BR', 2);
        $this->assert(
            $result['success'] === true && count($result['suggestions']) <= 2,
            'Deve respeitar o limite de 2 sugestões'
        );

        // Teste 3: Palavra que é correta, mas pede sugestões (deve retornar vazio ou poucas)
        $result = $this->service->getSuggestions('casa', 'pt_BR');
        $this->assert(
            $result['success'] === true && (empty($result['suggestions']) || count($result['suggestions']) < 3), // Espera poucas/nenhuma sugestão para palavra correta
            'Não deve retornar muitas sugestões para palavra já correta'
        );

        // Teste 4: Palavra vazia para sugestões
        $result = $this->service->getSuggestions('');
        $this->assert(
            $result['success'] === false && str_contains($result['error'], SpellcheckConfig::getErrorMessage('WORD_REQUIRED')),
            'Deve rejeitar palavra vazia para sugestões com erro WORD_REQUIRED'
        );

        // Teste 5: Idioma não suportado para sugestões
        $result = $this->service->getSuggestions('word', 'xx_XX');
        $this->assert(
            $result['success'] === false && str_contains($result['error'], SpellcheckConfig::getErrorMessage('LANGUAGE_NOT_SUPPORTED')),
            'Deve rejeitar idioma não suportado para sugestões com erro LANGUAGE_NOT_SUPPORTED'
        );

        echo "\n";
    }

    /**
     * Testa as funcionalidades de verificação de texto completo do SpellcheckService.
     */
    private function testTextChecking(): void
    {
        echo "--- Testando Verificação de Texto (SpellcheckService::checkText) ---\n";

        // Teste 1: Texto simples e correto
        $text = "Este é um texto de exemplo e está correto.";
        $result = $this->service->checkText($text, 'pt_BR');
        $this->assert(
            $result['success'] === true && $result['misspelled_count'] === 0 && $result['total_words'] > 0,
            'Deve processar texto correto sem encontrar erros'
        );
        $this->assert(
            $result['accuracy_percentage'] === 100.00,
            'Acurácia deve ser 100% para texto correto'
        );

        // Teste 2: Texto com palavras incorretas
        $text = "Este é um testu com errus.";
        $result = $this->service->checkText($text, 'pt_BR');
        $this->assert(
            $result['success'] === true && $result['misspelled_count'] > 0,
            'Deve identificar palavras incorretas no texto'
        );
        $this->assert(
            isset($result['misspelled_words'][0]['suggestions']) && !empty($result['misspelled_words'][0]['suggestions']),
            'Palavras incorretas devem ter sugestões'
        );
        $this->assert(
            str_contains(json_encode($result), 'erro') || str_contains(json_encode($result), 'erros'), // Verifica se "erro" ou "erros" está nas sugestões
            'Sugestões devem ser relevantes para "errus"'
        );

        // Teste 3: Texto vazio
        $result = $this->service->checkText('');
        $this->assert(
            $result['success'] === false && str_contains($result['error'], SpellcheckConfig::getErrorMessage('TEXT_REQUIRED')),
            'Deve rejeitar texto vazio com erro TEXT_REQUIRED'
        );

        // Teste 4: Texto muito longo (usando a configuração)
        $maxLen = $this->config->getMaxTextLength(); // Pega o valor da config
        $longText = str_repeat('palavra ', intval($maxLen / 8) + 1); // Cria um texto que excede o limite
        $result = $this->service->checkText($longText);
         $this->assert(
            $result['success'] === false && str_contains($result['error'], SpellcheckConfig::getErrorMessage('TEXT_TOO_LONG')),
            'Deve rejeitar texto muito longo com erro TEXT_TOO_LONG'
        );

        // Teste 5: Texto com pontuação e caracteres especiais
        $text = "Olá, mundo! Como você está? Isso é fantástico...!";
        $result = $this->service->checkText($text, 'pt_BR');
        $this->assert(
            $result['success'] === true,
            'Deve processar texto com pontuação e caracteres especiais'
        );

        // Teste 6: Texto com 0 palavras válidas
        $text = "a e i o u"; // Palavras muito curtas, devem ser filtradas pela config->getMinWordLength()
        $result = $this->service->checkText($text, 'pt_BR');
        $this->assert(
            $result['success'] === true && $result['total_words'] === 0 && $result['misspelled_count'] === 0,
            'Deve lidar com texto sem palavras válidas e retornar total_words = 0'
        );
        $this->assert(
            $result['accuracy_percentage'] === 100.00,
            'Acurácia deve ser 100% para texto sem palavras válidas'
        );


        echo "\n";
    }

    /**
     * Testa a funcionalidade de obtenção de idiomas suportados.
     */
    private function testLanguageSupport(): void
    {
        echo "--- Testando Suporte a Idiomas (SpellcheckService::getSupportedLanguages) ---\n";

        // Teste 1: Obter idiomas suportados
        $result = $this->service->getSupportedLanguages();
        $this->assert(
            $result['success'] === true && isset($result['languages']) && is_array($result['languages']),
            'Deve retornar um array de idiomas suportados'
        );
        $this->assert(
            !empty($result['languages']),
            'A lista de idiomas suportados não deve estar vazia'
        );

        // Teste 2: Verificar a estrutura dos dados do idioma (ex: 'name', 'dictionary_file')
        $languages = $result['languages'];
        $this->assert(
            isset($languages['pt_BR']['name']) && isset($languages['pt_BR']['dictionary_file']),
            'Cada idioma deve ter 'name' e 'dictionary_file''
        );

        // Teste 3: Adicionar um novo idioma via config e verificar se o serviço o reconhece
        // Este teste depende do SpellcheckConfig, mas verifica se o Service reflete a mudança.
        $this->config->addSupportedLanguage('de_DE', [
            'name' => 'Deutsch (Deutschland)',
            'dictionary_file' => 'de_DE.dic',
            'affix_file' => 'de_DE.aff',
            'encoding' => 'UTF-8'
        ]);
        $updatedLanguages = $this->service->getSupportedLanguages();
        $this->assert(
            isset($updatedLanguages['languages']['de_DE']),
            'Deve permitir adicionar novo idioma via config e o serviço deve reconhecê-lo'
        );

        echo "\n";
    }

    /**
     * Testa o tratamento de erros em várias camadas do serviço e configuração.
     */
    private function testErrorHandling(): void
    {
        echo "--- Testando Tratamento de Erros ---\n";

        // Teste 1: Tentativa de definir comprimento máximo de palavra inválido na configuração
        try {
            $this->config->setMaxWordLength(0);
            $this->assert(false, 'Setter setMaxWordLength deve lançar InvalidArgumentException para valor 0');
        } catch (InvalidArgumentException $e) {
            $this->assert(true, 'Setter setMaxWordLength lançou InvalidArgumentException para valor 0');
        }

        // Teste 2: Tentativa de adicionar configuração de idioma incompleta
        try {
            $this->config->addSupportedLanguage('invalid', ['name' => 'Invalid']);
            $this->assert(false, 'addSupportedLanguage deve lançar InvalidArgumentException para config incompleta');
        } catch (InvalidArgumentException $e) {
            $this->assert(true, 'addSupportedLanguage lançou InvalidArgumentException para config incompleta');
        }

        // Teste 3: Validação da configuração (seus erros podem variar se os dicionários não existirem)
        $errors = $this->config->validate();
        $this->assert(
            is_array($errors),
            'Validação de configuração deve retornar um array de erros'
        );
        // Exemplo de como verificar um erro específico se esperado:
        // $this->assert(
        //     in_array("Diretório de dicionários não encontrado ou não acessível: {$this->config->getDictionaryPath()}", $errors),
        //     'Deve reportar erro se diretório de dicionários não for acessível'
        // );

        // Teste 4: Tentar carregar um dicionário para um idioma não suportado (já coberto em testWordChecking, mas reitera)
        $result = $this->service->checkWord('word', 'non_existent_lang');
        $this->assert(
            $result['success'] === false && str_contains($result['error'], SpellcheckConfig::getErrorMessage('LANGUAGE_NOT_SUPPORTED')),
            'checkWord deve retornar erro para idioma não suportado'
        );

        echo "\n";
    }

    /**
     * Testa a funcionalidade de obtenção de estatísticas do serviço.
     */
    private function testServiceStats(): void
    {
        echo "--- Testando Estatísticas do Serviço (SpellcheckService::getServiceStats) ---\n";

        // Teste 1: Obter estatísticas
        $stats = $this->service->getServiceStats();
        $this->assert(
            isset($stats['service_name']) && $stats['service_name'] === 'SpellcheckService',
            'Deve retornar o nome do serviço correto'
        );
        $this->assert(
            isset($stats['api_version']) && $stats['api_version'] === SpellcheckConfig::API_VERSION,
            'Deve retornar a versão da API da configuração'
        );
        $this->assert(
            isset($stats['supported_languages_count']) && $stats['supported_languages_count'] >= 2, // pt_BR e en_US
            'Deve retornar a contagem correta de idiomas suportados'
        );
        $this->assert(
            isset($stats['max_word_length']) && $stats['max_word_length'] === $this->config->getMaxWordLength(),
            'Deve retornar o comprimento máximo de palavra da configuração'
        );
        $this->assert(
            isset($stats['dictionary_base_path']) && !empty($stats['dictionary_base_path']),
            'Deve retornar o caminho base dos dicionários'
        );

        echo "\n";
    }

    /**
     * Executa uma asserção (verificação de teste) e registra o resultado.
     * Imprime visualmente se o teste passou ou falhou.
     *
     * @param bool $condition A condição booleana que deve ser verdadeira para o teste passar.
     * @param string $message A mensagem descritiva do teste.
     */
    private function assert(bool $condition, string $message): void
    {
        $this->totalTests++; // Incrementa o contador total de testes

        if ($condition) {
            $this->passedTests++; // Incrementa o contador de testes aprovados
            echo "  ✓ {$message}\n"; // Exibe um "tick" verde para sucesso
            $this->testResults[] = ['status' => 'PASS', 'message' => $message]; // Registra o resultado
        } else {
            echo "  ✗ {$message}\n"; // Exibe um "X" vermelho para falha
            $this->testResults[] = ['status' => 'FAIL', 'message' => $message]; // Registra o resultado
        }
    }

    /**
     * Imprime um resumo final dos testes executados, incluindo contagens e taxa de sucesso.
     * Lista os testes que falharam, se houver.
     */
    private function printSummary(): void
    {
        echo "\n--- RESUMO DOS TESTES UNITÁRIOS E DE INTEGRAÇÃO ---\n";
        echo "Total de testes: {$this->totalTests}\n";
        echo "Testes aprovados: {$this->passedTests}\n";
        echo "Testes falharam: " . ($this->totalTests - $this->passedTests) . "\n";

        $successRate = ($this->totalTests > 0) ? round(($this->passedTests / $this->totalTests) * 100, 2) : 0;
        echo "Taxa de sucesso: {$successRate}%\n";

        if ($this->passedTests === $this->totalTests) {
            echo "\n🎉 TODOS OS TESTES PASSARAM! 🎉\n";
        } else {
            echo "\n⚠️ ALGUNS TESTES FALHARAM ⚠️\n";
            echo "\nDetalhes dos testes que falharam:\n";

            foreach ($this->testResults as $result) {
                if ($result['status'] === 'FAIL') {
                    echo "- {$result['message']}\n"; // Imprime a mensagem dos testes que falharam
                }
            }
        }

        echo "\n=== FIM DOS TESTES UNITÁRIOS E DE INTEGRAÇÃO ===\n";
    }
}

---

/**
 * Função para executar testes de integração na API HTTP.
 *
 * Esta função tenta fazer requisições HTTP reais para os endpoints da SpellcheckAPI
 * e verifica as respostas. Requer que o servidor web e a SpellcheckAPI estejam rodando.
 */
function runApiIntegrationTests(): void
{
    echo "\n=== INICIANDO TESTES DE INTEGRAÇÃO DA API HTTP ===\n\n";

    // Define a URL base da sua API.
    // Ajuste 'api.php' para o nome real do seu arquivo da API (spellcheck_api.php, index.php, etc.)
    // E certifique-se de que o dirname($_SERVER['PHP_SELF']) está correto para o seu ambiente.
    $baseUrl = 'http://localhost' . dirname($_SERVER['PHP_SELF']) . '/spellcheck_api.php';
    echo "URL Base da API: {$baseUrl}\n\n";

    $apiTestResults = [];
    $totalApiTests = 0;
    $passedApiTests = 0;

    /**
     * Função auxiliar para fazer requisições HTTP e processar a resposta.
     * @param string $url O endpoint completo.
     * @param string $method O método HTTP (GET, POST).
     * @param array $data Dados para requisições POST.
     * @return array|null O array decodificado da resposta JSON, ou null em caso de falha.
     */
    $makeApiRequest = function (string $url, string $method = 'GET', array $data = []): ?array {
        $options = [
            'http' => [
                'method' => $method,
                'header' => 'Content-Type: application/json',
                'ignore_errors' => true // Importante para capturar códigos de erro HTTP
            ]
        ];
        if ($method === 'POST' && !empty($data)) {
            $options['http']['content'] = json_encode($data);
        }

        $context = stream_context_create($options);
        $response = @file_get_contents($url, false, $context); // Usamos @ para suprimir warnings de conexão

        if ($response === false) {
            return null; // Erro de conexão ou similar
        }

        // Analisa os cabeçalhos para obter o código de status HTTP
        preg_match('/HTTP\/\d\.\d\s*(\d+)/', $http_response_header[0], $matches);
        $statusCode = (int)$matches[1];

        $decodedResponse = json_decode($response, true);

        // Adiciona o status code à resposta para facilitar a verificação
        if (is_array($decodedResponse)) {
            $decodedResponse['http_status_code'] = $statusCode;
        }

        return $decodedResponse;
    };

    /**
     * Asserção para testes de API
     * @param bool $condition
     * @param string $message
     * @param array $results
     * @param int $total
     * @param int $passed
     */
    $assertApi = function (bool $condition, string $message) use (&$apiTestResults, &$totalApiTests, &$passedApiTests) {
        $totalApiTests++;
        if ($condition) {
            $passedApiTests++;
            echo "  ✓ {$message}\n";
            $apiTestResults[] = ['status' => 'PASS', 'message' => $message];
        } else {
            echo "  ✗ {$message}\n";
            $apiTestResults[] = ['status' => 'FAIL', 'message' => $message];
        }
    };

    // --- Teste 1: Health check ---
    echo "Teste 1: Endpoint /health\n";
    $response = $makeApiRequest($baseUrl . '/health', 'GET');
    $assertApi(
        $response && $response['success'] === true && $response['status'] === 'healthy' && $response['http_status_code'] === 200,
        'API health check deve retornar status healthy (200 OK)'
    );

    // --- Teste 2: check-word - palavra correta ---
    echo "Teste 2: Endpoint /check-word - palavra correta 'teste'\n";
    $response = $makeApiRequest($baseUrl . '/check-word', 'POST', ['word' => 'teste', 'language' => 'pt_BR']);
    $assertApi(
        $response && $response['success'] === true && $response['is_correct'] === true && $response['http_status_code'] === 200,
        'check-word para "teste" deve retornar correto (200 OK)'
    );

    // --- Teste 3: check-word - palavra incorreta ---
    echo "Teste 3: Endpoint /check-word - palavra incorreta 'errro'\n";
    $response = $makeApiRequest($baseUrl . '/check-word', 'POST', ['word' => 'errro', 'language' => 'pt_BR']);
    $assertApi(
        $response && $response['success'] === true && $response['is_correct'] === false && $response['http_status_code'] === 200,
        'check-word para "errro" deve retornar incorreto (200 OK)'
    );

    // --- Teste 4: check-word - palavra vazia (erro) ---
    echo "Teste 4: Endpoint /check-word - palavra vazia\n";
    $response = $makeApiRequest($baseUrl . '/check-word', 'POST', ['word' => '', 'language' => 'pt_BR']);
    $assertApi(
        $response && $response['success'] === false && $response['http_status_code'] === 400 && str_contains($response['error'], 'obrigatório'),
        'check-word para palavra vazia deve retornar erro 400'
    );

    // --- Teste 5: suggestions - palavra incorreta ---
    echo "Teste 5: Endpoint /suggestions - para 'errro'\n";
    $response = $makeApiRequest($baseUrl . '/suggestions', 'POST', ['word' => 'errro', 'language' => 'pt_BR']);
    $assertApi(
        $response && $response['success'] === true && !empty($response['suggestions']) && $response['http_status_code'] === 200,
        'suggestions para "errro" deve retornar sugestões (200 OK)'
    );

    // --- Teste 6: check-text - texto correto ---
    echo "Teste 6: Endpoint /check-text - texto correto\n";
    $response = $makeApiRequest($baseUrl . '/check-text', 'POST', ['text' => 'Um texto correto para testar', 'language' => 'pt_BR']);
    $assertApi(
        $response && $response['success'] === true && $response['misspelled_count'] === 0 && $response['http_status_code'] === 200,
        'check-text para texto correto deve retornar 0 erros (200 OK)'
    );

    // --- Teste 7: check-text - texto com erro ---
    echo "Teste 7: Endpoint /check-text - texto com 'errro'\n";
    $response = $makeApiRequest($baseUrl . '/check-text', 'POST', ['text' => 'Um texto com um errro', 'language' => 'pt_BR']);
    $assertApi(
        $response && $response['success'] === true && $response['misspelled_count'] > 0 && $response['http_status_code'] === 200,
        'check-text para texto com "errro" deve retornar erros (200 OK)'
    );

    // --- Teste 8: languages ---
    echo "Teste 8: Endpoint /languages\n";
    $response = $makeApiRequest($baseUrl . '/languages', 'GET');
    $assertApi(
        $response && $response['success'] === true && !empty($response['languages']) && $response['http_status_code'] === 200,
        'languages deve retornar lista de idiomas (200 OK)'
    );

    // --- Teste 9: stats ---
    echo "Teste 9: Endpoint /stats\n";
    $response = $makeApiRequest($baseUrl . '/stats', 'GET');
    $assertApi(
        $response && $response['success'] === true && isset($response['service_name']) && $response['http_status_code'] === 200,
        'stats deve retornar estatísticas do serviço (200 OK)'
    );

    // --- Teste 10: Endpoint não encontrado ---
    echo "Teste 10: Endpoint não existente /non-existent-endpoint\n";
    $response = $makeApiRequest($baseUrl . '/non-existent-endpoint', 'GET');
    $assertApi(
        $response && $response['success'] === false && $response['http_status_code'] === 404 && str_contains($response['error'], 'não encontrado'),
        'Endpoint não existente deve retornar erro 404'
    );

    // --- Resumo dos testes de API ---
    echo "\n--- RESUMO DOS TESTES DE INTEGRAÇÃO DA API HTTP ---\n";
    echo "Total de testes de API: {$totalApiTests}\n";
    echo "Testes de API aprovados: {$passedApiTests}\n";
    echo "Testes de API falharam: " . ($totalApiTests - $passedApiTests) . "\n";
    $successRateApi = ($totalApiTests > 0) ? round(($passedApiTests / $totalApiTests) * 100, 2) : 0;
    echo "Taxa de sucesso da API: {$successRateApi}%\n";

    if ($passedApiTests === $totalApiTests) {
        echo "\n✅ TODOS OS TESTES DE INTEGRAÇÃO DA API PASSARAM! ✅\n";
    } else {
        echo "\n❌ ALGUNS TESTES DE INTEGRAÇÃO DA API FALHARAM ❌\n";
        echo "\nDetalhes dos testes de API que falharam:\n";
        foreach ($apiTestResults as $result) {
            if ($result['status'] === 'FAIL') {
                echo "- {$result['message']}\n";
            }
        }
    }
    echo "\n=== FIM DOS TESTES DE INTEGRAÇÃO DA API HTTP ===\n";
}

// --- Ponto de Entrada do Script de Teste ---
// Executa os testes se o arquivo for chamado diretamente pela linha de comando.
if (php_sapi_name() === 'cli') { // Verifica se está sendo executado via CLI
    try {
        $tester = new SpellcheckTest(); // Instancia a classe de testes unitários/serviço
        $tester->runAllTests();       // Executa os testes unitários/serviço

        // Chama os testes de integração da API se for executado via CLI
        // Nota: Para que os testes de API funcionem, o servidor web DEVE estar rodando
        // e a API deve estar acessível na URL configurada.
        runApiIntegrationTests();

    } catch (Exception $e) {
        // Captura exceções gerais para uma saída amigável na CLI
        echo "\nERRO FATAL DURANTE OS TESTES: " . $e->getMessage() . "\n";
        echo "Trace:\n" . $e->getTraceAsString() . "\n";
    }
} elseif (isset($_SERVER['HTTP_HOST'])) { // Executa os testes via navegador
    // Se acessado via navegador, executa apenas os testes unitários/serviço
    // Os testes de API HTTP devem ser feitos preferencialmente via CLI com um servidor web rodando
    // ou por ferramentas como `curl` conforme a documentação da API.
    echo "<pre>"; // Formata a saída para o navegador
    try {
        $tester = new SpellcheckTest();
        $tester->runAllTests();
        echo "\nPara testar a API HTTP, por favor, use a linha de comando (php " . basename(__FILE__) . ") ou ferramentas como curl/Postman.\n";
    } catch (Exception $e) {
        echo "\nERRO FATAL: " . $e->getMessage() . "\n";
        echo "Trace:\n" . $e->getTraceAsString() . "\n";
    }
    echo "</pre>";
}

/**
 * Instruções de uso:
 *
 * 1. Via linha de comando (RECOMENDADO para testes completos, incluindo API HTTP):
 * Certifique-se de que seu servidor web (Apache/Nginx) está rodando e a SpellcheckAPI está acessível.
 * php test_spellcheck.php
 *
 * 2. Via navegador (para testes unitários/serviço apenas, sem testes de API HTTP):
 * Acesse no seu navegador: http://localhost/caminho/para/test_spellcheck.php
 *
 * 3. Incluindo em outros arquivos (para uso programático):
 * require_once 'test_spellcheck.php';
 * $tester = new SpellcheckTest();
 * $tester->runAllTests();
 */