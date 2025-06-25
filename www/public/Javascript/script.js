let ascending = true;  // Add this variable at the top of your script file
let dateAscending = true;


// Adicionar evento ao botão de deletar
document.addEventListener("click", (event) => {
  if (event.target.classList.contains("archive_button")) {
    const notaEl = event.target.closest(".nota"); // Seleciona o elemento da nota
    deletarNota(notaEl); // Passa o elemento da nota para a função
  }
  
  // Adicionar evento ao botão de arquivar
  if (event.target.classList.contains("arquivar_button")) {
    const notaEl = event.target.closest(".nota"); // Seleciona o elemento da nota
    arquivarNota(notaEl); // Passa o elemento da nota para a função
  }
});

document.addEventListener("DOMContentLoaded", carregarNotas);

const toggleBtn = document.querySelector(".toggle-btn");
const sidebar = document.querySelector(".sidebar");
toggleBtn.addEventListener("click", () => {
  sidebar.classList.toggle("expanded");
  content.classList.toggle("pushed");
});

// Adicionar event listeners para preview de mídia
document.addEventListener('DOMContentLoaded', function() {
  const imagemInput = document.getElementById('imagem-input');
  const videoInput = document.getElementById('video-input');
  
  if (imagemInput) {
    imagemInput.addEventListener('change', function(e) {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          const previewImg = document.getElementById('preview-img');
          const imagemPreview = document.querySelector('.imagem-preview');
          previewImg.src = e.target.result;
          imagemPreview.style.display = 'block';
        };
        reader.readAsDataURL(file);
      }
    });
  }
  
  if (videoInput) {
    videoInput.addEventListener('change', function(e) {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          const previewVideo = document.getElementById('preview-video');
          const videoPreview = document.querySelector('.video-preview');
          previewVideo.src = e.target.result;
          videoPreview.style.display = 'block';
        };
        reader.readAsDataURL(file);
      }
    });
  }
});

function removerImagem() {
  const imagemInput = document.getElementById('imagem-input');
  const imagemPreview = document.querySelector('.imagem-preview');
  imagemInput.value = '';
  imagemPreview.style.display = 'none';
}

function removerVideo() {
  const videoInput = document.getElementById('video-input');
  const videoPreview = document.querySelector('.video-preview');
  videoInput.value = '';
  videoPreview.style.display = 'none';
}

function abrirPopupCriar(id) {
  const popup = document.getElementById(id);

  // Limpa os campos do formulário ao abrir o popup
  const tituloInput = document.querySelector(".titulo-input");
  const descricaoInput = document.querySelector(".texto-input");
  const idInput = document.querySelector(".id-input");
  const imagemInput = document.getElementById('imagem-input');
  const videoInput = document.getElementById('video-input');

  if (tituloInput) tituloInput.value = "";
  if (descricaoInput) descricaoInput.value = ""; 
  if (idInput) idInput.value = "";
  if (imagemInput) imagemInput.value = "";
  if (videoInput) videoInput.value = "";
  
  // Limpar previews
  removerImagem();
  removerVideo();

  popup.style.display = "flex";
}

function salvarNota() {
  const titulo = document.querySelector(".titulo-input").value.trim();
  const descricao = document.querySelector(".texto-input").value.trim();
  const pasta = document.querySelector(".pasta-input")
    ? document.querySelector(".pasta-input").value.trim()
    : "";
  const id = document.querySelector(".id-input").value.trim();
  const imagemInput = document.getElementById('imagem-input');
  const videoInput = document.getElementById('video-input');

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
  
  // Adicionar arquivos se selecionados
  if (imagemInput.files[0]) {
    formData.append("imagem", imagemInput.files[0]);
  }
  if (videoInput.files[0]) {
    formData.append("video", videoInput.files[0]);
  }

  // Decidir entre criar ou atualizar
  if (id) {
    // Acionar o update
    formData.append("action", "update");
    formData.append("id", id);
    fetch("/models/notas_crud.php", {
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
    fetch("/models/notas_crud.php", {
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

function carregarNotas() {
  fetch("/models/notas_crud.php", {
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
          // Ignore clicks on any button, especially the edit button
          if (event.target.tagName === "BUTTON" || event.target.classList.contains("edit_button")) {
            return;
          }

          // Preenche os campos do popup com os dados da nota
          const tituloInput = document.querySelector(".titulo-input");
          const descricaoInput = document.querySelector(".texto-input");
          const idInput = document.querySelector(".id-input");
          
          if (tituloInput && descricaoInput && idInput) {
            tituloInput.value = nota.titulo;
            descricaoInput.value = nota.descricao;
            idInput.value = nota.id;
            console.log("ID da nota:", nota.id);
          }

          abrirPopupEditar("popupCriar", nota);
        });
        
        // Construir HTML da nota com mídia
        let mediaHtml = '';
        if (nota.imagem_url) {
          mediaHtml += `<img src="${nota.imagem_url}" alt="Imagem da nota" style="max-width: 100%; height: auto; margin: 10px 0;">`;
        }
        if (nota.video_url) {
          mediaHtml += `<video src="${nota.video_url}" controls style="max-width: 100%; height: auto; margin: 10px 0;"></video>`;
        }
        
        divNota.innerHTML = `
          <h4 class="nota-titulo">${nota.titulo}</h4>
          <p class="nota-texto">${nota.descricao}</p>
          ${mediaHtml}
          <div class="nota-botoes">
            <button type="button" class="arquivar_button nota-botao" title="Arquivar">�</button>
            <button type="button" class="archive_button nota-botao" title="Excluir">🗑️</button>
            <button class="nota-botao edit_button" title="Editar">✏️</button>
          </div>
        `;

        // Se tem Markdown, renderiza o preview com formatação
        const editBtn = divNota.querySelector('.edit_button'); // The ✏️ button
        editBtn.addEventListener('click', (event) => {
          event.stopPropagation(); // Prevent triggering the note's main click
          abrirPopupEditar("popupCriar", nota);
        });

        container.appendChild(divNota);
      });
    })
    .catch((error) => {
      console.error("Erro ao carregar notas:", error);
    });
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

  // Decidir entre criar ou atualizar
  if (id) {
    // Acionar o update
    formData.append("action", "update");
    formData.append("id", id); // Inclui o ID da nota no update
    fetch("/models/notas_crud.php", {
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
    fetch("/models/notas_crud.php", {
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
  fetch("/models/notas_crud.php", {
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
          // Ignore clicks on any button, especially the edit button
          if (event.target.tagName === "BUTTON" || event.target.classList.contains("edit_button")) {
            return;
          }

          // Preenche os campos do popup com os dados da nota
          const tituloInput = document.querySelector(".titulo-input");
          const descricaoInput = document.querySelector(".texto-input");
          const idInput = document.querySelector(".id-input");
          
          if (tituloInput && descricaoInput && idInput) {
            tituloInput.value = nota.titulo;
            descricaoInput.value = nota.descricao;
            idInput.value = nota.id;
            console.log("ID da nota:", nota.id);
          }

          abrirPopupEditar("popupCriar", nota);
        });
        
        // Detecta se tem Markdown e renderiza adequadamente
        const isMarkdown = hasMarkdownSyntax(nota.descricao);
        const previewText = isMarkdown 
          ? truncateMarkdown(nota.descricao, 150)
          : nota.descricao.length > 150 
            ? nota.descricao.substring(0, 150) + '...'
            : nota.descricao;
        
        divNota.innerHTML = `
          <h4 class="nota-titulo">${nota.titulo}</h4>
          <div class="nota-texto-container">
            <p class="nota-texto">${previewText}</p>
          </div>
          <div class="nota-botoes">
            <button type="button" class="arquivar_button nota-botao" title="Arquivar">�</button>
            <button type="button" class="archive_button nota-botao" title="Excluir">🗑️</button>
            <button class="nota-botao edit_button" title="Editar">✏️</button>
          </div>
        `;
        
        // Se tem Markdown, renderiza o preview com formatação
        if (isMarkdown) {
          const textoContainer = divNota.querySelector('.nota-texto');
          renderMarkdownInElement(textoContainer, previewText);
        }

        // Add this after divNota.innerHTML = ...
        const editBtn = divNota.querySelector('.edit_button'); // The ✏️ button
        editBtn.addEventListener('click', (event) => {
          event.stopPropagation(); // Prevent triggering the note's main click
          abrirPopupEditar("popupCriar", nota);
        });

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

  fetch("/models/notas_crud.php", {
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


// Hover do perfil do usuário
document.addEventListener("DOMContentLoaded", function () {
  const avatar = document.getElementById("userAvatar");
  const hover = document.getElementById("profileHover");

  if (avatar && hover) {
    avatar.addEventListener("mouseenter", () => {
      hover.style.display = "block";
    });
    avatar.addEventListener("mouseleave", () => {
      setTimeout(() => {
        if (!hover.matches(":hover")) hover.style.display = "none";
      }, 150);
    });
    hover.addEventListener("mouseleave", () => {
      hover.style.display = "none";
    });
    hover.addEventListener("mouseenter", () => {
      hover.style.display = "block";
    });
  }
});


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

// Função para arquivar uma nota
function arquivarNota(notaEl) {
  const id = notaEl.dataset.id; // Obtém o ID da nota do atributo data-id
  const formData = new FormData();
  formData.append("action", "archive");
  formData.append("id", id);

  fetch("/models/notas_crud.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        carregarNotas();
        // alert("Nota arquivada com sucesso!");
      } else {
        console.error("Erro ao arquivar nota:", data.message);
        // alert("Erro ao arquivar nota: " + data.message);
      }
    })
    .catch((error) => {
      console.error("Erro:", error);
      // alert("Erro ao arquivar nota.");
    });
}
