const toggleBtn = document.querySelector('.toggle-btn');
const sidebar = document.querySelector('.sidebar');

// Array para simular o banco de dados
let notasSimuladas = [];

document.addEventListener('DOMContentLoaded', carregarNotas);

toggleBtn.addEventListener('click', () => {
    sidebar.classList.toggle('expanded');
    content.classList.toggle('pushed');
});

function abrirPopup(id) {
    document.getElementById(id).style.display = 'flex';
}
  
function fecharPopup(event, popupEl) {
    event.stopPropagation();
    popupEl.style.display = 'none';
}

function verificaEnter(event) {
    if (event.key === 'Enter') {
      event.preventDefault(); // impede envio se estiver dentro de um form
      // Chama a função desejada
      enviarFormulario(); 
    }
}
  
function enviarFormulario() {
    // Lógica de organização aqui
    alert('Buscando nota...');
}

function criarNota() {
    const titulo = document.querySelector('.titulo-input').value;
    const descricao = document.querySelector('.texto-input').value;

    if (!titulo || !descricao) {
        alert('Título e descrição são obrigatórios!');
        return;
    }

    const novaNota = {
        id: notasSimuladas.length + 1,
        titulo: titulo,
        descricao: descricao
    };
    notasSimuladas.push(novaNota);

    alert('Nota criada com sucesso!');
    carregarNotas(); // Atualizar a listagem de notas
}

function carregarNotas() {
    const container = document.querySelector('.listagem_de_notas .notas');
    container.innerHTML = ''; 

    notasSimuladas.forEach(nota => {
        const divNota = document.createElement('div');
        divNota.className = 'nota';
        divNota.setAttribute('onclick', "abrirPopup('popupCriar')");

        divNota.innerHTML = `
            <h4>${nota.titulo}</h4>
            <p>${nota.descricao}</p>
            <div class="icones">
                <button type="button" class="archive_button" onclick="deletarNota(${nota.id})">🗑️</button>
                <button type="button" class="">📥</button>
            </div>
        `;

        container.appendChild(divNota);
    });
}

function deletarNota(id) {
    notasSimuladas = notasSimuladas.filter(nota => nota.id !== id);
    alert('Nota deletada com sucesso!');
    carregarNotas(); // Atualizar a listagem de notas
}

// Função para abrir o popup de perfil do usuário
function abrirPerfilUsuario() {
  document.getElementById('popupPerfil').style.display = 'flex';
  document.body.style.overflow = 'hidden';
}

// Função para abrir a página de configurações
function abrirConfiguracoes() {
  // Implementar redirecionamento para a página de configurações
  alert('Funcionalidade de configurações em desenvolvimento');
}

// Função para abrir a página sobre
function abrirSobre() {
  // Implementar redirecionamento para a página sobre
  alert('Funcionalidade sobre em desenvolvimento');
}

// Função para sair
function sair() {
  window.location.href = 'logout.php';
}