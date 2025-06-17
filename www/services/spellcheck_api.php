<?php

/**
 * API REST para Serviço de Correção Ortográfica
 *
 * Este arquivo fornece endpoints REST para acessar as funcionalidades
 * do serviço de correção ortográfica.
 */

// --- Configurações de Cabeçalho da API ---
// Define o tipo de conteúdo como JSON com charset UTF-8
header('Content-Type: application/json; charset=utf-8');
// Permite requisições de qualquer origem (útil para desenvolvimento, ajustar em produção)
header('Access-Control-Allow-Origin: *');
// Métodos HTTP permitidos para as requisições
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
// Cabeçalhos que podem ser enviados na requisição
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

// --- Responde a requisições OPTIONS (CORS preflight) ---
// Este bloco é crucial para que navegadores possam fazer requisições cross-origin.
// Ele responde a uma requisição OPTIONS antes da requisição real.
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200); // Retorna OK (200) para a requisição preflight
    exit(); // Encerra a execução para não processar a requisição real
}

// --- Inclusão das classes necessárias ---
// Certifique-se de que os caminhos para suas classes SpellcheckConfig e SpellcheckService estão corretos
require_once __DIR__ . '/SpellcheckConfig.php';
require_once __DIR__ . '/SpellcheckService.php';

/**
 * Classe para gerenciar a API REST do Serviço de Correção Ortográfica.
 *
 * Esta classe é responsável por:
 * - Inicializar o SpellcheckService com suas configurações.
 * - Roteamento das requisições para os métodos apropriados do serviço.
 * - Tratamento de parâmetros de entrada.
 * - Padronização das respostas de sucesso e erro em formato JSON.
 */
class SpellcheckAPI
{
    private SpellcheckService $service; // Instância do serviço de verificação ortográfica
    private array $allowedMethods = ['GET', 'POST']; // Métodos HTTP permitidos para os endpoints
    private array $endpoints = [ // Mapeamento de endpoints da URL para métodos da classe
        'check-word' => 'checkWord',
        'suggestions' => 'getSuggestions',
        'check-text' => 'checkText',
        'languages' => 'getLanguages',
        'stats' => 'getStats',
        'health' => 'healthCheck' // Endpoint para verificar a saúde da API
    ];

    /**
     * Construtor da classe SpellcheckAPI.
     * Inicializa a configuração e o serviço de verificação ortográfica.
     * Em caso de falha na inicialização do serviço, retorna um erro 500.
     */
    public function __construct()
    {
        try {
            // Cria uma nova instância de SpellcheckConfig, que carrega as configurações padrão e permite sobrescritas.
            $config = new SpellcheckConfig();

            // Valida a configuração inicial. Se houver erros, lança uma exceção.
            $configErrors = $config->validate();
            if (!empty($configErrors)) {
                // Junta os erros para uma mensagem mais informativa
                throw new Exception('Configuração inválida: ' . implode('; ', $configErrors));
            }

            // Inicializa o SpellcheckService com a configuração validada.
            $this->service = new SpellcheckService($config);
        } catch (Exception $e) {
            // Em caso de erro na inicialização, envia uma resposta de erro 500 (Internal Server Error)
            $this->sendError('Erro ao inicializar o serviço: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Processa a requisição HTTP de entrada.
     *
     * Este método determina o método HTTP e o endpoint solicitado,
     * e então chama a função apropriada para lidar com a requisição.
     * Lida com exceções e envia respostas de erro padronizadas.
     */
    public function handleRequest(): void
    {
        try {
            // Verifica se o método HTTP da requisição é permitido.
            if (!in_array($_SERVER['REQUEST_METHOD'], $this->allowedMethods)) {
                $this->sendError('Método não permitido', 405); // 405 Method Not Allowed
                return;
            }

            // Extrai o endpoint da URL da requisição.
            $endpoint = $this->getEndpoint();

            // Verifica se o endpoint solicitado existe no mapeamento de endpoints.
            if (!isset($this->endpoints[$endpoint])) {
                $this->sendError('Endpoint não encontrado', 404); // 404 Not Found
                return;
            }

            // Obtém o nome do método da classe que corresponde ao endpoint.
            $method = $this->endpoints[$endpoint];
            // Chama o método da classe SpellcheckAPI.
            $this->$method();

        } catch (Exception $e) {
            // Captura qualquer exceção não tratada e envia uma resposta de erro 500.
            error_log("SpellcheckAPI Fatal Error: " . $e->getMessage() . " on line " . $e->getLine() . " in " . $e->getFile());
            $this->sendError('Erro interno do servidor: ' . $e->getMessage(), 500); // 500 Internal Server Error
        }
    }

    /**
     * Extrai o endpoint da URL da requisição.
     *
     * Assume que o endpoint é o último segmento do caminho da URL.
     * Ex: para /api/v1/health, o endpoint seria 'health'.
     *
     * @return string O nome do endpoint. Retorna 'health' por padrão se nenhum endpoint for especificado.
     */
    private function getEndpoint(): string
    {
        // Pega apenas o caminho da URL (ex: /path/to/api.php/check-word)
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        // Remove barras extras e divide o caminho em segmentos
        $segments = array_filter(explode('/', trim($path, '/')));

        // Se o último segmento for o nome do arquivo PHP (ex: api.php), remove-o
        $scriptName = basename($_SERVER['PHP_SELF']);
        if (end($segments) === $scriptName) {
            array_pop($segments);
        }

        // Retorna o último segmento como o endpoint, ou 'health' se nenhum for encontrado
        return end($segments) ?: 'health';
    }

    /**
     * Obtém os parâmetros da requisição HTTP.
     *
     * Suporta parâmetros enviados via GET, POST (form-data) e POST (JSON raw body).
     *
     * @return array Um array associativo contendo todos os parâmetros da requisição.
     */
    private function getRequestParams(): array
    {
        $params = [];

        // Para requisições POST, tenta ler o body como JSON primeiro
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = file_get_contents('php://input');
            $jsonData = json_decode($input, true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($jsonData)) {
                $params = $jsonData; // JSON válido
            } else {
                $params = $_POST; // Fallback para form-data (application/x-www-form-urlencoded ou multipart/form-data)
            }
        } else {
            $params = $_GET; // Para requisições GET, usa $_GET
        }

        return $params;
    }

    /**
     * --- Métodos de Endpoint ---
     * Cada método abaixo corresponde a um endpoint da API e chama o serviço apropriado.
     */

    /**
     * Endpoint para verificar a correção de uma palavra.
     *
     * Requer o parâmetro 'word'. O parâmetro 'language' é opcional.
     * Responde com o resultado da verificação ortográfica.
     */
    private function checkWord(): void
    {
        $params = $this->getRequestParams();

        // Validação de parâmetro obrigatório 'word'
        if (!isset($params['word']) || empty(trim($params['word']))) {
            $this->sendError(SpellcheckConfig::getErrorMessage('WORD_REQUIRED'), 400); // 400 Bad Request
            return;
        }

        $word = trim($params['word']);
        // Usa o código padrão da configuração se o idioma não for fornecido
        $language = $params['language'] ?? SpellcheckConfig::DEFAULT_LANGUAGE_CODE;

        // Chama o método do serviço e envia a resposta
        $result = $this->service->checkWord($word, $language);
        // O serviço já retorna um array com 'success', 'error' etc.
        // Se houver erro no serviço, sendResponse ou sendError serão chamados com base nisso.
        if (isset($result['success']) && $result['success'] === false) {
             $this->sendError($result['error'] ?? 'Erro desconhecido na verificação da palavra.', 400);
        } else {
            $this->sendResponse($result);
        }
    }

    /**
     * Endpoint para obter sugestões para uma palavra incorreta.
     *
     * Requer o parâmetro 'word'. 'language' e 'limit' são opcionais.
     * Responde com uma lista de sugestões.
     */
    private function getSuggestions(): void
    {
        $params = $this->getRequestParams();

        // Validação de parâmetro obrigatório 'word'
        if (!isset($params['word']) || empty(trim($params['word']))) {
            $this->sendError(SpellcheckConfig::getErrorMessage('WORD_REQUIRED'), 400);
            return;
        }

        $word = trim($params['word']);
        $language = $params['language'] ?? SpellcheckConfig::DEFAULT_LANGUAGE_CODE;
        // Limite padrão vindo da configuração, ou sobrescrito pelo parâmetro (com validação)
        $limit = isset($params['limit']) ? (int) $params['limit'] : SpellcheckConfig::MAX_SUGGESTIONS;

        // Valida o limite de sugestões
        if ($limit < 1 || $limit > SpellcheckConfig::MAX_SUGGESTIONS * 2) { // Ex: limite máximo da config * 2
            $this->sendError('O limite de sugestões deve estar entre 1 e ' . (SpellcheckConfig::MAX_SUGGESTIONS * 2), 400);
            return;
        }

        $result = $this->service->getSuggestions($word, $language, $limit);
        if (isset($result['success']) && $result['success'] === false) {
             $this->sendError($result['error'] ?? 'Erro desconhecido na obtenção de sugestões.', 400);
        } else {
            $this->sendResponse($result);
        }
    }

    /**
     * Endpoint para verificar um texto completo em busca de erros ortográficos.
     *
     * Requer o parâmetro 'text'. O parâmetro 'language' é opcional.
     * Responde com uma lista de palavras incorretas e suas sugestões.
     */
    private function checkText(): void
    {
        $params = $this->getRequestParams();

        // Validação de parâmetro obrigatório 'text'
        if (!isset($params['text']) || empty(trim($params['text']))) {
            $this->sendError(SpellcheckConfig::getErrorMessage('TEXT_REQUIRED'), 400);
            return;
        }

        $text = trim($params['text']);
        $language = $params['language'] ?? SpellcheckConfig::DEFAULT_LANGUAGE_CODE;

        // Validação do tamanho do texto na API, usando a configuração
        if (mb_strlen($text, 'UTF-8') > $this->service->getServiceStats()['max_text_length']) {
            $this->sendError(SpellcheckConfig::getErrorMessage('TEXT_TOO_LONG') . ' (máximo: ' . $this->service->getServiceStats()['max_text_length'] . ' caracteres)', 400);
            return;
        }

        $result = $this->service->checkText($text, $language);
        if (isset($result['success']) && $result['success'] === false) {
             $this->sendError($result['error'] ?? 'Erro desconhecido na verificação do texto.', 400);
        } else {
            $this->sendResponse($result);
        }
    }

    /**
     * Endpoint para obter a lista de idiomas suportados pelo serviço.
     *
     * Não requer parâmetros.
     * Responde com um array de idiomas suportados.
     */
    private function getLanguages(): void
    {
        $result = $this->service->getSupportedLanguages();
        $this->sendResponse($result);
    }

    /**
     * Endpoint para obter estatísticas e informações sobre o serviço de verificação ortográfica.
     *
     * Não requer parâmetros.
     * Responde com detalhes sobre a versão, configurações, etc.
     */
    private function getStats(): void
    {
        $result = $this->service->getServiceStats();
        $this->sendResponse($result);
    }

    /**
     * Endpoint de verificação de saúde da API.
     *
     * Um endpoint simples para verificar se a API está operacional.
     * Responde com status 'healthy'.
     */
    private function healthCheck(): void
    {
        $result = [
            'success' => true,
            'status' => 'healthy',
            'timestamp' => date('Y-m-d H:i:s'),
            'api_version' => SpellcheckConfig::API_VERSION,
            'endpoints' => array_keys($this->endpoints) // Lista todos os endpoints disponíveis
        ];

        $this->sendResponse($result);
    }

    /**
     * Envia uma resposta de sucesso em formato JSON.
     *
     * Define o código de status HTTP como 200 (OK) e imprime os dados em JSON.
     *
     * @param array $data O array de dados a ser enviado como resposta.
     */
    private function sendResponse(array $data): void
    {
        http_response_code(200);
        // Usa JSON_UNESCAPED_UNICODE para caracteres acentuados e JSON_PRETTY_PRINT para legibilidade
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit(); // Encerra a execução após enviar a resposta
    }

    /**
     * Envia uma resposta de erro em formato JSON.
     *
     * Define o código de status HTTP apropriado e imprime a mensagem de erro em JSON.
     *
     * @param string $message A mensagem de erro.
     * @param int $code O código de status HTTP (padrão: 400 Bad Request).
     */
    private function sendError(string $message, int $code = 400): void
    {
        http_response_code($code);

        $error = [
            'success' => false,
            'error' => $message,
            'code' => $code,
            'timestamp' => date('Y-m-d H:i:s')
        ];

        echo json_encode($error, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit(); // Encerra a execução após enviar o erro
    }
}

// --- Ponto de Entrada da API ---
// Instancia e executa a classe da API para lidar com a requisição.
try {
    $api = new SpellcheckAPI();
    $api->handleRequest();
} catch (Exception $e) {
    // Captura exceções fatais que podem ocorrer antes ou fora do handleRequest
    http_response_code(500);
    error_log("SpellcheckAPI Global Fatal Error: " . $e->getMessage() . " on line " . $e->getLine() . " in " . $e->getFile());
    echo json_encode([
        'success' => false,
        'error' => 'Erro fatal no servidor: ' . $e->getMessage(),
        'code' => 500,
        'timestamp' => date('Y-m-d H:i:s')
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}

// --- Documentação da API ---
// Esta seção serve como referência rápida para os endpoints e exemplos de uso.
/**
 * Documentação da API:
 *
 * Base URL (exemplo): http://localhost/seu_diretorio/api.php
 * (Altere 'api.php' para o nome real do seu arquivo, ex: 'spellcheck_api.php')
 *
 * ENDPOINTS:
 *
 * 1. GET /health
 * - Descrição: Verifica se a API está funcionando corretamente e retorna informações básicas.
 * - Resposta de exemplo: {"success": true, "status": "healthy", ...}
 *
 * 2. POST /check-word
 * - Descrição: Verifica a correção ortográfica de uma única palavra.
 * - Parâmetros (JSON Body):
 * - `word` (string, obrigatório): A palavra a ser verificada.
 * - `language` (string, opcional): O código do idioma (padrão: `pt_BR`).
 * - Resposta de exemplo: {"success": true, "word": "exemplo", "is_correct": true, ...}
 *
 * 3. POST /suggestions
 * - Descrição: Obtém sugestões de correção para uma palavra.
 * - Parâmetros (JSON Body):
 * - `word` (string, obrigatório): A palavra para a qual obter sugestões.
 * - `language` (string, opcional): O código do idioma (padrão: `pt_BR`).
 * - `limit` (int, opcional): Número máximo de sugestões a retornar (padrão: 10, máximo: 20).
 * - Resposta de exemplo: {"success": true, "word": "errro", "suggestions": ["erro", "euros"], ...}
 *
 * 4. POST /check-text
 * - Descrição: Verifica um texto completo em busca de palavras incorretas e retorna sugestões.
 * - Parâmetros (JSON Body):
 * - `text` (string, obrigatório): O texto a ser verificado.
 * - `language` (string, opcional): O código do idioma (padrão: `pt_BR`).
 * - Resposta de exemplo: {"success": true, "total_words": 5, "misspelled_count": 1, "misspelled_words": [{"word": "errado", "suggestions": ["certo"]}], ...}
 *
 * 5. GET /languages
 * - Descrição: Retorna a lista de todos os idiomas suportados pelo serviço.
 * - Resposta de exemplo: {"success": true, "languages": {"pt_BR": {"name": "Português (Brasil)", ...}}}
 *
 * 6. GET /stats
 * - Descrição: Retorna estatísticas e informações detalhadas sobre o serviço de verificação ortográfica.
 * - Resposta de exemplo: {"service_name": "SpellcheckService", "api_version": "1.0", ...}
 *
 * EXEMPLOS DE USO (utilizando `curl`):
 *
 * curl -X GET "http://localhost/seu_diretorio/api.php/health"
 *
 * curl -X POST -H "Content-Type: application/json" \
 * -d '{"word":"casa", "language":"pt_BR"}' \
 * "http://localhost/seu_diretorio/api.php/check-word"
 *
 * curl -X POST -H "Content-Type: application/json" \
 * -d '{"word":"livrro", "language":"pt_BR", "limit":3}' \
 * "http://localhost/seu_diretorio/api.php/suggestions"
 *
 * curl -X POST -H "Content-Type: application/json" \
 * -d '{"text":"Onde está o meo livrro?", "language":"pt_BR"}' \
 * "http://localhost/seu_diretorio/api.php/check-text"
 *
 * curl -X GET "http://localhost/seu_diretorio/api.php/languages"
 *
 * curl -X GET "http://localhost/seu_diretorio/api.php/stats"
 */