let typo = null;
let isLoading = false;
let debounceTimer = null;

// Load dictionary only when needed
async function loadDictionary() {
    if (typo || isLoading) return;
    isLoading = true;
    
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
    if (!typo) return text;
    return text.replace(/\b\w{3,}\b/g, word => 
        typo.check(word) ? word : `<span class="misspelled">${word}</span>`
    );
}

function syncHighlight() {
    const textarea = document.querySelector('.texto-input');
    const highlight = document.querySelector('.texto-highlight');
    
    if (!textarea || !highlight) return;
    
    // Only process if popup is visible
    const popup = document.getElementById('popupCriar');
    if (!popup || popup.style.display === 'none') return;

    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(async () => {
        if (!typo) await loadDictionary();
        highlight.innerHTML = highlightMisspelled(textarea.value);
        highlight.scrollTop = textarea.scrollTop;
    }, 500); // Increased debounce time
}

// Attach event listeners only when popup opens
function setupSpellcheck() {
    const popup = document.getElementById('popupCriar');
    if (!popup) return;

    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.type === 'attributes' && 
                (mutation.attributeName === 'style' || mutation.attributeName === 'class')) {
                const isVisible = popup.style.display === 'block';
                const textarea = document.querySelector('.texto-input');
                
                if (isVisible && textarea) {
                    textarea.addEventListener('input', syncHighlight);
                    textarea.addEventListener('scroll', () => {
                        const highlight = document.querySelector('.texto-highlight');
                        if (highlight) highlight.scrollTop = textarea.scrollTop;
                    });
                    loadDictionary();
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
