document.addEventListener("DOMContentLoaded", function () {
  carregarUsuarios();

  function criarPopupEditarUsuario(id, nome, email) {
    // Remove popup antigo se existir
    const antigo = document.getElementById("editModal");
    if (antigo) antigo.remove();

    // Cria o HTML do popup
    const modal = document.createElement("div");
    modal.id = "editModal";
    modal.style.display = "flex";
    modal.innerHTML = `
      <div class="modal-content">
        <form id="editForm">
          <h3>Editar Usuário</h3>
          <input type="hidden" id="editId" value="${id}" />
          <div class="form-group">
            <label for="editNome">Nome</label>
            <input type="text" id="editNome" value="${nome}" required />
          </div>
          <div class="form-group">
            <label for="editEmail">Email</label>
            <input type="email" id="editEmail" value="${email}" required />
          </div>
          <div class="btn-group">
            <button type="submit" class="btn btn-primary">Salvar</button>
            <button type="button" class="btn btn-secondary" id="cancelarBtn">Cancelar</button>
          </div>
        </form>
      </div>
    `;
    document.body.appendChild(modal);

    // Evento de submit do formulário
    document.getElementById("editForm").addEventListener("submit", function (e) {
      e.preventDefault();
      atualizarUsuario();
    });

    // Evento de cancelar
    document.getElementById("cancelarBtn").addEventListener("click", closeModal);

    // Fecha modal ao clicar fora dele
    modal.addEventListener("click", function (e) {
      if (e.target === modal) {
        closeModal();
      }
    });
  }

  // Event delegation para botões de editar e deletar
  document.body.addEventListener("click", function (e) {
    if (e.target.classList.contains("edit-btn")) {
      const id = e.target.dataset.id;
      const nome = e.target.dataset.nome;
      const email = e.target.dataset.email;
      criarPopupEditarUsuario(id, nome, email);
    }

    if (e.target.classList.contains("delete-btn")) {
      if (confirm("Tem certeza que deseja excluir este usuário?")) {
        deletarUsuario(e.target.dataset.id);
      }
    }
  });

  // Função para busca em tempo real
  const searchInput = document.getElementById("searchInput");
  if (searchInput) {
    searchInput.addEventListener("input", function () {
      const termo = this.value.toLowerCase();
      const linhas = document.querySelectorAll(".users-table tbody tr");

      linhas.forEach((linha) => {
        const nome = linha.children[0]?.textContent.toLowerCase() || "";
        const email = linha.children[1]?.textContent.toLowerCase() || "";

        if (nome.includes(termo) || email.includes(termo) || termo === "") {
          linha.style.display = "";
        } else {
          linha.style.display = "none";
        }
      });
    });
  }

  // Torna as funções globais para uso no escopo da função criarPopupEditarUsuario
  window.criarPopupEditarUsuario = criarPopupEditarUsuario;
});

function carregarUsuarios() {
  fetch("/models/usuarios_api.php?action=listar")
    .then((res) => {
      if (!res.ok) {
        throw new Error(`HTTP error! status: ${res.status}`);
      }
      return res.json();
    })
    .then((usuarios) => {
      const tbody = document.querySelector(".users-table tbody");
      if (!tbody) return;

      tbody.innerHTML = "";
      if (usuarios && usuarios.length > 0) {
        usuarios.forEach((user) => {
          tbody.innerHTML += `
            <tr>
              <td>${escapeHtml(user.nome)}</td>
              <td>${escapeHtml(user.email)}</td>
              <td>
                <button class='action-btn edit-btn' data-id='${user.id}' data-nome='${escapeHtml(user.nome)}' data-email='${escapeHtml(user.email)}'>✏️</button>
                <button class='action-btn delete-btn' data-id='${user.id}'>🗑️</button>
              </td>
            </tr>
          `;
        });
      } else {
        tbody.innerHTML = "<tr><td colspan='3'>Nenhum usuário encontrado</td></tr>";
      }
    })
    .catch((error) => {
      console.error("Erro ao carregar usuários:", error);
      const tbody = document.querySelector(".users-table tbody");
      if (tbody) {
        tbody.innerHTML = "<tr><td colspan='3'>Erro ao carregar usuários</td></tr>";
      }
    });
}

function atualizarUsuario() {
  const id = document.getElementById("editId").value;
  const nome = document.getElementById("editNome").value;
  const email = document.getElementById("editEmail").value;

  // Validação básica
  if (!nome.trim() || !email.trim()) {
    alert("Por favor, preencha todos os campos.");
    return;
  }

  const formData = new FormData();
  formData.append("action", "atualizar");
  formData.append("id", id);
  formData.append("nome", nome.trim());
  formData.append("email", email.trim());

  fetch("/models/usuarios_api.php", {
    method: "POST",
    body: formData,
  })
    .then((res) => {
      if (!res.ok) {
        throw new Error(`HTTP error! status: ${res.status}`);
      }
      return res.json();
    })
    .then((resp) => {
      if (resp.success) {
        closeModal();
        carregarUsuarios();
      } else {
        alert(resp.message || "Erro ao atualizar usuário.");
      }
    })
    .catch((error) => {
      console.error("Erro ao atualizar usuário:", error);
      alert("Erro ao atualizar usuário. Tente novamente.");
    });
}

function deletarUsuario(id) {
  const formData = new FormData();
  formData.append("action", "deletar");
  formData.append("id", id);

  fetch("/models/usuarios_api.php", {
    method: "POST",
    body: formData,
  })
    .then((res) => {
      if (!res.ok) {
        throw new Error(`HTTP error! status: ${res.status}`);
      }
      return res.json();
    })
    .then((resp) => {
      if (resp.success) {
        carregarUsuarios();
        alert("Usuário excluído com sucesso!");
      } else {
        alert(resp.message || "Erro ao excluir usuário.");
      }
    })
    .catch((error) => {
      console.error("Erro ao excluir usuário:", error);
      alert("Erro ao excluir usuário. Tente novamente.");
    });
}

function closeModal() {
  const modal = document.getElementById("editModal");
  if (modal) {
    modal.style.display = "none";
    setTimeout(() => {
      modal.remove();
    }, 300);
  }
}

// Funções utilitárias
function escapeHtml(text) {
  const div = document.createElement("div");
  div.textContent = text;
  return div.innerHTML;
}

// Fecha modal com tecla ESC
document.addEventListener("keydown", function (e) {
  if (e.key === "Escape") {
    closeModal();
  }
});