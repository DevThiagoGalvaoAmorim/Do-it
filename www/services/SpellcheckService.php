<?php

/**
 * Serviço de Correção Ortográfica
 * 
 * Esta classe fornece uma camada de serviço para funcionalidades de correção ortográfica,
 * abstraindo a implementação específica e fornecendo uma API consistente.
 */
class SpellcheckService
{
    private $config;
    private $dictionaryPath;
    private $supportedLanguages;
    
    public function __construct(SpellcheckConfig $config = null)
    {
        $this->config = $config ?? new SpellcheckConfig();
        $this->dictionaryPath = $this->config->getDictionaryPath();
        $this->supportedLanguages = $this->config->getSupportedLanguages();
    }
    
    /**
     * Verifica se uma palavra está correta
     * 
     * @param string $word A palavra a ser verificada
     * @param string $language Idioma para verificação (padrão: pt_BR)
     * @return array Resultado da verificação
     */
    public function checkWord(string $word, string $language = 'pt_BR'): array
    {
        try {
            $this->validateLanguage($language);
            $this->validateWord($word);
            
            // Simula verificação ortográfica (em produção, integraria com biblioteca real)
            $isCorrect = $this->performSpellCheck($word, $language);
            
            return [
                'success' => true,
                'word' => $word,
                'language' => $language,
                'is_correct' => $isCorrect,
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'word' => $word,
                'language' => $language
            ];
        }
    }
    
    /**
     * Obtém sugestões para uma palavra incorreta
     * 
     * @param string $word A palavra incorreta
     * @param string $language Idioma para sugestões
     * @param int $limit Número máximo de sugestões
     * @return array Lista de sugestões
     */
    public function getSuggestions(string $word, string $language = 'pt_BR', int $limit = 5): array
    {
        try {
            $this->validateLanguage($language);
            $this->validateWord($word);
            
            $suggestions = $this->generateSuggestions($word, $language, $limit);
            
            return [
                'success' => true,
                'word' => $word,
                'language' => $language,
                'suggestions' => $suggestions,
                'count' => count($suggestions)
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'word' => $word,
                'suggestions' => []
            ];
        }
    }
    
    /**
     * Verifica um texto completo e retorna palavras incorretas
     * 
     * @param string $text O texto a ser verificado
     * @param string $language Idioma para verificação
     * @return array Resultado da verificação do texto
     */
    public function checkText(string $text, string $language = 'pt_BR'): array
    {
        try {
            $this->validateLanguage($language);
            
            if (empty(trim($text))) {
                throw new InvalidArgumentException('Texto não pode estar vazio');
            }
            
            $words = $this->extractWords($text);
            $misspelledWords = [];
            $totalWords = count($words);
            
            foreach ($words as $position => $word) {
                if (!$this->performSpellCheck($word, $language)) {
                    $misspelledWords[] = [
                        'word' => $word,
                        'position' => $position,
                        'suggestions' => $this->generateSuggestions($word, $language, 3)
                    ];
                }
            }
            
            return [
                'success' => true,
                'language' => $language,
                'total_words' => $totalWords,
                'misspelled_count' => count($misspelledWords),
                'misspelled_words' => $misspelledWords,
                'accuracy_percentage' => round((($totalWords - count($misspelledWords)) / $totalWords) * 100, 2)
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'misspelled_words' => []
            ];
        }
    }
    
    /**
     * Retorna os idiomas suportados
     * 
     * @return array Lista de idiomas suportados
     */
    public function getSupportedLanguages(): array
    {
        return [
            'success' => true,
            'languages' => $this->supportedLanguages
        ];
    }
    
    /**
     * Valida se o idioma é suportado
     * 
     * @param string $language
     * @throws InvalidArgumentException
     */
    private function validateLanguage(string $language): void
    {
        if (!in_array($language, array_keys($this->supportedLanguages))) {
            throw new InvalidArgumentException("Idioma '{$language}' não é suportado");
        }
    }
    
    /**
     * Valida uma palavra
     * 
     * @param string $word
     * @throws InvalidArgumentException
     */
    private function validateWord(string $word): void
    {
        if (empty(trim($word))) {
            throw new InvalidArgumentException('Palavra não pode estar vazia');
        }
        
        if (strlen($word) > $this->config->getMaxWordLength()) {
            throw new InvalidArgumentException('Palavra muito longa');
        }
        
        if (!preg_match('/^[\p{L}\p{M}\'-]+$/u', $word)) {
            throw new InvalidArgumentException('Palavra contém caracteres inválidos');
        }
    }
    
    /**
     * Extrai palavras de um texto
     * 
     * @param string $text
     * @return array
     */
    private function extractWords(string $text): array
    {
        // Remove pontuação e divide em palavras
        $words = preg_split('/[\s\p{P}]+/u', $text, -1, PREG_SPLIT_NO_EMPTY);
        
        // Filtra palavras muito curtas
        return array_filter($words, function($word) {
            return strlen(trim($word)) >= $this->config->getMinWordLength();
        });
    }
    
    /**
     * Realiza a verificação ortográfica de uma palavra
     * 
     * @param string $word
     * @param string $language
     * @return bool
     */
    private function performSpellCheck(string $word, string $language): bool
    {
        // Em uma implementação real, aqui seria integrado com uma biblioteca
        // como Hunspell, Aspell ou uma API externa
        
        // Simulação: palavras comuns em português são consideradas corretas
        $commonWords = [
            'o', 'a', 'de', 'que', 'e', 'do', 'da', 'em', 'um', 'para', 'é', 'com', 'não',
            'uma', 'os', 'no', 'se', 'na', 'por', 'mais', 'as', 'dos', 'como', 'mas', 'foi',
            'ao', 'ele', 'das', 'tem', 'à', 'seu', 'sua', 'ou', 'ser', 'quando', 'muito',
            'há', 'nos', 'já', 'está', 'eu', 'também', 'só', 'pelo', 'pela', 'até', 'isso',
            'ela', 'entre', 'era', 'depois', 'sem', 'mesmo', 'aos', 'ter', 'seus', 'suas',
            'nota', 'notas', 'texto', 'escrever', 'lembrete', 'lembretes', 'arquivo', 'arquivos'
        ];
        
        $wordLower = mb_strtolower($word, 'UTF-8');
        return in_array($wordLower, $commonWords) || $this->isValidWord($wordLower);
    }
    
    /**
     * Verifica se uma palavra é válida usando regras básicas
     * 
     * @param string $word
     * @return bool
     */
    private function isValidWord(string $word): bool
    {
        // Regras básicas para português
        // Em uma implementação real, usaria dicionário completo
        
        // Aceita palavras que terminam com sufixos comuns
        $validSuffixes = ['ção', 'são', 'mente', 'ado', 'ada', 'ido', 'ida', 'ar', 'er', 'ir'];
        
        foreach ($validSuffixes as $suffix) {
            if (str_ends_with($word, $suffix)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Gera sugestões para uma palavra incorreta
     * 
     * @param string $word
     * @param string $language
     * @param int $limit
     * @return array
     */
    private function generateSuggestions(string $word, string $language, int $limit): array
    {
        // Implementação simplificada de sugestões
        // Em produção, usaria algoritmos como Levenshtein distance
        
        $suggestions = [];
        $wordLower = mb_strtolower($word, 'UTF-8');
        
        // Sugestões baseadas em erros comuns
        $commonMistakes = [
            'ç' => 'c',
            'ss' => 's',
            'rr' => 'r',
            'nh' => 'n',
            'lh' => 'l'
        ];
        
        foreach ($commonMistakes as $wrong => $correct) {
            if (strpos($wordLower, $wrong) !== false) {
                $suggestion = str_replace($wrong, $correct, $wordLower);
                if ($this->performSpellCheck($suggestion, $language)) {
                    $suggestions[] = $suggestion;
                }
            }
        }
        
        // Adiciona algumas sugestões genéricas se não encontrou nenhuma
        if (empty($suggestions)) {
            $suggestions = ['palavra', 'texto', 'nota'];
        }
        
        return array_slice(array_unique($suggestions), 0, $limit);
    }
    
    /**
     * Obtém estatísticas do serviço
     * 
     * @return array
     */
    public function getServiceStats(): array
    {
        return [
            'service_name' => 'SpellcheckService',
            'version' => '1.0.0',
            'supported_languages' => count($this->supportedLanguages),
            'max_word_length' => $this->config->getMaxWordLength(),
            'min_word_length' => $this->config->getMinWordLength(),
            'dictionary_path' => $this->dictionaryPath
        ];
    }
}