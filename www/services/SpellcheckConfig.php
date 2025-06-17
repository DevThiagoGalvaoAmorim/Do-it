<?php

/**
 * Configuração do Serviço de Correção Ortográfica
 * 
 * Esta classe gerencia todas as configurações relacionadas ao serviço de correção ortográfica,
 * incluindo idiomas suportados, caminhos de dicionários e limites de processamento.
 */
class SpellcheckConfig
{
    private $dictionaryPath;
    private $supportedLanguages;
    private $maxWordLength;
    private $minWordLength;
    private $cacheEnabled;
    private $cacheTimeout;
    private $maxTextLength;
    
    public function __construct(array $config = [])
    {
        $this->initializeDefaults();
        $this->loadConfiguration($config);
    }
    
    /**
     * Inicializa configurações padrão
     */
    private function initializeDefaults(): void
    {
        $this->dictionaryPath = __DIR__ . '/../public/Javascript/dictionaries/';
        $this->maxWordLength = 50;
        $this->minWordLength = 2;
        $this->cacheEnabled = true;
        $this->cacheTimeout = 3600; // 1 hora
        $this->maxTextLength = 10000; // 10KB
        
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
     * @param array $config
     */
    private function loadConfiguration(array $config): void
    {
        if (isset($config['dictionary_path'])) {
            $this->dictionaryPath = $config['dictionary_path'];
        }
        
        if (isset($config['max_word_length'])) {
            $this->maxWordLength = (int) $config['max_word_length'];
        }
        
        if (isset($config['min_word_length'])) {
            $this->minWordLength = (int) $config['min_word_length'];
        }
        
        if (isset($config['cache_enabled'])) {
            $this->cacheEnabled = (bool) $config['cache_enabled'];
        }
        
        if (isset($config['cache_timeout'])) {
            $this->cacheTimeout = (int) $config['cache_timeout'];
        }
        
        if (isset($config['max_text_length'])) {
            $this->maxTextLength = (int) $config['max_text_length'];
        }
        
        if (isset($config['supported_languages'])) {
            $this->supportedLanguages = array_merge(
                $this->supportedLanguages,
                $config['supported_languages']
            );
        }
    }
    
    /**
     * Obtém o caminho dos dicionários
     * 
     * @return string
     */
    public function getDictionaryPath(): string
    {
        return $this->dictionaryPath;
    }
    
    /**
     * Define o caminho dos dicionários
     * 
     * @param string $path
     */
    public function setDictionaryPath(string $path): void
    {
        $this->dictionaryPath = rtrim($path, '/') . '/';
    }
    
    /**
     * Obtém os idiomas suportados
     * 
     * @return array
     */
    public function getSupportedLanguages(): array
    {
        return $this->supportedLanguages;
    }
    
    /**
     * Adiciona um novo idioma suportado
     * 
     * @param string $code Código do idioma (ex: pt_BR)
     * @param array $config Configuração do idioma
     */
    public function addSupportedLanguage(string $code, array $config): void
    {
        $requiredFields = ['name', 'dictionary_file', 'affix_file', 'encoding'];
        
        foreach ($requiredFields as $field) {
            if (!isset($config[$field])) {
                throw new InvalidArgumentException("Campo obrigatório '{$field}' não encontrado na configuração do idioma");
            }
        }
        
        $this->supportedLanguages[$code] = $config;
    }
    
    /**
     * Verifica se um idioma é suportado
     * 
     * @param string $languageCode
     * @return bool
     */
    public function isLanguageSupported(string $languageCode): bool
    {
        return isset($this->supportedLanguages[$languageCode]);
    }
    
    /**
     * Obtém configuração de um idioma específico
     * 
     * @param string $languageCode
     * @return array|null
     */
    public function getLanguageConfig(string $languageCode): ?array
    {
        return $this->supportedLanguages[$languageCode] ?? null;
    }
    
    /**
     * Obtém o comprimento máximo de palavra
     * 
     * @return int
     */
    public function getMaxWordLength(): int
    {
        return $this->maxWordLength;
    }
    
    /**
     * Define o comprimento máximo de palavra
     * 
     * @param int $length
     */
    public function setMaxWordLength(int $length): void
    {
        if ($length < 1) {
            throw new InvalidArgumentException('Comprimento máximo deve ser maior que 0');
        }
        
        $this->maxWordLength = $length;
    }
    
    /**
     * Obtém o comprimento mínimo de palavra
     * 
     * @return int
     */
    public function getMinWordLength(): int
    {
        return $this->minWordLength;
    }
    
    /**
     * Define o comprimento mínimo de palavra
     * 
     * @param int $length
     */
    public function setMinWordLength(int $length): void
    {
        if ($length < 1) {
            throw new InvalidArgumentException('Comprimento mínimo deve ser maior que 0');
        }
        
        $this->minWordLength = $length;
    }
    
    /**
     * Verifica se o cache está habilitado
     * 
     * @return bool
     */
    public function isCacheEnabled(): bool
    {
        return $this->cacheEnabled;
    }
    
    /**
     * Habilita ou desabilita o cache
     * 
     * @param bool $enabled
     */
    public function setCacheEnabled(bool $enabled): void
    {
        $this->cacheEnabled = $enabled;
    }
    
    /**
     * Obtém o timeout do cache em segundos
     * 
     * @return int
     */
    public function getCacheTimeout(): int
    {
        return $this->cacheTimeout;
    }
    
    /**
     * Define o timeout do cache em segundos
     * 
     * @param int $timeout
     */
    public function setCacheTimeout(int $timeout): void
    {
        if ($timeout < 0) {
            throw new InvalidArgumentException('Timeout do cache deve ser maior ou igual a 0');
        }
        
        $this->cacheTimeout = $timeout;
    }
    
    /**
     * Obtém o comprimento máximo de texto
     * 
     * @return int
     */
    public function getMaxTextLength(): int
    {
        return $this->maxTextLength;
    }
    
    /**
     * Define o comprimento máximo de texto
     * 
     * @param int $length
     */
    public function setMaxTextLength(int $length): void
    {
        if ($length < 1) {
            throw new InvalidArgumentException('Comprimento máximo de texto deve ser maior que 0');
        }
        
        $this->maxTextLength = $length;
    }
    
    /**
     * Obtém o caminho completo para um arquivo de dicionário
     * 
     * @param string $languageCode
     * @param string $fileType 'dictionary' ou 'affix'
     * @return string|null
     */
    public function getDictionaryFilePath(string $languageCode, string $fileType = 'dictionary'): ?string
    {
        $languageConfig = $this->getLanguageConfig($languageCode);
        
        if (!$languageConfig) {
            return null;
        }
        
        $fileName = $fileType === 'affix' 
            ? $languageConfig['affix_file'] 
            : $languageConfig['dictionary_file'];
            
        return $this->dictionaryPath . $fileName;
    }
    
    /**
     * Valida a configuração atual
     * 
     * @return array Lista de erros de validação (vazio se válido)
     */
    public function validate(): array
    {
        $errors = [];
        
        // Verifica se o diretório de dicionários existe
        if (!is_dir($this->dictionaryPath)) {
            $errors[] = "Diretório de dicionários não encontrado: {$this->dictionaryPath}";
        }
        
        // Verifica se há pelo menos um idioma suportado
        if (empty($this->supportedLanguages)) {
            $errors[] = 'Nenhum idioma suportado configurado';
        }
        
        // Valida configurações de cada idioma
        foreach ($this->supportedLanguages as $code => $config) {
            $requiredFields = ['name', 'dictionary_file', 'affix_file', 'encoding'];
            
            foreach ($requiredFields as $field) {
                if (!isset($config[$field]) || empty($config[$field])) {
                    $errors[] = "Campo '{$field}' obrigatório não encontrado para idioma '{$code}'";
                }
            }
            
            // Verifica se os arquivos de dicionário existem
            if (is_dir($this->dictionaryPath)) {
                $dictFile = $this->dictionaryPath . $config['dictionary_file'];
                $affixFile = $this->dictionaryPath . $config['affix_file'];
                
                if (!file_exists($dictFile)) {
                    $errors[] = "Arquivo de dicionário não encontrado: {$dictFile}";
                }
                
                if (!file_exists($affixFile)) {
                    $errors[] = "Arquivo de afixos não encontrado: {$affixFile}";
                }
            }
        }
        
        // Valida limites
        if ($this->minWordLength >= $this->maxWordLength) {
            $errors[] = 'Comprimento mínimo de palavra deve ser menor que o máximo';
        }
        
        if ($this->maxTextLength < 100) {
            $errors[] = 'Comprimento máximo de texto muito pequeno (mínimo: 100 caracteres)';
        }
        
        return $errors;
    }
    
    /**
     * Exporta a configuração atual como array
     * 
     * @return array
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
            'max_text_length' => $this->maxTextLength
        ];
    }
    
    /**
     * Carrega configuração de um arquivo JSON
     * 
     * @param string $filePath
     * @return SpellcheckConfig
     * @throws Exception
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
     * @param string $filePath
     * @throws Exception
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
}