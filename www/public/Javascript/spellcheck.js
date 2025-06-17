let isLoading = false;
let debounceTimer = null;
let spellcheckCache = new Map();

// Cache DOM elements
const textarea = document.querySelector('.texto-input');
const highlight = document.querySelector('.texto-highlight');
const popup = document.getElementById('popupCriar');

// API configuration
const SPELLCHECK_API_URL = '/services/spellcheck_api.php';
const CACHE_EXPIRY = 5 * 60 * 1000; // 5 minutes

// Check word using API with caching
async function checkWordWithAPI(word) {
    const cacheKey = `check_${word.toLowerCase()}`;
    const cached = spellcheckCache.get(cacheKey);
    
    if (cached && Date.now() - cached.timestamp < CACHE_EXPIRY) {
        return cached.result;
    }
    
    try {
        const response = await fetch(SPELLCHECK_API_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=check&word=${encodeURIComponent(word)}&language=pt_BR`
        });
        
        const data = await response.json();
        
        if (data.success) {
            const result = data.data.correct;
            spellcheckCache.set(cacheKey, {
                result: result,
                timestamp: Date.now()
            });
            return result;
        } else {
            console.error('Spellcheck API error:', data.error);
            return true; // Return true on error to avoid false positives
        }
    } catch (error) {
        console.error('Failed to check word:', error);
        return true; // Return true on error to avoid false positives
    }
}

// Get suggestions using API
async function getSuggestionsWithAPI(word) {
    try {
        const response = await fetch(SPELLCHECK_API_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=suggest&word=${encodeURIComponent(word)}&language=pt_BR&max_suggestions=5`
        });
        
        const data = await response.json();
        
        if (data.success) {
            return data.data.suggestions;
        } else {
            console.error('Suggestions API error:', data.error);
            return [];
        }
    } catch (error) {
        console.error('Failed to get suggestions:', error);
        return [];
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

async function highlightMisspelled(text) {
    const escapedText = escapeHtml(text);
    const words = text.match(/\b\w{3,}\b/g) || [];
    
    if (words.length === 0) {
        return escapedText;
    }
    
    // Check all words in parallel
    const wordChecks = await Promise.all(
        words.map(async word => ({
            word: word,
            correct: await checkWordWithAPI(word)
        }))
    );
    
    // Create a map for quick lookup
    const wordCorrectness = new Map();
    wordChecks.forEach(({word, correct}) => {
        wordCorrectness.set(word, correct);
    });
    
    // Replace words with highlighted versions if misspelled
    return escapedText.replace(/\b\w{3,}\b/g, word => 
        wordCorrectness.get(word) ? word : `<span class="misspelled">${word}</span>`
    );
}

function syncHighlight() {
    if (!textarea || !highlight) return;
    
    if (!popup || popup.style.display === 'none') return;

    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(async () => {
        if (isLoading) return;
        
        isLoading = true;
        try {
            const highlightedText = await highlightMisspelled(textarea.value);
            highlight.innerHTML = highlightedText;

            // Use requestAnimationFrame for scroll sync
            requestAnimationFrame(() => {
                highlight.scrollTop = textarea.scrollTop;
            });
        } catch (error) {
            console.error('Error highlighting text:', error);
            highlight.innerHTML = escapeHtml(textarea.value);
        } finally {
            isLoading = false;
        }
    }, 500);
}

// Attach event listeners only when popup opens
function setupSpellcheck() {
    if (!popup) return;

    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.type === 'attributes' && 
                (mutation.attributeName === 'style' || mutation.attributeName === 'class')) {
                const isVisible = popup.style.display === 'block';
                
                if (isVisible && textarea) {
                    textarea.addEventListener('input', syncHighlight);
                    textarea.addEventListener('scroll', () => {
                        if (highlight) {
                            highlight.scrollTop = textarea.scrollTop;
                        }
                    });
                    syncHighlight();
                } else {
                    textarea?.removeEventListener('input', syncHighlight);
                    textarea?.removeEventListener('scroll', syncHighlight);
                }
            }
        });
    });

    observer.observe(popup, { 
        attributes: true, 
        attributeFilter: ['style', 'class'] 
    });
}

document.addEventListener('DOMContentLoaded', setupSpellcheck);
