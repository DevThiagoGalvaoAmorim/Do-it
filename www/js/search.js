function searchItems() {
    const searchInput = document.getElementById('searchInput');
    const query = searchInput.value.trim();

    fetch(`search.php?query=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error('Error:', data.error);
                return;
            }
            displayResults(data);
        })
        .catch(error => console.error('Error:', error));
}

function displayResults(results) {
    const container = document.querySelector('.listagem_de_notas .notas');
    
   
    container.innerHTML = '';
    
    if (results.length === 0) {
        container.innerHTML = '<p class="no-results">Nenhum resultado encontrado.</p>';
        return;
    }
    
   
    results.forEach(nota => {
        const divNota = document.createElement('div');
        divNota.className = 'nota';
        divNota.dataset.id = nota.id;

        divNota.innerHTML = `
            <h4 class="nota-titulo">${nota.titulo}</h4>
            <p class="nota-texto">${nota.descricao}</p>
            <div class="nota-botoes">
                <button class="nota-botao">📦</button>
                <button type="button" class="archive_button nota-botao">🗑️</button>
                <button class="nota-botao">✏️</button>
            </div>
        `;

        divNota.addEventListener('click', (event) => {
            if (event.target.tagName === 'BUTTON') {
                return;
            }
            abrirPopupEditar('popupCriar', nota);
        });

        container.appendChild(divNota);
    });
}

function verificaEnter(event) {
    if (event.key === "Enter") {
        searchItems();
    }
}


function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}


const debouncedSearch = debounce(() => searchItems(), 300);


document.getElementById('searchInput').addEventListener('input', debouncedSearch);