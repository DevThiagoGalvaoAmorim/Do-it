<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/SpellcheckService.php';
require_once __DIR__ . '/SpellcheckConfig.php';

try {
    $spellcheckService = new SpellcheckService();
    
    $action = $_REQUEST['action'] ?? '';
    $response = ['success' => false, 'data' => null, 'error' => null, 'version' => SpellcheckConfig::API_VERSION];
    
    // Validate action
    if (empty($action)) {
        throw new Exception(SpellcheckConfig::getErrorMessage('INVALID_ACTION'));
    }
    
    switch ($action) {
        case 'check':
            $word = $_REQUEST['word'] ?? '';
            $language = $_REQUEST['language'] ?? SpellcheckConfig::DEFAULT_LANGUAGE;
            
            if (empty($word)) {
                throw new Exception(SpellcheckConfig::getErrorMessage('WORD_REQUIRED'));
            }
            
            if (!SpellcheckConfig::isValidWordLength($word)) {
                throw new Exception(SpellcheckConfig::getErrorMessage('WORD_TOO_LONG'));
            }
            
            if (!SpellcheckConfig::isLanguageSupported($language)) {
                throw new Exception(SpellcheckConfig::getErrorMessage('LANGUAGE_NOT_SUPPORTED'));
            }
            
            $isCorrect = $spellcheckService->checkWord($word, $language);
            $response = [
                'success' => true,
                'data' => [
                    'word' => $word,
                    'correct' => $isCorrect,
                    'language' => $language
                ]
            ];
            break;
            
        case 'suggest':
            $word = $_REQUEST['word'] ?? '';
            $language = $_REQUEST['language'] ?? 'pt_BR';
            $maxSuggestions = (int)($_REQUEST['max_suggestions'] ?? 5);
            
            if (empty($word)) {
                throw new Exception('Word parameter is required');
            }
            
            $suggestions = $spellcheckService->getSuggestions($word, $language, $maxSuggestions);
            $response = [
                'success' => true,
                'data' => [
                    'word' => $word,
                    'suggestions' => $suggestions
                ]
            ];
            break;
            
        case 'check_text':
            $text = $_REQUEST['text'] ?? '';
            $language = $_REQUEST['language'] ?? 'pt_BR';
            
            if (empty($text)) {
                throw new Exception('Text parameter is required');
            }
            
            $results = $spellcheckService->checkText($text, $language);
            $response = [
                'success' => true,
                'data' => [
                    'text' => $text,
                    'results' => $results
                ]
            ];
            break;
            
        case 'languages':
            $languages = $spellcheckService->getAvailableLanguages();
            $response = [
                'success' => true,
                'data' => [
                    'languages' => $languages
                ]
            ];
            break;
            
        default:
            throw new Exception('Invalid action. Available actions: check, suggest, check_text, languages');
    }
    
} catch (Exception $e) {
    http_response_code(400);
    $response = [
        'success' => false,
        'error' => $e->getMessage()
    ];
    
    // Log error for debugging
    error_log("Spellcheck API Error: " . $e->getMessage());
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);