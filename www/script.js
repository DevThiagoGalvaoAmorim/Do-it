let ascending = true;  // Add this variable at the top of your script file
let dateAscending = true;

const toggleBtn = document.querySelector(".toggle-btn");
const sidebar = document.querySelector(".sidebar");

// Adicionar evento ao botão de deletar
document.addEventListener("click", (event) => {
  if (event.target.classList.contains("archive_button")) {
    const notaEl = event.target.closest(".nota"); // Seleciona o elemento da nota
    deletarNota(notaEl); // Passa o elemento da nota para a função
  }
});

document.addEventListener("DOMContentLoaded", carregarNotas);

toggleBtn.addEventListener("click", () => {
  sidebar.classList.toggle("expanded");
  content.classList.toggle("pushed");
});

function abrirPopupCriar(id) {
  const popup = document.getElementById(id);

  // Limpa os campos do formulário ao abrir o popup
  const tituloInput = document.querySelector(".titulo-input");
  const descricaoInput = document.querySelector(".texto-input");
  const idInput = document.querySelector(".id-input");

  if (tituloInput) tituloInput.value = "";
  if (descricaoInput) descricaoInput.value = ""; 
  if (idInput) idInput.value = "";

  popup.style.display = "flex";
}

function abrirPopupEditar(id, nota) {
  const popup = document.getElementById(id);

  // Preenche os campos do formulário com os dados da nota
  const tituloInput = document.querySelector(".titulo-input");
  const descricaoInput = document.querySelector(".texto-input");
  const idInput = document.querySelector(".id-input"); // Campo oculto para o ID

  if (tituloInput && descricaoInput && idInput) {
    tituloInput.value = nota.titulo;
    descricaoInput.value = nota.descricao;
    idInput.value = nota.id; // Define o ID da nota no campo oculto
  }

  popup.style.display = "flex"; // Exibe o popup
}

function fecharPopup(event, popupEl) {
  event.stopPropagation();
  popupEl.style.display = "none";

  // Limpa o campo oculto ao fechar o popup
  const idInput = document.querySelector(".id-input");
  if (idInput) {
    idInput.value = "";
  }
}

function verificaEnter(event) {
  if (event.key === "Enter") {
    event.preventDefault(); 
    enviarFormulario();
  }
}

function enviarFormulario() {
  // Lógica de organização aqui
  alert("Buscando nota...");
}

function salvarNota() {
  const titulo = document.querySelector(".titulo-input").value.trim();
  const descricao = document.querySelector(".texto-input").value.trim();
  const pasta = document.querySelector(".pasta-input")
    ? document.querySelector(".pasta-input").value.trim()
    : ""; // Caso tenha um campo para pasta
  const id_usuario = 1; // ID do usuário fixo no momento
  const id = document.querySelector(".id-input").value.trim(); // Obtém o ID da nota do campo oculto

  // Validação dos campos
  if (!titulo || !descricao) {
    alert("Título e descrição são obrigatórios.");
    return;
  }

  // Criar um objeto FormData para enviar os dados
  const formData = new FormData();
  formData.append("titulo", titulo);
  formData.append("descricao", descricao);
  formData.append("pasta", pasta);
  formData.append("id_usuario", id_usuario);

  // Decidir entre criar ou atualizar
  if (id) {
    // Acionar o update
    formData.append("action", "update");
    formData.append("id", id); // Inclui o ID da nota no update
    fetch("conexao_db/notas_crud.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          finalizarOperacao("Nota atualizada com sucesso!");
        } else {
          console.error("Erro ao atualizar nota:", data.message);
          alert("Erro ao atualizar a nota.");
        }
      })
      .catch((error) => {
        console.error("Erro:", error);
        alert("Erro ao atualizar a nota.");
      });
  } else {
    // Acionar o create
    formData.append("action", "create");
    fetch("conexao_db/notas_crud.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          finalizarOperacao("Nota criada com sucesso!");
        } else {
          console.error("Erro ao criar nota:", data.message);
          alert("Erro ao criar a nota.");
        }
      })
      .catch((error) => {
        console.error("Erro:", error);
        alert("Erro ao criar a nota.");
      });
  }
}

// Função para finalizar a operação (create e update)
function finalizarOperacao(mensagem) {
  carregarNotas();
  const popupCriar = document.getElementById("popupCriar");
  if (popupCriar) {
    popupCriar.style.display = "none";
  }
}

function carregarNotas() {
  fetch("conexao_db/notas_crud.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: "action=read",
  })
    .then((response) => response.json())
    .then((notas) => {
      const container = document.querySelector(".listagem_de_notas .notas");
      container.innerHTML = "";

      // Iterar pelas notas e criar as divs
      notas.forEach((nota) => {
        const divNota = document.createElement("div");
        divNota.className = "nota";
        divNota.dataset.id = nota.id;

        divNota.dataset.date = nota.data_hora;

        divNota.addEventListener("click", (event) => {
          if (event.target.tagName === "BUTTON") {
            return;
          }

          // Preenche os campos do popup com os dados da nota
          const tituloInput = document.querySelector(".titulo-input");
          const descricaoInput = document.querySelector(".texto-input");
          const idInput = document.querySelector(".id-input"); // Campo oculto para o ID
          
          if (tituloInput && descricaoInput && idInput) {
            tituloInput.value = nota.titulo;
            descricaoInput.value = nota.descricao;
            idInput.value = nota.id; // Define o ID da nota no campo oculto
            console.log("ID da nota:", nota.id); // Exibe o ID no console
          }

          // Abre o popup
          abrirPopupEditar("popupCriar", nota);
        });
        divNota.className = "nota";
        divNota.innerHTML = `
          <h4 class="nota-titulo">${nota.titulo}</h4>
          <p class="nota-texto">${nota.descricao}</p>
          <div class="nota-botoes">
        <button class="nota-botao">📦</button>
        <button type="button" class="archive_button nota-botao">🗑️</button>
        <button class="nota-botao">✏️</button>
    </div>
`;

        container.appendChild(divNota);
      });
    })
    .catch((error) => {
      console.error("Erro ao carregar notas:", error);
    });
}

function deletarNota(notaEl) {
  const id = notaEl.dataset.id; // Obtém o ID da nota do atributo data-id
  const formData = new FormData();
  formData.append("action", "delete");
  formData.append("id", id);

  fetch("conexao_db/notas_crud.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        carregarNotas();
      } else {
        console.error("Erro ao deletar nota:", data.message);
      }
    })
    .catch((error) => {
      console.error("Erro:", error);
    });
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


// Funções parar ordenar as notas
function sortNotes() {
  const container = document.querySelector('.listagem_de_notas .notas');
  const filterIcon = document.querySelector('.filtro-btn img');
  const notas = Array.from(container.children);

  notas.sort((a, b) => {
    const titleA = a.querySelector('.nota-titulo').textContent.toLowerCase();
    const titleB = b.querySelector('.nota-titulo').textContent.toLowerCase();
    return ascending ? titleA.localeCompare(titleB) : titleB.localeCompare(titleA);
  });

  container.innerHTML = '';
  notas.forEach(nota => {
    container.appendChild(nota);
  });

  //Roda icone de filtro
  filterIcon.classList.toggle('flip', !ascending);

  ascending = !ascending;
}

function sortByDate() {
  const container = document.querySelector('.listagem_de_notas .notas');
  const calendarIcon = document.querySelector('img[src*="calendar_down"]');
  const notas = Array.from(container.children);

  notas.sort((a, b) => {
    const dateA = new Date(a.dataset.date);
    const dateB = new Date(b.dataset.date);
    return dateAscending ? dateA - dateB : dateB - dateA;
  });

  container.innerHTML = '';
  notas.forEach(nota => {
    container.appendChild(nota);
  });

  calendarIcon.classList.toggle('flip', !dateAscending);
  dateAscending = !dateAscending;
}