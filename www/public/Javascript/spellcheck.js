let typo = null;
/**
 * Cliente JavaScript para Serviço de Correção Ortográfica
 * 
 * Refatorado para usar a camada de serviço server-side em vez de
 * processamento client-side com Typo.js
 */

let isLoading = false;
let debounceTimer = null;
let spellcheckCache = new Map();
let currentLanguage = 'pt_BR';

// Cache DOM elements
const textarea = document.querySelector('.texto-input');
const highlight = document.querySelector('.texto-highlight');
const popup = document.getElementById('popupCriar');

// Configuração da API
const API_BASE_URL = '/services/spellcheck_api.php';
const CACHE_TIMEOUT = 300000; // 5 minutos
const DEBOUNCE_DELAY = 500;

/**
 * Cliente para comunicação com a API de correção ortográfica
 */
class SpellcheckClient {
    constructor(baseUrl = API_BASE_URL) {
        this.baseUrl = baseUrl;
        this.cache = new Map();
    }
    
    /**
     * Faz requisição para a API
     */
    async makeRequest(endpoint, data = null, method = 'GET') {
        const url = `${this.baseUrl}/${endpoint}`;
        const options = {
            method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        };
        
        if (data && method === 'POST') {
            options.body = JSON.stringify(data);
        }
        
        try {
            const response = await fetch(url, options);
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            
            return await response.json();
        } catch (error) {
            console.error('API request failed:', error);
            throw error;
        }
    }
    
    /**
     * Verifica se uma palavra está correta
     */
    async checkWord(word, language = currentLanguage) {
        const cacheKey = `word_${word}_${language}`;
        
        // Verifica cache
        if (this.cache.has(cacheKey)) {
            const cached = this.cache.get(cacheKey);
            if (Date.now() - cached.timestamp < CACHE_TIMEOUT) {
                return cached.data;
            }
            this.cache.delete(cacheKey);
        }
        
        const result = await this.makeRequest('check-word', { word, language }, 'POST');
        
        // Armazena no cache
        this.cache.set(cacheKey, {
            data: result,
            timestamp: Date.now()
        });
        
        return result;
    }
    
    /**
     * Obtém sugestões para uma palavra
     */
    async getSuggestions(word, language = currentLanguage, limit = 5) {
        return await this.makeRequest('suggestions', { word, language, limit }, 'POST');
    }
    
    /**
     * Verifica um texto completo
     */
    async checkText(text, language = currentLanguage) {
        return await this.makeRequest('check-text', { text, language }, 'POST');
    }
    
    /**
     * Obtém idiomas suportados
     */
    async getSupportedLanguages() {
        const cacheKey = 'supported_languages';
        
        if (this.cache.has(cacheKey)) {
            const cached = this.cache.get(cacheKey);
            if (Date.now() - cached.timestamp < CACHE_TIMEOUT) {
                return cached.data;
            }
        }
        
        const result = await this.makeRequest('languages');
        
        this.cache.set(cacheKey, {
            data: result,
            timestamp: Date.now()
        });
        
        return result;
    }
    
    /**
     * Verifica se a API está funcionando
     */
    async healthCheck() {
        return await this.makeRequest('health');
    }
}

// Instância global do cliente
const spellcheckClient = new SpellcheckClient();

/**
 * Inicializa o serviço de correção ortográfica
 */
async function initializeSpellcheck() {
    if (isLoading) return;
    isLoading = true;
    
    try {
        // Verifica se a API está funcionando
        await spellcheckClient.healthCheck();
        
        if (highlight) {
            highlight.innerHTML = "Serviço de correção ortográfica carregado.";
        }
        
        console.log('Spellcheck service initialized successfully');
    } catch (error) {
        console.error('Failed to initialize spellcheck service:', error);
        if (highlight) {
            highlight.innerHTML = "Falha ao carregar serviço de correção ortográfica.";
        }
    } finally {
        isLoading = false;
    }
}

function escapeHtml(text) {
  return text.replace(/[&<>"']/g, function(m) {
    return ({
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      '"': '&quot;',
      "'": '&#39;'
    })[m];
  });
}

/**
 * Destaca palavras incorretas no texto usando a API
 */
async function highlightMisspelledWords(text) {
    if (!text || text.trim().length === 0) {
        return escapeHtml(text);
    }
    
    try {
        // Usa a API para verificar o texto completo
        const result = await spellcheckClient.checkText(text, currentLanguage);
        
        if (!result.success) {
            console.error('Spellcheck failed:', result.error);
            return escapeHtml(text);
        }
        
        let highlightedText = escapeHtml(text);
        
        // Destaca palavras incorretas
        if (result.misspelled_words && result.misspelled_words.length > 0) {
            result.misspelled_words.forEach(item => {
                const word = item.word;
                const regex = new RegExp(`\\b${word.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')}\\b`, 'gi');
                highlightedText = highlightedText.replace(regex, 
                    `<span class="misspelled" data-word="${word}" title="Clique para ver sugestões">${word}</span>`
                );
            });
        }
        
        return highlightedText;
        
    } catch (error) {
        console.error('Error highlighting misspelled words:', error);
        return escapeHtml(text);
    }
}

/**
 * Sincroniza o destaque com o textarea
 */
function syncHighlight() {
    if (!textarea || !highlight) return;
    
    if (!popup || popup.style.display === 'none') return;

    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(async () => {
        if (isLoading) return;
        
        const text = textarea.value;
        
        if (text.trim().length === 0) {
            highlight.innerHTML = '';
            return;
        }
        
        // Mostra indicador de carregamento
        highlight.innerHTML = '<span class="loading">Verificando ortografia...</span>';
        
        try {
            const highlightedText = await highlightMisspelledWords(text);
            highlight.innerHTML = highlightedText;
            
            // Adiciona event listeners para palavras incorretas
            addMisspelledWordListeners();
            
        } catch (error) {
            console.error('Error in syncHighlight:', error);
            highlight.innerHTML = escapeHtml(text);
        }

        // Sincroniza scroll
        requestAnimationFrame(() => {
            highlight.scrollTop = textarea.scrollTop;
        });
    }, DEBOUNCE_DELAY);
}

/**
 * Adiciona event listeners para palavras incorretas
 */
function addMisspelledWordListeners() {
    const misspelledWords = highlight.querySelectorAll('.misspelled');
    
    misspelledWords.forEach(span => {
        span.addEventListener('click', async (e) => {
            e.preventDefault();
            const word = span.getAttribute('data-word');
            await showSuggestions(word, span);
        });
        
        span.style.cursor = 'pointer';
    });
}

/**
 * Mostra sugestões para uma palavra incorreta
 */
async function showSuggestions(word, element) {
    try {
        const result = await spellcheckClient.getSuggestions(word, currentLanguage, 5);
        
        if (!result.success) {
            console.error('Failed to get suggestions:', result.error);
            return;
        }
        
        // Remove tooltip anterior se existir
        const existingTooltip = document.querySelector('.spellcheck-tooltip');
        if (existingTooltip) {
            existingTooltip.remove();
        }
        
        // Cria tooltip com sugestões
        const tooltip = createSuggestionsTooltip(word, result.suggestions, element);
        document.body.appendChild(tooltip);
        
        // Remove tooltip ao clicar fora
        setTimeout(() => {
            document.addEventListener('click', function removeTooltip(e) {
                if (!tooltip.contains(e.target) && e.target !== element) {
                    tooltip.remove();
                    document.removeEventListener('click', removeTooltip);
                }
            });
        }, 100);
        
    } catch (error) {
        console.error('Error showing suggestions:', error);
    }
}

/**
 * Cria tooltip com sugestões
 */
function createSuggestionsTooltip(word, suggestions, element) {
    const tooltip = document.createElement('div');
    tooltip.className = 'spellcheck-tooltip';
    
    const rect = element.getBoundingClientRect();
    tooltip.style.position = 'absolute';
    tooltip.style.left = rect.left + 'px';
    tooltip.style.top = (rect.bottom + 5) + 'px';
    tooltip.style.backgroundColor = '#fff';
    tooltip.style.border = '1px solid #ccc';
    tooltip.style.borderRadius = '4px';
    tooltip.style.padding = '8px';
    tooltip.style.boxShadow = '0 2px 8px rgba(0,0,0,0.1)';
    tooltip.style.zIndex = '1000';
    tooltip.style.maxWidth = '200px';
    
    let content = `<div style="font-weight: bold; margin-bottom: 5px;">"${word}"</div>`;
    
    if (suggestions && suggestions.length > 0) {
        content += '<div style="margin-bottom: 5px;">Sugestões:</div>';
        suggestions.forEach(suggestion => {
            content += `<div style="padding: 2px 0; cursor: pointer; color: #007bff;" 
                             onclick="replaceMisspelledWord('${word}', '${suggestion}')">${suggestion}</div>`;
        });
    } else {
        content += '<div style="color: #666;">Nenhuma sugestão encontrada</div>';
    }
    
    tooltip.innerHTML = content;
    return tooltip;
}

/**
 * Substitui palavra incorreta por sugestão
 */
function replaceMisspelledWord(oldWord, newWord) {
    if (!textarea) return;
    
    const text = textarea.value;
    const regex = new RegExp(`\\b${oldWord.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')}\\b`, 'g');
    textarea.value = text.replace(regex, newWord);
    
    // Remove tooltip
    const tooltip = document.querySelector('.spellcheck-tooltip');
    if (tooltip) {
        tooltip.remove();
    }
    
    // Atualiza destaque
    syncHighlight();
    
    // Foca no textarea
    textarea.focus();
}

/**
 * Configura seletor de idioma
 */
function createLanguageSelector() {
    const selector = document.createElement('select');
    selector.id = 'spellcheck-language';
    selector.style.margin = '5px';
    selector.style.padding = '2px';
    
    // Adiciona opção padrão
    const defaultOption = document.createElement('option');
    defaultOption.value = 'pt_BR';
    defaultOption.textContent = 'Português (BR)';
    selector.appendChild(defaultOption);
    
    // Carrega idiomas suportados
    spellcheckClient.getSupportedLanguages().then(result => {
        if (result.success && result.languages) {
            selector.innerHTML = ''; // Limpa opções
            
            Object.entries(result.languages).forEach(([code, config]) => {
                const option = document.createElement('option');
                option.value = code;
                option.textContent = config.name;
                selector.appendChild(option);
            });
        }
    }).catch(error => {
        console.error('Failed to load supported languages:', error);
    });
    
    // Event listener para mudança de idioma
    selector.addEventListener('change', (e) => {
        currentLanguage = e.target.value;
        syncHighlight(); // Re-verifica com novo idioma
    });
    
    return selector;
}

/**
 * Adiciona controles de correção ortográfica ao popup
 */
function addSpellcheckControls() {
    if (!popup) return;
    
    // Verifica se já existe
    if (popup.querySelector('#spellcheck-controls')) return;
    
    const controls = document.createElement('div');
    controls.id = 'spellcheck-controls';
    controls.style.padding = '5px';
    controls.style.borderBottom = '1px solid #eee';
    controls.style.fontSize = '12px';
    
    const label = document.createElement('label');
    label.textContent = 'Idioma: ';
    label.style.marginRight = '5px';
    
    const languageSelector = createLanguageSelector();
    
    const statusSpan = document.createElement('span');
    statusSpan.id = 'spellcheck-status';
    statusSpan.style.marginLeft = '10px';
    statusSpan.style.color = '#666';
    
    controls.appendChild(label);
    controls.appendChild(languageSelector);
    controls.appendChild(statusSpan);
    
    // Insere no início do popup
    popup.insertBefore(controls, popup.firstChild);
}

/**
 * Atualiza status da correção ortográfica
 */
function updateSpellcheckStatus(message, isError = false) {
    const statusElement = document.getElementById('spellcheck-status');
    if (statusElement) {
        statusElement.textContent = message;
        statusElement.style.color = isError ? '#d32f2f' : '#666';
    }
}

// Configura event listeners quando popup abre
function setupSpellcheck() {
    if (!popup) return;

    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.type === 'attributes' && 
                (mutation.attributeName === 'style' || mutation.attributeName === 'class')) {
                const isVisible = popup.style.display === 'block';
                
                if (isVisible && textarea) {
                    // Adiciona controles se não existirem
                    addSpellcheckControls();
                    
                    // Inicializa serviço
                    initializeSpellcheck().then(() => {
                        updateSpellcheckStatus('Pronto');
                    }).catch(error => {
                        updateSpellcheckStatus('Erro ao carregar', true);
                    });
                    
                    // Adiciona event listeners
                    textarea.addEventListener('input', syncHighlight);
                    textarea.addEventListener('scroll', () => {
                        if (highlight) {
                            highlight.scrollTop = textarea.scrollTop;
                        }
                    });
                    
                    // Inicia verificação
                    syncHighlight();
                    
                } else {
                    // Remove event listeners quando popup fecha
                    textarea?.removeEventListener('input', syncHighlight);
                    textarea?.removeEventListener('scroll', syncHighlight);
                    
                    // Limpa cache periodicamente
                    if (spellcheckClient && spellcheckClient.cache) {
                        spellcheckClient.cache.clear();
                    }
                }
            }
        });
    });

    observer.observe(popup, { 
        attributes: true, 
        attributeFilter: ['style', 'class'] 
    });
}

// Adiciona estilos CSS para correção ortográfica
function addSpellcheckStyles() {
    const style = document.createElement('style');
    style.textContent = `
        .misspelled {
            background-color: #ffebee;
            border-bottom: 2px wavy #f44336;
            cursor: pointer;
        }
        
        .misspelled:hover {
            background-color: #ffcdd2;
        }
        
        .spellcheck-tooltip {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        
        .spellcheck-tooltip div:hover {
            background-color: #f5f5f5;
        }
        
        .loading {
            color: #666;
            font-style: italic;
        }
        
        #spellcheck-controls {
            background-color: #f9f9f9;
        }
        
        #spellcheck-language {
            border: 1px solid #ddd;
            border-radius: 3px;
        }
    `;
    document.head.appendChild(style);
}

// Inicialização
document.addEventListener('DOMContentLoaded', () => {
    addSpellcheckStyles();
    setupSpellcheck();
});

// Torna funções globais para uso em tooltips
window.replaceMisspelledWord = replaceMisspelledWord;
