<?php

class SpellcheckConfig {
    // API Configuration
    const API_VERSION = '1.0';
    const DEFAULT_LANGUAGE = 'pt_BR';
    const MAX_SUGGESTIONS = 10;
    const CACHE_TTL = 300; // 5 minutes in seconds
    
    // Performance settings
    const MAX_WORD_LENGTH = 50;
    const MIN_WORD_LENGTH = 2;
    const MAX_TEXT_LENGTH = 10000; // Maximum text length for batch checking
    const MAX_BATCH_WORDS = 100; // Maximum words to check in one request
    
    // Dictionary settings
    const DICTIONARY_PATH = '../public/Javascript/dicionarios/';
    const SUPPORTED_LANGUAGES = ['pt_BR'];
    
    // Error messages
    const ERROR_MESSAGES = [
        'WORD_REQUIRED' => 'Word parameter is required',
        'TEXT_REQUIRED' => 'Text parameter is required',
        'INVALID_ACTION' => 'Invalid action specified',
        'LANGUAGE_NOT_SUPPORTED' => 'Language not supported',
        'WORD_TOO_LONG' => 'Word exceeds maximum length',
        'TEXT_TOO_LONG' => 'Text exceeds maximum length',
        'DICTIONARY_NOT_FOUND' => 'Dictionary files not found',
        'INVALID_REQUEST' => 'Invalid request format'
    ];
    
    /**
     * Get configuration value
     */
    public static function get($key, $default = null) {
        return defined("self::{$key}") ? constant("self::{$key}") : $default;
    }
    
    /**
     * Validate language support
     */
    public static function isLanguageSupported($language) {
        return in_array($language, self::SUPPORTED_LANGUAGES);
    }
    
    /**
     * Get error message
     */
    public static function getErrorMessage($key) {
        return self::ERROR_MESSAGES[$key] ?? 'Unknown error';
    }
    
    /**
     * Validate word length
     */
    public static function isValidWordLength($word) {
        $length = strlen($word);
        return $length >= self::MIN_WORD_LENGTH && $length <= self::MAX_WORD_LENGTH;
    }
    
    /**
     * Validate text length
     */
    public static function isValidTextLength($text) {
        return strlen($text) <= self::MAX_TEXT_LENGTH;
    }
    
    /**
     * Get dictionary file paths
     */
    public static function getDictionaryPaths($language = null) {
        $language = $language ?: self::DEFAULT_LANGUAGE;
        $basePath = __DIR__ . '/' . self::DICTIONARY_PATH;
        
        return [
            'aff' => $basePath . 'index.aff',
            'dic' => $basePath . 'index.dic'
        ];
    }
}