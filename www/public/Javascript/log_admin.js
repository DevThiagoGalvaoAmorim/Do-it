
document.getElementById('searchLogInput').addEventListener('input', function() {
    filtrarTabela();
});

function filtrarTabela() {
    const termoBusca = document.getElementById('searchLogInput').value.toLowerCase();
    const idUsuarioFiltro = document.getElementById('filterUserId').value;
    const linhas = document.querySelectorAll('#logTableBody tr');

    linhas.forEach(linha => {
        const colunas = linha.querySelectorAll('td');
        const idUsuario = colunas[0]?.textContent.trim();
        const dataHora = colunas[1]?.textContent.toLowerCase();
        const acao = colunas[2]?.textContent.toLowerCase();

        let exibir = true;

        // Filtro por ID do usuário
        if (idUsuarioFiltro && idUsuario !== idUsuarioFiltro) {
            exibir = false;
        }

        // Filtro por texto da barra de busca
        if (termoBusca && !(
            idUsuario.includes(termoBusca) ||
            dataHora.includes(termoBusca) ||
            acao.includes(termoBusca)
        )) {
            exibir = false;
        }

        linha.style.display = exibir ? '' : 'none';
    });
}

function filtrarPorUsuario() {
    filtrarTabela();
}

function limparFiltroUsuario() {
    document.getElementById('filterUserId').value = '';
    filtrarTabela();
}
