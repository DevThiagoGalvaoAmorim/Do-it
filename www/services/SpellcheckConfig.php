<?php

/**
 * Configuração do Serviço de Correção Ortográfica
 *
 * Esta classe gerencia todas as configurações relacionadas ao serviço de correção ortográfica,
 * incluindo idiomas suportados, caminhos de dicionários e limites de processamento.
 */
class SpellcheckConfig
{
    // Propriedades de configuração
    private string $dictionaryPath;
    private array $supportedLanguages;
    private int $maxWordLength;
    private int $minWordLength;
    private bool $cacheEnabled;
    private int $cacheTimeout;
    private int $maxTextLength;

    // Constantes de API e Desempenho (incorporadas da branch develop)
    public const API_VERSION = '1.0';
    public const DEFAULT_LANGUAGE_CODE = 'pt_BR'; // Renomeado para evitar conflito com 'DEFAULT_LANGUAGE' de supportedLanguages
    public const MAX_SUGGESTIONS = 10;
    public const MAX_BATCH_WORDS = 100; // Máximo de palavras para verificar em uma requisição

    // Mensagens de Erro (incorporadas da branch develop)
    private const ERROR_MESSAGES = [
        'WORD_REQUIRED' => 'O parâmetro da palavra é obrigatório.',
        'TEXT_REQUIRED' => 'O parâmetro de texto é obrigatório.',
        'INVALID_ACTION' => 'Ação inválida especificada.',
        'LANGUAGE_NOT_SUPPORTED' => 'Idioma não suportado.',
        'WORD_TOO_LONG' => 'A palavra excede o comprimento máximo permitido.',
        'TEXT_TOO_LONG' => 'O texto excede o comprimento máximo permitido.',
        'DICTIONARY_NOT_FOUND' => 'Arquivos de dicionário não encontrados.',
        'INVALID_REQUEST' => 'Formato de requisição inválido.'
    ];

    public function __construct(array $config = [])
    {
        $this->initializeDefaults();
        $this->loadConfiguration($config);
    }

    /**
     * Inicializa configurações padrão
     *
     * Define os valores padrão para as propriedades da configuração,
     * incluindo o caminho dos dicionários, limites de palavras e texto,
     * configurações de cache e idiomas suportados.
     */
    private function initializeDefaults(): void
    {
        $this->dictionaryPath = __DIR__ . '/../public/Javascript/dictionaries/'; // Caminho padrão para os dicionários
        $this->maxWordLength = self::MAX_WORD_LENGTH; // Usando constante da versão develop
        $this->minWordLength = self::MIN_WORD_LENGTH; // Usando constante da versão develop
        $this->cacheEnabled = true; // Cache habilitado por padrão
        $this->cacheTimeout = self::CACHE_TTL; // Timeout do cache em segundos (da versão develop)
        $this->maxTextLength = self::MAX_TEXT_LENGTH; // Comprimento máximo de texto (da versão develop)

        // Idiomas suportados com suas configurações específicas
        $this->supportedLanguages = [
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
            ],
            'es_ES' => [
                'name' => 'Español (España)',
                'dictionary_file' => 'es_ES.dic',
                'affix_file' => 'es_ES.aff',
                'encoding' => 'UTF-8'
            ],
            'fr_FR' => [
                'name' => 'Français (France)',
                'dictionary_file' => 'fr_FR.dic',
                'affix_file' => 'fr_FR.aff',
                'encoding' => 'UTF-8'
            ]
        ];
    }

    /**
     * Carrega configurações personalizadas
     *
     * Sobrescreve as configurações padrão com os valores fornecidos no array $config.
     * Garante que os tipos de dados sejam corretos.
     *
     * @param array $config Um array associativo com as configurações a serem carregadas.
     */
    private function loadConfiguration(array $config): void
    {
        if (isset($config['dictionary_path'])) {
            $this->setDictionaryPath($config['dictionary_path']); // Usa o setter para normalizar o caminho
        }

        if (isset($config['max_word_length'])) {
            $this->setMaxWordLength((int) $config['max_word_length']);
        }

        if (isset($config['min_word_length'])) {
            $this->setMinWordLength((int) $config['min_word_length']);
        }

        if (isset($config['cache_enabled'])) {
            $this->setCacheEnabled((bool) $config['cache_enabled']);
        }

        if (isset($config['cache_timeout'])) {
            $this->setCacheTimeout((int) $config['cache_timeout']);
        }

        if (isset($config['max_text_length'])) {
            $this->setMaxTextLength((int) $config['max_text_length']);
        }

        // Mescla idiomas suportados. Permite adicionar ou sobrescrever idiomas existentes.
        if (isset($config['supported_languages']) && is_array($config['supported_languages'])) {
            foreach ($config['supported_languages'] as $code => $langConfig) {
                $this->addSupportedLanguage($code, $langConfig);
            }
        }
    }

    /**
     * Obtém o caminho dos dicionários
     *
     * @return string O caminho completo para o diretório de dicionários.
     */
    public function getDictionaryPath(): string
    {
        return $this->dictionaryPath;
    }

    /**
     * Define o caminho dos dicionários
     *
     * Garante que o caminho termine com uma barra para consistência.
     *
     * @param string $path O novo caminho para o diretório de dicionários.
     */
    public function setDictionaryPath(string $path): void
    {
        $this->dictionaryPath = rtrim($path, '/') . '/';
    }

    /**
     * Obtém os idiomas suportados
     *
     * @return array Um array associativo de idiomas suportados com suas configurações.
     */
    public function getSupportedLanguages(): array
    {
        return $this->supportedLanguages;
    }

    /**
     * Adiciona ou atualiza um novo idioma suportado
     *
     * Verifica se todos os campos obrigatórios para a configuração do idioma estão presentes.
     *
     * @param string $code Código do idioma (ex: pt_BR).
     * @param array $config Configuração do idioma (name, dictionary_file, affix_file, encoding).
     * @throws InvalidArgumentException Se algum campo obrigatório estiver faltando na configuração do idioma.
     */
    public function addSupportedLanguage(string $code, array $config): void
    {
        $requiredFields = ['name', 'dictionary_file', 'affix_file', 'encoding'];

        foreach ($requiredFields as $field) {
            if (!isset($config[$field])) {
                throw new InvalidArgumentException("Campo obrigatório '{$field}' não encontrado na configuração do idioma '{$code}'");
            }
        }

        $this->supportedLanguages[$code] = $config;
    }

    /**
     * Verifica se um idioma é suportado
     *
     * @param string $languageCode O código do idioma a ser verificado.
     * @return bool True se o idioma for suportado, false caso contrário.
     */
    public function isLanguageSupported(string $languageCode): bool
    {
        return isset($this->supportedLanguages[$languageCode]);
    }

    /**
     * Obtém a configuração de um idioma específico
     *
     * @param string $languageCode O código do idioma.
     * @return array|null Um array com a configuração do idioma, ou null se não for encontrado.
     */
    public function getLanguageConfig(string $languageCode): ?array
    {
        return $this->supportedLanguages[$languageCode] ?? null;
    }

    /**
     * Obtém o comprimento máximo de palavra
     *
     * @return int O comprimento máximo permitido para uma palavra.
     */
    public function getMaxWordLength(): int
    {
        return $this->maxWordLength;
    }

    /**
     * Define o comprimento máximo de palavra
     *
     * @param int $length O novo comprimento máximo de palavra.
     * @throws InvalidArgumentException Se o comprimento for menor que 1.
     */
    public function setMaxWordLength(int $length): void
    {
        if ($length < 1) {
            throw new InvalidArgumentException('Comprimento máximo de palavra deve ser maior que 0.');
        }

        $this->maxWordLength = $length;
    }

    /**
     * Obtém o comprimento mínimo de palavra
     *
     * @return int O comprimento mínimo permitido para uma palavra.
     */
    public function getMinWordLength(): int
    {
        return $this->minWordLength;
    }

    /**
     * Define o comprimento mínimo de palavra
     *
     * @param int $length O novo comprimento mínimo de palavra.
     * @throws InvalidArgumentException Se o comprimento for menor que 1.
     */
    public function setMinWordLength(int $length): void
    {
        if ($length < 1) {
            throw new InvalidArgumentException('Comprimento mínimo de palavra deve ser maior que 0.');
        }

        $this->minWordLength = $length;
    }

    /**
     * Verifica se o cache está habilitado
     *
     * @return bool True se o cache estiver habilitado, false caso contrário.
     */
    public function isCacheEnabled(): bool
    {
        return $this->cacheEnabled;
    }

    /**
     * Habilita ou desabilita o cache
     *
     * @param bool $enabled True para habilitar, false para desabilitar.
     */
    public function setCacheEnabled(bool $enabled): void
    {
        $this->cacheEnabled = $enabled;
    }

    /**
     * Obtém o timeout do cache em segundos
     *
     * @return int O tempo de vida do cache em segundos.
     */
    public function getCacheTimeout(): int
    {
        return $this->cacheTimeout;
    }

    /**
     * Define o timeout do cache em segundos
     *
     * @param int $timeout O novo tempo de vida do cache em segundos.
     * @throws InvalidArgumentException Se o timeout for negativo.
     */
    public function setCacheTimeout(int $timeout): void
    {
        if ($timeout < 0) {
            throw new InvalidArgumentException('Timeout do cache deve ser maior ou igual a 0.');
        }

        $this->cacheTimeout = $timeout;
    }

    /**
     * Obtém o comprimento máximo de texto
     *
     * @return int O comprimento máximo permitido para o texto a ser verificado.
     */
    public function getMaxTextLength(): int
    {
        return $this->maxTextLength;
    }

    /**
     * Define o comprimento máximo de texto
     *
     * @param int $length O novo comprimento máximo de texto.
     * @throws InvalidArgumentException Se o comprimento for menor que 1.
     */
    public function setMaxTextLength(int $length): void
    {
        if ($length < 1) {
            throw new InvalidArgumentException('Comprimento máximo de texto deve ser maior que 0.');
        }

        $this->maxTextLength = $length;
    }

    /**
     * Obtém o caminho completo para um arquivo de dicionário (dic ou aff)
     *
     * @param string $languageCode O código do idioma.
     * @param string $fileType O tipo de arquivo ('dictionary' para .dic ou 'affix' para .aff).
     * @return string|null O caminho completo do arquivo, ou null se o idioma/tipo não for encontrado.
     */
    public function getDictionaryFilePath(string $languageCode, string $fileType = 'dictionary'): ?string
    {
        $languageConfig = $this->getLanguageConfig($languageCode);

        if (!$languageConfig) {
            return null;
        }

        $fileName = $fileType === 'affix'
            ? ($languageConfig['affix_file'] ?? null) // Garante que a chave exista
            : ($languageConfig['dictionary_file'] ?? null); // Garante que a chave exista

        if (empty($fileName)) {
            return null; // Retorna null se o nome do arquivo não for encontrado na configuração do idioma
        }

        return $this->dictionaryPath . $fileName;
    }

    /**
     * Valida a configuração atual
     *
     * Percorre todas as configurações e verifica se estão consistentes e válidas.
     *
     * @return array Uma lista de mensagens de erro. O array estará vazio se a configuração for válida.
     */
    public function validate(): array
    {
        $errors = [];

        // Verifica se o diretório de dicionários existe e é legível
        if (!is_dir($this->dictionaryPath) || !is_readable($this->dictionaryPath)) {
            $errors[] = "Diretório de dicionários não encontrado ou não acessível: {$this->dictionaryPath}";
        }

        // Verifica se há pelo menos um idioma suportado configurado
        if (empty($this->supportedLanguages)) {
            $errors[] = self::getErrorMessage('LANGUAGE_NOT_SUPPORTED') . ' (Nenhum idioma configurado).';
        }

        // Valida configurações de cada idioma individualmente
        foreach ($this->supportedLanguages as $code => $config) {
            $requiredFields = ['name', 'dictionary_file', 'affix_file', 'encoding'];

            foreach ($requiredFields as $field) {
                if (!isset($config[$field]) || empty($config[$field])) {
                    $errors[] = "Campo '{$field}' obrigatório não encontrado ou vazio para idioma '{$code}'.";
                }
            }

            // Verifica se os arquivos de dicionário existem para cada idioma
            if (is_dir($this->dictionaryPath)) {
                $dictFile = $this->getDictionaryFilePath($code, 'dictionary');
                $affixFile = $this->getDictionaryFilePath($code, 'affix');

                if ($dictFile && !file_exists($dictFile)) {
                    $errors[] = "Arquivo de dicionário não encontrado para '{$code}': {$dictFile}";
                }

                if ($affixFile && !file_exists($affixFile)) {
                    $errors[] = "Arquivo de afixos não encontrado para '{$code}': {$affixFile}";
                }
            }
        }

        // Valida as relações de comprimento mínimo e máximo de palavra
        if ($this->minWordLength >= $this->maxWordLength) {
            $errors[] = 'Comprimento mínimo de palavra (' . $this->minWordLength . ') deve ser menor que o máximo (' . $this->maxWordLength . ').';
        }

        // Valida o comprimento máximo de texto
        if ($this->maxTextLength < 100) { // Um valor mínimo razoável para o comprimento do texto
            $errors[] = 'Comprimento máximo de texto muito pequeno (mínimo recomendado: 100 caracteres).';
        }

        // Valida o timeout do cache
        if ($this->cacheTimeout < 0) {
            $errors[] = 'Timeout do cache não pode ser negativo.';
        }

        return $errors;
    }

    /**
     * Exporta a configuração atual como array
     *
     * Útil para serialização ou para inspecionar o estado atual da configuração.
     *
     * @return array Um array associativo representando o estado atual da configuração.
     */
    public function toArray(): array
    {
        return [
            'dictionary_path' => $this->dictionaryPath,
            'supported_languages' => $this->supportedLanguages,
            'max_word_length' => $this->maxWordLength,
            'min_word_length' => $this->minWordLength,
            'cache_enabled' => $this->cacheEnabled,
            'cache_timeout' => $this->cacheTimeout,
            'max_text_length' => $this->maxTextLength,
            // Podemos adicionar as constantes aqui se quisermos que elas sejam parte da serialização,
            // mas como são constantes, geralmente não são salvas.
            // 'api_version' => self::API_VERSION,
            // 'default_language_code' => self::DEFAULT_LANGUAGE_CODE,
            // 'max_suggestions' => self::MAX_SUGGESTIONS,
            // 'max_batch_words' => self::MAX_BATCH_WORDS,
        ];
    }

    /**
     * Carrega configuração de um arquivo JSON
     *
     * Cria uma nova instância de SpellcheckConfig com base em um arquivo JSON.
     *
     * @param string $filePath O caminho completo para o arquivo JSON.
     * @return SpellcheckConfig Uma nova instância da classe com a configuração carregada.
     * @throws Exception Se o arquivo não for encontrado ou houver erro na decodificação JSON.
     */
    public static function fromFile(string $filePath): self
    {
        if (!file_exists($filePath)) {
            throw new Exception("Arquivo de configuração não encontrado: {$filePath}");
        }

        $content = file_get_contents($filePath);
        $config = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Erro ao decodificar arquivo de configuração JSON: ' . json_last_error_msg());
        }

        return new self($config);
    }

    /**
     * Salva a configuração atual em um arquivo JSON
     *
     * Serializa a configuração atual para um arquivo JSON formatado.
     *
     * @param string $filePath O caminho completo para o arquivo JSON onde a configuração será salva.
     * @throws Exception Se houver erro na codificação JSON ou ao salvar o arquivo.
     */
    public function saveToFile(string $filePath): void
    {
        $json = json_encode($this->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        if ($json === false) {
            throw new Exception('Erro ao codificar configuração para JSON');
        }

        if (file_put_contents($filePath, $json) === false) {
            throw new Exception("Erro ao salvar arquivo de configuração: {$filePath}");
        }
    }

    /**
     * Obtém uma mensagem de erro pelo seu código.
     *
     * @param string $key A chave da mensagem de erro.
     * @return string A mensagem de erro correspondente, ou 'Erro desconhecido' se a chave não existir.
     */
    public static function getErrorMessage(string $key): string
    {
        return self::ERROR_MESSAGES[$key] ?? 'Erro desconhecido';
    }
}