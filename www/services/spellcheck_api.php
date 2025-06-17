<?php

/**
 * API REST para Serviço de Correção Ortográfica
 * 
 * Este arquivo fornece endpoints REST para acessar as funcionalidades
 * do serviço de correção ortográfica.
 */

// Configurações de cabeçalho para API
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Responde a requisições OPTIONS (CORS preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Inclui as classes necessárias
require_once __DIR__ . '/SpellcheckConfig.php';
require_once __DIR__ . '/SpellcheckService.php';

/**
 * Classe para gerenciar a API REST
 */
class SpellcheckAPI
{
    private $service;
    private $allowedMethods = ['GET', 'POST'];
    private $endpoints = [
        'check-word' => 'checkWord',
        'suggestions' => 'getSuggestions', 
        'check-text' => 'checkText',
        'languages' => 'getLanguages',
        'stats' => 'getStats',
        'health' => 'healthCheck'
    ];
    
    public function __construct()
    {
        try {
            $config = new SpellcheckConfig();
            $this->service = new SpellcheckService($config);
        } catch (Exception $e) {
            $this->sendError('Erro ao inicializar serviço: ' . $e->getMessage(), 500);
        }
    }
    
    /**
     * Processa a requisição da API
     */
    public function handleRequest(): void
    {
        try {
            // Verifica método HTTP
            if (!in_array($_SERVER['REQUEST_METHOD'], $this->allowedMethods)) {
                $this->sendError('Método não permitido', 405);
                return;
            }
            
            // Obtém o endpoint da URL
            $endpoint = $this->getEndpoint();
            
            // Verifica se o endpoint existe
            if (!isset($this->endpoints[$endpoint])) {
                $this->sendError('Endpoint não encontrado', 404);
                return;
            }
            
            // Chama o método correspondente
            $method = $this->endpoints[$endpoint];
            $this->$method();
            
        } catch (Exception $e) {
            $this->sendError('Erro interno do servidor: ' . $e->getMessage(), 500);
        }
    }
    
    /**
     * Extrai o endpoint da URL
     * 
     * @return string
     */
    private function getEndpoint(): string
    {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $segments = explode('/', trim($path, '/'));
        
        // Assume que o endpoint é o último segmento
        return end($segments) ?: 'health';
    }
    
    /**
     * Obtém parâmetros da requisição
     * 
     * @return array
     */
    private function getRequestParams(): array
    {
        $params = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = file_get_contents('php://input');
            $jsonData = json_decode($input, true);
            
            if ($jsonData !== null) {
                $params = $jsonData;
            } else {
                $params = $_POST;
            }
        } else {
            $params = $_GET;
        }
        
        return $params;
    }
    
    /**
     * Endpoint para verificar uma palavra
     */
    private function checkWord(): void
    {
        $params = $this->getRequestParams();
        
        if (!isset($params['word']) || empty(trim($params['word']))) {
            $this->sendError('Parâmetro "word" é obrigatório', 400);
            return;
        }
        
        $word = trim($params['word']);
        $language = $params['language'] ?? 'pt_BR';
        
        $result = $this->service->checkWord($word, $language);
        $this->sendResponse($result);
    }
    
    /**
     * Endpoint para obter sugestões
     */
    private function getSuggestions(): void
    {
        $params = $this->getRequestParams();
        
        if (!isset($params['word']) || empty(trim($params['word']))) {
            $this->sendError('Parâmetro "word" é obrigatório', 400);
            return;
        }
        
        $word = trim($params['word']);
        $language = $params['language'] ?? 'pt_BR';
        $limit = isset($params['limit']) ? (int) $params['limit'] : 5;
        
        // Valida limite
        if ($limit < 1 || $limit > 20) {
            $this->sendError('Limite deve estar entre 1 e 20', 400);
            return;
        }
        
        $result = $this->service->getSuggestions($word, $language, $limit);
        $this->sendResponse($result);
    }
    
    /**
     * Endpoint para verificar texto completo
     */
    private function checkText(): void
    {
        $params = $this->getRequestParams();
        
        if (!isset($params['text']) || empty(trim($params['text']))) {
            $this->sendError('Parâmetro "text" é obrigatório', 400);
            return;
        }
        
        $text = trim($params['text']);
        $language = $params['language'] ?? 'pt_BR';
        
        // Verifica tamanho do texto
        if (strlen($text) > 10000) {
            $this->sendError('Texto muito longo (máximo: 10.000 caracteres)', 400);
            return;
        }
        
        $result = $this->service->checkText($text, $language);
        $this->sendResponse($result);
    }
    
    /**
     * Endpoint para obter idiomas suportados
     */
    private function getLanguages(): void
    {
        $result = $this->service->getSupportedLanguages();
        $this->sendResponse($result);
    }
    
    /**
     * Endpoint para obter estatísticas do serviço
     */
    private function getStats(): void
    {
        $result = $this->service->getServiceStats();
        $this->sendResponse($result);
    }
    
    /**
     * Endpoint de verificação de saúde
     */
    private function healthCheck(): void
    {
        $result = [
            'success' => true,
            'status' => 'healthy',
            'timestamp' => date('Y-m-d H:i:s'),
            'version' => '1.0.0',
            'endpoints' => array_keys($this->endpoints)
        ];
        
        $this->sendResponse($result);
    }
    
    /**
     * Envia resposta de sucesso
     * 
     * @param array $data
     */
    private function sendResponse(array $data): void
    {
        http_response_code(200);
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit();
    }
    
    /**
     * Envia resposta de erro
     * 
     * @param string $message
     * @param int $code
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
        exit();
    }
}

// Instancia e executa a API
try {
    $api = new SpellcheckAPI();
    $api->handleRequest();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Erro fatal: ' . $e->getMessage(),
        'code' => 500,
        'timestamp' => date('Y-m-d H:i:s')
    ], JSON_UNESCAPED_UNICODE);
}

/**
 * Documentação da API:
 * 
 * GET /spellcheck_api.php/health
 * - Verifica se a API está funcionando
 * 
 * POST /spellcheck_api.php/check-word
 * - Parâmetros: word (obrigatório), language (opcional, padrão: pt_BR)
 * - Verifica se uma palavra está correta
 * 
 * POST /spellcheck_api.php/suggestions
 * - Parâmetros: word (obrigatório), language (opcional), limit (opcional, padrão: 5)
 * - Obtém sugestões para uma palavra incorreta
 * 
 * POST /spellcheck_api.php/check-text
 * - Parâmetros: text (obrigatório), language (opcional, padrão: pt_BR)
 * - Verifica um texto completo e retorna palavras incorretas
 * 
 * GET /spellcheck_api.php/languages
 * - Retorna lista de idiomas suportados
 * 
 * GET /spellcheck_api.php/stats
 * - Retorna estatísticas do serviço
 * 
 * Exemplos de uso:
 * 
 * curl -X POST -H "Content-Type: application/json" \
 *      -d '{"word":"palavra"}' \
 *      http://localhost/spellcheck_api.php/check-word
 * 
 * curl -X POST -H "Content-Type: application/json" \
 *      -d '{"word":"palavrra", "limit":3}' \
 *      http://localhost/spellcheck_api.php/suggestions
 * 
 * curl -X POST -H "Content-Type: application/json" \
 *      -d '{"text":"Este é um texto de exemplo"}' \
 *      http://localhost/spellcheck_api.php/check-text
 */