<?php

require_once __DIR__ . '/SpellcheckConfig.php';

class SpellcheckService {
    private $dictionaries = [];
    private $currentLanguage;
    private $dictionaryPath;
    
    public function __construct($dictionaryPath = null) {
        $this->currentLanguage = SpellcheckConfig::DEFAULT_LANGUAGE;
        $this->dictionaryPath = $dictionaryPath ?: __DIR__ . '/' . SpellcheckConfig::DICTIONARY_PATH;
    }
    
    /**
     * Load dictionary files for a specific language
     */
    private function loadDictionary($language = 'pt_BR') {
        if (isset($this->dictionaries[$language])) {
            return true;
        }
        
        $affFile = $this->dictionaryPath . 'index.aff';
        $dicFile = $this->dictionaryPath . 'index.dic';
        
        if (!file_exists($affFile) || !file_exists($dicFile)) {
            throw new Exception("Dictionary files not found for language: {$language}");
        }
        
        $this->dictionaries[$language] = [
            'aff' => file_get_contents($affFile),
            'dic' => file_get_contents($dicFile),
            'words' => $this->parseDictionaryWords($dicFile)
        ];
        
        return true;
    }
    
    /**
     * Parse dictionary words from .dic file
     */
    private function parseDictionaryWords($dicFile) {
        $content = file_get_contents($dicFile);
        $lines = explode("\n", $content);
        $words = [];
        
        // Skip first line (word count)
        for ($i = 1; $i < count($lines); $i++) {
            $line = trim($lines[$i]);
            if (!empty($line)) {
                // Extract base word (before any flags)
                $word = explode('/', $line)[0];
                $words[strtolower($word)] = true;
            }
        }
        
        return $words;
    }
    
    /**
     * Check if a word is spelled correctly
     */
    public function checkWord($word, $language = null) {
        $language = $language ?: $this->currentLanguage;
        
        try {
            // Validate language support
            if (!SpellcheckConfig::isLanguageSupported($language)) {
                throw new Exception(SpellcheckConfig::getErrorMessage('LANGUAGE_NOT_SUPPORTED'));
            }
            
            $this->loadDictionary($language);
            
            $word = trim($word);
            if (empty($word)) {
                return true;
            }
            
            // Validate word length
            if (!SpellcheckConfig::isValidWordLength($word)) {
                return strlen($word) < SpellcheckConfig::MIN_WORD_LENGTH; // Short words are correct
            }
            
            // Check if word exists in dictionary
            $normalizedWord = strtolower($word);
            return isset($this->dictionaries[$language]['words'][$normalizedWord]);
            
        } catch (Exception $e) {
            error_log("Spellcheck error: " . $e->getMessage());
            return true; // Return true on error to avoid false positives
        }
    }
    
    /**
     * Get spelling suggestions for a misspelled word
     */
    public function getSuggestions($word, $language = null, $maxSuggestions = 5) {
        $language = $language ?: $this->currentLanguage;
        
        try {
            $this->loadDictionary($language);
            
            $word = trim(strtolower($word));
            if (empty($word)) {
                return [];
            }
            
            $suggestions = [];
            $words = array_keys($this->dictionaries[$language]['words']);
            
            // Simple suggestion algorithm based on edit distance
            foreach ($words as $dictWord) {
                if (abs(strlen($word) - strlen($dictWord)) <= 2) {
                    $distance = levenshtein($word, $dictWord);
                    if ($distance <= 2 && $distance > 0) {
                        $suggestions[] = [
                            'word' => $dictWord,
                            'distance' => $distance
                        ];
                    }
                }
            }
            
            // Sort by edit distance and return top suggestions
            usort($suggestions, function($a, $b) {
                return $a['distance'] - $b['distance'];
            });
            
            return array_slice(array_column($suggestions, 'word'), 0, $maxSuggestions);
            
        } catch (Exception $e) {
            error_log("Suggestion error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Check multiple words at once
     */
    public function checkText($text, $language = null) {
        $language = $language ?: $this->currentLanguage;
        
        // Extract words using regex (3+ characters)
        preg_match_all('/\b\w{3,}\b/u', $text, $matches);
        $words = $matches[0];
        
        $results = [];
        foreach ($words as $word) {
            $results[] = [
                'word' => $word,
                'correct' => $this->checkWord($word, $language)
            ];
        }
        
        return $results;
    }
    
    /**
     * Get available languages
     */
    public function getAvailableLanguages() {
        return SpellcheckConfig::SUPPORTED_LANGUAGES;
    }
    
    /**
     * Set current language
     */
    public function setLanguage($language) {
        if (SpellcheckConfig::isLanguageSupported($language)) {
            $this->currentLanguage = $language;
            return true;
        }
        return false;
    }
}