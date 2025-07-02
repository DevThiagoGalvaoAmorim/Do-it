<?php

require_once __DIR__ . '/SpellcheckConfig.php'; // Certifique-se de que o caminho está correto

/**
 * Serviço de Correção Ortográfica
 *
 * Esta classe fornece uma camada de serviço para funcionalidades de correção ortográfica,
 * abstraindo a implementação específica e fornecendo uma API consistente.
 * Ela interage com a SpellcheckConfig para obter as configurações e gerencia
 * o carregamento e a utilização dos dicionários.
 */
class SpellcheckService
{
    private SpellcheckConfig $config;
    private array $dictionaries = []; // Cache para dicionários carregados
    private ?string $currentLanguage = null; // Idioma padrão para operações se não especificado

    /**
     * Construtor da classe SpellcheckService.
     *
     * Injeta a configuração de verificação ortográfica. Se nenhuma configuração for fornecida,
     * uma nova instância de SpellcheckConfig será criada com valores padrão.
     *
     * @param SpellcheckConfig|null $config A instância de configuração.
     */
    public function __construct(SpellcheckConfig $config = null)
    {
        $this->config = $config ?? new SpellcheckConfig();
        $this->currentLanguage = $this->config::DEFAULT_LANGUAGE_CODE; // Define o idioma padrão da config
    }

    /**
     * Carrega os arquivos de dicionário (.dic e .aff) para um idioma específico.
     * Os dicionários são armazenados em cache após o primeiro carregamento.
     *
     * @param string $language O código do idioma (ex: 'pt_BR').
     * @throws Exception Se os arquivos de dicionário não forem encontrados ou não puderem ser lidos.
     */
    private function loadDictionary(string $language): void
    {
        // Se o dicionário já estiver carregado, não faz nada.
        if (isset($this->dictionaries[$language])) {
            return;
        }

        // Obtém a configuração específica do idioma
        $langConfig = $this->config->getLanguageConfig($language);
        if (!$langConfig) {
            throw new InvalidArgumentException(SpellcheckConfig::getErrorMessage('LANGUAGE_NOT_SUPPORTED') . ": '{$language}'");
        }

        // Obtém os caminhos completos dos arquivos de dicionário e afixos
        $affFile = $this->config->getDictionaryFilePath($language, 'affix');
        $dicFile = $this->config->getDictionaryFilePath($language, 'dictionary');

        // Verifica se os arquivos existem e são legíveis
        if (!file_exists($affFile) || !is_readable($affFile)) {
            throw new Exception(SpellcheckConfig::getErrorMessage('DICTIONARY_NOT_FOUND') . ": Arquivo AFF '{$affFile}'.");
        }
        if (!file_exists($dicFile) || !is_readable($dicFile)) {
            throw new Exception(SpellcheckConfig::getErrorMessage('DICTIONARY_NOT_FOUND') . ": Arquivo DIC '{$dicFile}'.");
        }

        // Armazena o conteúdo e as palavras do dicionário em cache
        $this->dictionaries[$language] = [
            'aff' => file_get_contents($affFile),
            'dic' => file_get_contents($dicFile),
            'words' => $this->parseDictionaryWords($dicFile, $langConfig['encoding']) // Passa o encoding
        ];
    }

    /**
     * Extrai e normaliza as palavras de um arquivo .dic.
     * Ignora a primeira linha (contagem de palavras) e flags.
     *
     * @param string $dicFile O caminho para o arquivo .dic.
     * @param string $encoding A codificação do arquivo.
     * @return array Um array associativo de palavras normalizadas (minúsculas) para busca rápida.
     */
    private function parseDictionaryWords(string $dicFile, string $encoding): array
    {
        $content = file_get_contents($dicFile);
        $lines = explode("\n", $content);
        $words = [];

        // Ignora a primeira linha que geralmente contém a contagem de palavras
        for ($i = 1; $i < count($lines); $i++) {
            $line = trim($lines[$i]);
            if (!empty($line)) {
                // Extrai a palavra base (antes de quaisquer flags)
                $word = explode('/', $line)[0];
                // Normaliza a palavra para minúsculas e armazena para busca rápida
                $words[mb_strtolower($word, $encoding)] = true;
            }
        }
        return $words;
    }

    /**
     * Valida se o idioma é suportado.
     *
     * @param string $language O código do idioma a ser validado.
     * @throws InvalidArgumentException Se o idioma não for suportado.
     */
    private function validateLanguage(string $language): void
    {
        if (!$this->config->isLanguageSupported($language)) {
            throw new InvalidArgumentException(SpellcheckConfig::getErrorMessage('LANGUAGE_NOT_SUPPORTED'));
        }
    }

    /**
     * Valida uma palavra de acordo com as regras de comprimento e caracteres válidos.
     *
     * @param string $word A palavra a ser validada.
     * @throws InvalidArgumentException Se a palavra for vazia, muito longa ou contiver caracteres inválidos.
     */
    private function validateWord(string $word): void
    {
        $trimmedWord = trim($word);
        if (empty($trimmedWord)) {
            throw new InvalidArgumentException(SpellcheckConfig::getErrorMessage('WORD_REQUIRED'));
        }

        // Usa as configurações de comprimento mínimo e máximo da SpellcheckConfig
        if (mb_strlen($trimmedWord, 'UTF-8') > $this->config->getMaxWordLength()) {
            throw new InvalidArgumentException(SpellcheckConfig::getErrorMessage('WORD_TOO_LONG'));
        }
        // Nota: A validação de MIN_WORD_LENGTH é mais aplicável na extração ou na lógica de sugestão,
        // pois palavras muito curtas podem ser corretas (ex: "e", "o", "a").

        // Expressão regular para validar caracteres de palavras (letras, marcas de acento, apóstrofo, hífen)
        if (!preg_match('/^[\p{L}\p{M}\'-]+$/u', $trimmedWord)) {
            throw new InvalidArgumentException('Palavra contém caracteres inválidos.');
        }
    }

    /**
     * Extrai palavras de um texto.
     *
     * Divide o texto em palavras, removendo pontuação e espaços múltiplos,
     * e filtra palavras com base no comprimento mínimo configurado.
     *
     * @param string $text O texto de onde as palavras serão extraídas.
     * @return array Um array de palavras.
     */
    private function extractWords(string $text): array
    {
        // Remove pontuação e divide em palavras. \p{P} para qualquer pontuação, \s para espaço.
        $words = preg_split('/[\s\p{P}]+/u', $text, -1, PREG_SPLIT_NO_EMPTY);

        // Filtra palavras que são muito curtas de acordo com a configuração
        return array_filter($words, function ($word) {
            return mb_strlen(trim($word), 'UTF-8') >= $this->config->getMinWordLength();
        });
    }

    /**
     * Realiza a verificação ortográfica de uma palavra usando o dicionário carregado.
     *
     * @param string $word A palavra a ser verificada.
     * @param string $language O idioma para a verificação.
     * @return bool True se a palavra estiver correta, false caso contrário.
     * @throws Exception Se o dicionário para o idioma não puder ser carregado.
     */
    private function performSpellCheck(string $word, string $language): bool
    {
        $this->loadDictionary($language); // Garante que o dicionário esteja carregado

        $normalizedWord = mb_strtolower(trim($word), 'UTF-8');
        // Verifica se a palavra normalizada existe no conjunto de palavras do dicionário
        return isset($this->dictionaries[$language]['words'][$normalizedWord]);
    }

    /**
     * Gera sugestões para uma palavra incorreta usando o algoritmo Levenshtein.
     *
     * @param string $word A palavra para a qual gerar sugestões.
     * @param string $language O idioma para as sugestões.
     * @param int $limit O número máximo de sugestões a retornar.
     * @return array Uma lista de palavras sugeridas.
     * @throws Exception Se o dicionário para o idioma não puder ser carregado.
     */
    private function generateSuggestions(string $word, string $language, int $limit): array
    {
        $this->loadDictionary($language); // Garante que o dicionário esteja carregado

        $suggestions = [];
        $normalizedWord = mb_strtolower(trim($word), 'UTF-8');

        // Itera sobre todas as palavras do dicionário para encontrar sugestões
        foreach (array_keys($this->dictionaries[$language]['words']) as $dictWord) {
            // Otimização: calcula a distância de Levenshtein apenas para palavras de comprimento similar
            // A diferença de comprimento não deve ser muito grande para ser uma sugestão relevante.
            if (abs(mb_strlen($normalizedWord, 'UTF-8') - mb_strlen($dictWord, 'UTF-8')) <= 2) {
                // Calcula a distância de Levenshtein. Um valor menor indica mais similaridade.
                // Usamos 2 como limite razoável para a distância de edição.
                $distance = levenshtein($normalizedWord, $dictWord);
                if ($distance <= 2 && $distance > 0) { // Distância 0 significa que é a mesma palavra
                    $suggestions[] = [
                        'word' => $dictWord,
                        'distance' => $distance
                    ];
                }
            }
        }

        // Ordena as sugestões pela menor distância de Levenshtein (as mais relevantes primeiro)
        usort($suggestions, function ($a, $b) {
            return $a['distance'] <=> $b['distance']; // Operador spaceship (PHP 7+)
        });

        // Retorna apenas as palavras (descartando a distância) e limita o número de resultados
        return array_slice(array_column($suggestions, 'word'), 0, $limit);
    }

    ---

    ### Métodos Públicos da API do Serviço

    /**
     * Verifica se uma palavra está correta.
     *
     * @param string $word A palavra a ser verificada.
     * @param string|null $language O idioma para verificação (usa o padrão da config se null).
     * @return array Um array com o resultado da verificação (sucesso, palavra, idioma, está_correta, timestamp ou erro).
     */
    public function checkWord(string $word, ?string $language = null): array
    {
        $language = $language ?? $this->currentLanguage; // Usa o idioma padrão se não especificado

        try {
            $this->validateLanguage($language); // Valida o idioma
            $this->validateWord($word);         // Valida a palavra

            // Realiza a verificação ortográfica
            $isCorrect = $this->performSpellCheck($word, $language);

            return [
                'success' => true,
                'word' => $word,
                'language' => $language,
                'is_correct' => $isCorrect,
                'timestamp' => date('Y-m-d H:i:s')
            ];

        } catch (Exception $e) {
            // Retorna um array de erro em caso de exceção
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'word' => $word,
                'language' => $language,
                'is_correct' => false // Por padrão, se houver erro na verificação, considera-se incorreta
            ];
        }
    }

    /**
     * Obtém sugestões para uma palavra incorreta.
     *
     * @param string $word A palavra para a qual obter sugestões.
     * @param string|null $language O idioma para as sugestões (usa o padrão da config se null).
     * @param int|null $limit Número máximo de sugestões a retornar (usa o padrão da config se null).
     * @return array Um array com as sugestões (sucesso, palavra, idioma, sugestões, contagem ou erro).
     */
    public function getSuggestions(string $word, ?string $language = null, ?int $limit = null): array
    {
        $language = $language ?? $this->currentLanguage; // Usa o idioma padrão se não especificado
        $limit = $limit ?? $this->config::MAX_SUGGESTIONS; // Usa o limite de sugestões da config

        try {
            $this->validateLanguage($language); // Valida o idioma
            $this->validateWord($word);         // Valida a palavra

            // Gera as sugestões
            $suggestions = $this->generateSuggestions($word, $language, $limit);

            return [
                'success' => true,
                'word' => $word,
                'language' => $language,
                'suggestions' => $suggestions,
                'count' => count($suggestions)
            ];

        } catch (Exception $e) {
            // Retorna um array de erro em caso de exceção
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'word' => $word,
                'language' => $language,
                'suggestions' => [] // Nenhuma sugestão em caso de erro
            ];
        }
    }

    /**
     * Verifica um texto completo e retorna as palavras incorretas com sugestões.
     *
     * @param string $text O texto a ser verificado.
     * @param string|null $language O idioma para verificação (usa o padrão da config se null).
     * @return array Um array com o resultado da verificação do texto (sucesso, idioma, total_palavras,
     * misspelled_count, misspelled_words, accuracy_percentage ou erro).
     */
    public function checkText(string $text, ?string $language = null): array
    {
        $language = $language ?? $this->currentLanguage; // Usa o idioma padrão se não especificado

        try {
            $this->validateLanguage($language); // Valida o idioma

            // Valida o comprimento máximo do texto
            if (mb_strlen($text, 'UTF-8') > $this->config->getMaxTextLength()) {
                throw new InvalidArgumentException(SpellcheckConfig::getErrorMessage('TEXT_TOO_LONG'));
            }

            if (empty(trim($text))) {
                throw new InvalidArgumentException(SpellcheckConfig::getErrorMessage('TEXT_REQUIRED'));
            }

            $words = $this->extractWords($text); // Extrai as palavras do texto
            $misspelledWords = [];
            $totalWords = count($words);

            if ($totalWords === 0) {
                 return [
                    'success' => true,
                    'language' => $language,
                    'total_words' => 0,
                    'misspelled_count' => 0,
                    'misspelled_words' => [],
                    'accuracy_percentage' => 100.00
                ];
            }


            // Itera sobre as palavras para verificar e obter sugestões se incorretas
            foreach ($words as $position => $word) {
                if (!$this->performSpellCheck($word, $language)) {
                    $misspelledWords[] = [
                        'word' => $word,
                        'original_position' => $position, // Mantém a posição original no array de palavras
                        'suggestions' => $this->generateSuggestions($word, $language, $this->config::MAX_SUGGESTIONS)
                    ];
                }
            }

            // Calcula a porcentagem de acurácia
            $accuracyPercentage = ($totalWords > 0) ? round((($totalWords - count($misspelledWords)) / $totalWords) * 100, 2) : 100.00;

            return [
                'success' => true,
                'language' => $language,
                'total_words' => $totalWords,
                'misspelled_count' => count($misspelledWords),
                'misspelled_words' => $misspelledWords,
                'accuracy_percentage' => $accuracyPercentage,
                'timestamp' => date('Y-m-d H:i:s')
            ];

        } catch (Exception $e) {
            // Retorna um array de erro em caso de exceção
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'misspelled_words' => [], // Nenhuma palavra incorreta em caso de erro no serviço
                'language' => $language
            ];
        }
    }

    /**
     * Retorna a lista de idiomas suportados pelo serviço, obtida da configuração.
     *
     * @return array Um array com sucesso e a lista de idiomas suportados.
     */
    public function getSupportedLanguages(): array
    {
        return [
            'success' => true,
            'languages' => $this->config->getSupportedLanguages() // Obtém da instância de config
        ];
    }

    /**
     * Define o idioma padrão para as operações do serviço.
     *
     * @param string $language O código do idioma a ser definido como padrão.
     * @return bool True se o idioma foi definido com sucesso, false caso contrário.
     */
    public function setLanguage(string $language): bool
    {
        if ($this->config->isLanguageSupported($language)) {
            $this->currentLanguage = $language;
            return true;
        }
        return false;
    }

    /**
     * Obtém estatísticas básicas do serviço de verificação ortográfica.
     *
     * @return array Um array contendo informações sobre o serviço.
     */
    public function getServiceStats(): array
    {
        return [
            'service_name' => 'SpellcheckService',
            'api_version' => $this->config::API_VERSION,
            'default_language' => $this->currentLanguage,
            'supported_languages_count' => count($this->config->getSupportedLanguages()),
            'max_word_length' => $this->config->getMaxWordLength(),
            'min_word_length' => $this->config->getMinWordLength(),
            'max_text_length' => $this->config->getMaxTextLength(),
            'dictionary_base_path' => $this->config->getDictionaryPath(),
            'cache_enabled' => $this->config->isCacheEnabled(),
            'cache_timeout_seconds' => $this->config->getCacheTimeout()
        ];
    }
}