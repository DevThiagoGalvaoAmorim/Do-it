const toggleBtn = document.querySelector('.toggle-btn');
const sidebar = document.querySelector('.sidebar');

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
