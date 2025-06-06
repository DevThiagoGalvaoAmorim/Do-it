document.getElementById("sendEmail").addEventListener("click", function () {
    fetch("http://localhost:8080/models/send_email.php", {
        method: "POST"
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
});