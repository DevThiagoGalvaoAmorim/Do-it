//mensagem de erro
function showErrorMessage() {
    const errorBox = document.getElementById('errorBox');
    errorBox.style.display = 'block';
    
    // esconde a mensagem depois de 3seg
    setTimeout(() => {
        errorBox.style.display = 'none';
    }, 3000);
}