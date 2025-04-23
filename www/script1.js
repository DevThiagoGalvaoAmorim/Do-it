const toggleBtn = document.querySelector('.toggle-btn');
const sidebar = document.querySelector('.sidebar');

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

  // Criar um objeto FormData para enviar os dados
  const formData = new FormData();
  formData.append('action', 'create');
  formData.append('titulo', titulo);
  formData.append('descricao', descricao);

  // Enviar os dados para o servidor usando fetch
  fetch('notas_crud.php', {
      method: 'POST',
      body: formData
  })
  .then(response => response.json())
  .then(data => {
      if (data.success) {
          alert(data.message); 
      } else {
          alert(data.message); 
      }
  })
  .catch(error => {
      console.error('Erro:', error);
      alert('Ocorreu um erro ao criar a nota.');
  });
}

function carregarNotas() {
  // Enviar uma requisição para buscar as notas
  fetch('http://localhost/Do-it/www/conexao_db/notas_crud.php', {
      method: 'POST',
      headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: 'action=read'
  })
  .then(response => response.json())
  .then(notas => {
      const container = document.querySelector('.listagem_de_notas .notas');
      container.innerHTML = ''; 

      // Iterar pelas notas e criar as divs
      notas.forEach(nota => {
          const divNota = document.createElement('div');
          divNota.className = 'nota';
          divNota.setAttribute('onclick', "abrirPopup('popupCriar')");

          divNota.innerHTML = `
              <h4>${nota.titulo}</h4>
              <p>${nota.descricao}</p>
              <div class="icones">
                  <button type="button" class="archive_button">🗑️</button>
                  <button type="button" class="">📥</button>
              </div>
          `;

          container.appendChild(divNota);
      });
  })
  .catch(error => {
      console.error('Erro ao carregar notas:', error);
      alert('Ocorreu um erro ao carregar as notas.');
  });
}