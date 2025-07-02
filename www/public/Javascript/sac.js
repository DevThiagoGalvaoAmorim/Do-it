const clearname = document.querySelector("input.name");
const clearemail = document.querySelector("input.email");
const cleartext = document.querySelector("input.assunto");
const enviar = document.getElementById("enviar");
const modal = document.querySelector("div.modal");
let closed = document.getElementById("closed");

closed.addEventListener("click", () => {
    modal.classList.remove("abrir");
})

document.getElementById("enviar").addEventListener("click", function () {
    event.preventDefault();

    const mensagem = document.querySelector("input.assunto").value; // Captura o texto do input

    fetch("http://localhost:8080/models/sac_email.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: `mensagem=${encodeURIComponent(mensagem)}` // Envia mensagem corretamente
    })
        .then(response => response.text())
        .then(data => {
            let statusDiv = document.getElementById("status");
            if (statusDiv) {
                statusDiv.innerHTML = data;
            } else {
                console.error("Elemento #status não encontrado!");
            }
        })
        .catch(error => {
            console.error("Erro na requisição:", error);
        });

    if (!modal.classList.contains("abrir")) {
        modal.classList.add("abrir");
    };


    if (clearname) {
        clearname.value = "";
    }

    if (clearemail) {
        clearemail.value = "";
    }

    if (cleartext) {
        cleartext.value = "";
    }

});











