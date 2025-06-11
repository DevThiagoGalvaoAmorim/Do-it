// ----------------------------------------------- para lembretes --------------------------------------------------

function salvarLembrete() {
    const titulo = document.querySelector(".titulo-lembrete-input")?.value.trim() || "";
    const descricao = document.querySelector(".descricao-lembrete-input")?.value.trim() || "";
    const dataHora = document.querySelector("#datatime_lembrete")?.value.trim() || "";
    const id = document.querySelector(".id-lembrete-input")?.value.trim() || "";
    if (!titulo || !dataHora) {
        alert("Título e data são obrigatórios!");
        return;
    }

    const formData = new FormData();
    formData.append("titulo", titulo);
    formData.append("descricao", descricao);
    formData.append("data_hora", dataHora);

    if (id) {
        formData.append("action", "update");
        formData.append("id", id);
    } else {
        formData.append("action", "create");
    }

    fetch("/models/lembrete_crud.php", {
        method: "POST",
        body: formData,
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadDatabaseData();
                loadUsersByMonthData();
                carregarLembretes(); // Atualiza a lista automaticamente

                const popup = document.getElementById("popupCriarLembrete");
                if (popup) {
                    popup.style.display = "none";
                } else {
                    console.error("Erro: Popup 'popupCriarLembrete' não encontrado.");
                }
            } else {
                alert("Erro ao salvar lembrete: " + data.message);
            }
        })
        .catch(error => console.error("Erro ao salvar lembrete:", error));
}

// Carregar lembretes ao abrir a página
document.addEventListener("DOMContentLoaded", carregarLembretes);

function carregarLembretes() {
    fetch("/models/lembrete_crud.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: new URLSearchParams({ action: "read" })
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const container = document.querySelector(".listagem_de_lembretes .lembretes");
                if (!container) {
                    console.error("Erro: Elemento '.listagem_de_lembretes .lembretes' não encontrado.");
                    return;
                }

                container.innerHTML = "";

                data.lembretes.forEach(lembrete => {
                    const divLembrete = document.createElement("div");
                    divLembrete.className = "lembrete";
                    divLembrete.dataset.id = lembrete.id;
                    divLembrete.dataset.date = lembrete.data_hora;

                    divLembrete.addEventListener("click", (event) => {
                        if (event.target.tagName === "BUTTON") return;
                        abrirPopupEditarLembrete("popupCriarLembrete", lembrete);
                    });

                    divLembrete.innerHTML = `
                    <h4 class="lembrete-titulo">${lembrete.titulo}</h4>
                    <p class="lembrete-descricao">${lembrete.descricao || "Sem descrição"}</p>
                    <small>${new Date(lembrete.data_hora).toLocaleString()}</small>
                    <div class="lembrete-botoes">
                        <button class="lembrete-botao">✏️</button>
                        <button type="button" class="archive_button lembrete-botao" onclick="deletarLembrete(${lembrete.id})">🗑️</button>
                    </div>
                `;

                    container.appendChild(divLembrete);
                });
            }
        })
        .catch(error => console.error("Erro ao carregar lembretes:", error));
}

function deletarLembrete(id) {
    const formData = new FormData();
    formData.append("action", "delete");
    formData.append("id", id);

    fetch("/models/lembrete_crud.php", {
        method: "POST",
        body: formData,
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadDatabaseData();
                loadUsersByMonthData();
                carregarLembretes();
            } else {
                alert("Erro ao deletar o lembrete.");
            }
        })
        .catch(error => console.error("Erro ao deletar o lembrete:", error));
}

// Abrir popup de criação
function abrirPopupCriarLembrete(id) {
    const popup = document.getElementById(id);
    if (!popup) {
        console.error(`Erro: O popup com o ID '${id}' não foi encontrado.`);
        return;
    }

    const tituloInput = document.querySelector(".titulo-lembrete-input");
    const descricaoInput = document.querySelector(".descricao-lembrete-input");
    const idInput = document.querySelector(".id-lembrete-input");

    if (tituloInput) tituloInput.value = "";
    if (descricaoInput) descricaoInput.value = "";
    if (idInput) idInput.value = "";

    popup.style.display = "flex";
}

// Abrir popup de edição
function abrirPopupEditarLembrete(id, lembrete) {
    const popup = document.getElementById(id);
    if (!popup) {
        console.error(`Erro: O popup com o ID '${id}' não foi encontrado.`);
        return;
    }

    const tituloInput = document.querySelector(".titulo-lembrete-input");
    const descricaoInput = document.querySelector(".descricao-lembrete-input");
    const idInput = document.querySelector(".id-lembrete-input");

    if (tituloInput) tituloInput.value = lembrete.titulo;
    if (descricaoInput) descricaoInput.value = lembrete.descricao;
    if (idInput) idInput.value = lembrete.id;

    popup.style.display = "flex";
}

// Fechar popup de lembretes
function fecharPopupLembrete(event, popupEl) {
    event.stopPropagation();
    if (!popupEl) {
        console.error("Erro: Elemento de popup não encontrado.");
        return;
    }

    popupEl.style.display = "none";
    const idInput = document.querySelector(".id-lembrete-input");
    if (idInput) idInput.value = "";
}



