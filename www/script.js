const toggleBtn = document.querySelector('.toggle-btn'); 
const sidebar = document.querySelector('.sidebar'); 

// Array para simular o banco de dados 
let notasSimuladas = []; 

document.addEventListener('DOMContentLoaded', function() {
  carregarNotas();
  
  // Adicionar event listener para o formulário de perfil
  const formPerfil = document.getElementById('formPerfil');
  if (formPerfil) {
    formPerfil.addEventListener('submit', atualizarPerfil);
  }
  
  // Verificar se o astronauta está carregado corretamente
  const perfilAvatar = document.querySelector('.perfil-avatar img');
  if (perfilAvatar) {
    perfilAvatar.addEventListener('error', function() {
      console.error('Erro ao carregar imagem do astronauta');
      // Fallback para o ícone SVG caso a imagem não carregue
      this.parentNode.innerHTML = `
        <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="currentColor"
          class="bi bi-person-circle" viewBox="0 0 16 16">
          <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
          <path fill-rule="evenodd"
            d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
        </svg>
      `;
    });
  }
});

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
    
    // Restaura o scroll quando o popup é fechado
    document.body.style.overflow = 'auto';
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
  document.body.style.overflow = 'hidden'; // Impede rolagem da página quando o popup está aberto
} 

// Função para atualizar perfil do usuário
function atualizarPerfil(event) {
  event.preventDefault();
  
  const nome = document.getElementById('nome').value.trim();
  const email = document.getElementById('email').value.trim();
  const senha = document.getElementById('senha').value;
  const confirmarSenha = document.getElementById('confirmar_senha').value;
  
  // Validação básica
  if (!nome || !email) {
    alert('Nome e email são obrigatórios!');
    return;
  }
  
  // Validação de senha (apenas se o usuário estiver tentando alterar a senha)
  if (senha && senha !== confirmarSenha) {
    alert('As senhas não coincidem!');
    return;
  }
  
  // Criar FormData para enviar ao servidor
  const formData = new FormData(document.getElementById('formPerfil'));
  
  // Enviar dados para o servidor
  fetch('atualizar_perfil.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      alert('Perfil atualizado com sucesso!');
      fecharPopup(event, document.getElementById('popupPerfil'));
    } else {
      alert('Erro ao atualizar perfil: ' + data.message);
    }
  })
  .catch(error => {
    console.error('Erro:', error);
    alert('Erro ao atualizar perfil. Por favor, tente novamente.');
  });
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