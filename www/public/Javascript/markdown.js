// Configuração do Marked.js
if (typeof marked !== 'undefined') {
    marked.setOptions({
        breaks: true,
        gfm: true,
        sanitize: false // Usaremos DOMPurify para sanitização
    });
}

/**
 * Converte texto Markdown para HTML
 * @param {string} markdownText - Texto em formato Markdown
 * @returns {string} - HTML renderizado e sanitizado
 */
function markdownToHTML(markdownText) {
    if (!markdownText || typeof marked === 'undefined') {
        return markdownText || '';
    }
    
    try {
        // Converte Markdown para HTML
        const rawHTML = marked.parse(markdownText);
        
        // Sanitiza o HTML para segurança
        if (typeof DOMPurify !== 'undefined') {
            return DOMPurify.sanitize(rawHTML);
        }
        
        return rawHTML;
    } catch (error) {
        console.error('Erro ao processar Markdown:', error);
        return markdownText;
    }
}

/**
 * Renderiza Markdown em um elemento específico
 * @param {HTMLElement} element - Elemento onde renderizar o Markdown
 * @param {string} markdownText - Texto em formato Markdown
 */
function renderMarkdownInElement(element, markdownText) {
    if (!element) return;
    
    const htmlContent = markdownToHTML(markdownText);
    element.innerHTML = htmlContent;
    
    // Adiciona classe para estilização específica do Markdown
    element.classList.add('markdown-content');
}

/**
 * Detecta se o texto contém sintaxe Markdown
 * @param {string} text - Texto para verificar
 * @returns {boolean} - True se contém Markdown
 */
function hasMarkdownSyntax(text) {
    if (!text) return false;
    
    const markdownPatterns = [
        /#{1,6}\s+/, // Headers
        /\*\*.*\*\*/, // Bold
        /\*.*\*/, // Italic
        /\[.*\]\(.*\)/, // Links
        /```[\s\S]*```/, // Code blocks
        /`.*`/, // Inline code
        /^\s*[-*+]\s+/m, // Lists
        /^\s*\d+\.\s+/m, // Numbered lists
        /^>\s+/m // Blockquotes
    ];
    
    return markdownPatterns.some(pattern => pattern.test(text));
}

/**
 * Trunca texto Markdown para preview mantendo formatação básica
 * @param {string} markdownText - Texto em Markdown
 * @param {number} maxLength - Comprimento máximo
 * @returns {string} - Texto truncado
 */
function truncateMarkdown(markdownText, maxLength = 150) {
    if (!markdownText || markdownText.length <= maxLength) {
        return markdownText;
    }
    
    // Remove sintaxe Markdown complexa para preview
    let plainText = markdownText
        .replace(/#{1,6}\s+/g, '') // Remove headers
        .replace(/\*\*(.*?)\*\*/g, '$1') // Remove bold
        .replace(/\*(.*?)\*/g, '$1') // Remove italic
        .replace(/\[([^\]]+)\]\([^\)]+\)/g, '$1') // Remove links, mantém texto
        .replace(/```[\s\S]*?```/g, '[código]') // Substitui code blocks
        .replace(/`([^`]+)`/g, '$1') // Remove inline code
        .replace(/^>\s+/gm, '') // Remove blockquotes
        .replace(/^\s*[-*+]\s+/gm, '• ') // Simplifica listas
        .replace(/^\s*\d+\.\s+/gm, '• '); // Simplifica listas numeradas
    
    return plainText.length > maxLength 
        ? plainText.substring(0, maxLength) + '...' 
        : plainText;
}