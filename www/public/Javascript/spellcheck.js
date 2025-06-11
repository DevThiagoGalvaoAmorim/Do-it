let typo = null;
let isLoading = false;
let debounceTimer = null;

// Cache DOM elements
const textarea = document.querySelector('.texto-input');
const highlight = document.querySelector('.texto-highlight');
const popup = document.getElementById('popupCriar');

// Load dictionary only when needed
async function loadDictionary() {
    if (typo || isLoading) return;
    isLoading = true;

    // Show loading indicator (optional)
    if (highlight) {
        highlight.innerHTML = "Loading dictionary...";
    }
    
    try {
        const [affResponse, dicResponse] = await Promise.all([
            fetch('/public/Javascript/dicionarios/index.aff'),
            fetch('/public/Javascript/dicionarios/index.dic')
        ]);

        const [aff, dic] = await Promise.all([
            affResponse.text(),
            dicResponse.text()
        ]);

        typo = new Typo('pt_BR', aff, dic, { platform: 'any' });
    } catch (error) {
        console.error('Failed to load dictionary:', error);
        if (highlight) {
            highlight.innerHTML = "Failed to load dictionary.";
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

function highlightMisspelled(text) {
    if (!typo) return escapeHtml(text);
    return escapeHtml(text).replace(/\b\w{3,}\b/g, word => 
        typo.check(word) ? word : `<span class="misspelled">${word}</span>`
    );
}

function syncHighlight() {
    if (!textarea || !highlight) return;
    
    if (!popup || popup.style.display === 'none') return;

    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(async () => {
        if (!typo) await loadDictionary();
        highlight.innerHTML = highlightMisspelled(textarea.value);

        // Use requestAnimationFrame for scroll sync (optional)
        requestAnimationFrame(() => {
            highlight.scrollTop = textarea.scrollTop;
        });
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
                    loadDictionary();
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
